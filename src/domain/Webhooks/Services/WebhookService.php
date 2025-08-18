<?php

declare(strict_types=1);

namespace Domain\Webhooks\Services;

use Domain\Shared\Factory\GatewayFactory;
use Domain\Shared\Helpers\Logger;
use Domain\Shared\Helpers\Queue;
use Domain\Webhooks\Facades\WebhookFacade;
use Domain\Webhooks\Repositories\WebhookRepository;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;


class WebhookService
{
	public function __construct(private WebhookRepository $repository) {}

	public function register(array $data)
	{
		$origin = Arr::get($data, 'origin', 'gateway');
		$eventType = Arr::get($data, 'event_type', null);
		$entity = Arr::get($data, 'entity', null);
		$entityId = Arr::get($data, 'entity_id', null);

		if ($origin && $origin === "gateway") {
			$eventType = Arr::get($data, 'type', null);
			$updated = GatewayFactory::build($data['gateway'])->updateByWebhook($data);

			$localData = GatewayFactory::build($data['gateway'])->getEntity($data);

			if ($localData) {
				$entity = $localData['entity'];
				$entityId = $localData['id'];				
				$localData["raw"] = json_encode($data);
			}
		}

		$dataToSend = GatewayFactory::build($data['gateway'])->parseWebhook($data);

		$webhook = $this->repository->create([
			'url' => env('WEBHOOK_POSTBACK_URL'),
			'raw' => json_encode($dataToSend),
			'origin' => $origin,
			'event_type' => $eventType,
			'entity' =>  $entity,
			'entity_id' => $entityId,
			'gateway' => Arr::get($data, 'gateway', null)
		]);

		Queue::publish('_webhook_send', WebhookFacade::class, 'send',  $webhook, true);
	}

	public function send($webhook, $decrement = true): void
	{
		$webhook = $this->repository->findById($webhook['id']);
		$dataToSend = json_decode($webhook['raw']);
		Logger::info(__METHOD__, "SEND WEBHOOK", ['dataToSend' => $dataToSend]);

		$response = Http::withHeaders([
			'X-Webhook-Token' => env('WEBHOOK_TOKEN'),
		])->post($webhook['url'], $dataToSend);

		$update = [
			'last_attempt' => \Carbon\Carbon::now(),
			'response_status' => $response->status(),
			'response_raw' => json_encode($response->json()),
			'status' => ($response->status() == 200) ? 'sent' : 'failed'
		];

		if ($decrement) {
			$update['attempts'] = $webhook['attempts'] - 1;
		}

		$webhook = $this->repository->update($webhook['id'], $update);

		if ($webhook['status'] == 'failed') {
			Logger::emergency(__METHOD__, new Exception('FAIL WEBHOOK SEND'), [
				'code' => $response->status(),
				'response_raw' => json_encode($response->json()),
				'data' => $webhook,
			]);
		}
	}
}
