<?php

declare(strict_types=1);

namespace Domain\Transfers\Services;

use Domain\Shared\Factory\GatewayFactory;
use Domain\Shared\Helpers\Logger;
use Domain\Shared\Helpers\Queue;
use Domain\Transfers\Contracts\TransferRepositoryContract;
use Domain\Transfers\Contracts\TransferServiceContract;
use Domain\Webhooks\Facades\WebhookFacade;



class TransferService  implements TransferServiceContract
{
	public function __construct(
		private TransferRepositoryContract $repository,
	) {}
	public function transfer(array $data): void
	{
		$transfer = $this->repository->create([
			"gateway" => $data['gateway'],
			"amount" => $data['amount'],
			"source_id" => $data['recipient_id'],
			"source_type" => 'recipient',
			"idempotency_key" => $data['idempotency_key']
		]);

		$data['metadata'] = [
			"local_id" => $transfer['id']
		];

		$data = GatewayFactory::build($data['gateway'])->transfer($data, $transfer);

		Queue::publish('_webhook_register', WebhookFacade::class, 'register', $data);
	}

	

	/**
	 * Get Transfer
	 * @param int|string $transferId
	 * @return array|null
	 */
	public function getTransfer(int|string $transferId): array|null
	{
		return $this->repository->findOne(['transfer_id' => $transferId]);
	}

	/**
	 * updateTransfer
	 * @param int|string $transferId
	 * @param array $data
	 * @return array|null
	 */
	public function updateTransfer(int|string $id, array $data): mixed
	{
		$transfer = $this->repository->findById($id);
		if (!$transfer) {
			Logger::warning(__METHOD__, "TRANSFER NOT FOUND", $data);
			return null;
		}
		$updated = $this->repository->update($id, $data);
		Logger::info(__METHOD__, "TRANSFER UPDATED", ["original" => $transfer, "change" => $updated]);
		return $updated;
	}
}
