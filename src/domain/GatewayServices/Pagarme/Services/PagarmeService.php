<?php

declare(strict_types=1);

namespace Domain\GatewayServices\Pagarme\Services;

use Domain\Shared\Contracts\ServiceGatewayContract;
use Domain\Shared\Helpers\Logger;
use Domain\Shared\Helpers\Queue;
use Domain\Transfers\Contracts\TransferRepositoryContract;
use Domain\Transfers\Contracts\TransferServiceContract;
use Domain\Transfers\Facades\TransferFacade;
use Domain\Webhooks\Facades\WebhookFacade;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

class PagarmeService extends ServiceGatewayContract
{
    private function pagarmeRequest(string $method, string $endpoint, $data = []): mixed //v5
    {
        $baseUrl = config('pagarme.V5.API_URL');
        $apiKey = config('pagarme.V5.API_KEY');

        $headers = [
            'Authorization' => "Basic " . base64_encode($apiKey . ":"),
            'Content-Type'  => 'application/json'
        ];

        if (!empty($data['idempotency_key'])) {
            $headers['Idempotency-Key'] = $data['idempotency_key'];
        }

        if(env('APP_ENV') === 'local'){
            $baseUrl = 'http://xgrow-by-greenn-saques-hub-nginx/api/mock/postTransferPagarme';
            $headers = [
                'Content-Type'  => 'application/json',
                'X-API-Key' => 'a91b5e15-c4e4-4892-8649-2789ee5d6032'
            ];
        }

        return Http::withHeaders($headers)->{$method}("{$baseUrl}{$endpoint}", $data);
    }

    public function parse(array $data): mixed
    {
        return [
            "amount" => $data['amount'],
            "recipient_id" => $data['recipient_id'],
            "metadata" => $data['metadata'],
            "idempotency_key" => $data['idempotency_key']
        ];
    }

    public function transfer(array $data, array $transfer): array
    {
        $payload = $this->parse($data);
        $response = $this->pagarmeRequest('post', '/transfers', $payload);

        if ($response->failed()) {
            Logger::error(__METHOD__, new Exception('PAGARME TRANSFER FAILED'), ['response' => $response->body()]);
            throw new Exception("ERROR PROCESSING REQUEST", 1);
        }
        $transferPagarme = $response->json();
        $transferPagarme['gateway'] = $data['gateway'];
        Logger::info(__METHOD__, ' PAGARME TRANFER REQUESTED', ['request' => $payload, 'response' => $transferPagarme]);

        $update = [
            'status' => $transferPagarme['status'],
            'transfer_id' => $transferPagarme['id'],
            'type' => $transferPagarme['type'],
            'fee' => $transferPagarme['fee'],
            'target_type' => $transferPagarme['target_type'],
            'target_id' => $transferPagarme['target_id'],
        ];

        $transfer = TransferFacade::updateTransfer($transfer['id'], $update);

        $data = array_merge($transfer, [
			"entity" => 'Transfer',
			"entity_id" => $transfer['id'],
			"event_type" => "transfer.created",
			"origin" => "proxy",
			"gateway_id" => $transfer['transfer_id'],
			"gatewayTransfer" => $transferPagarme,
		]);

        return $data;
    }

    public function getTransfer(int|string $transferId): mixed
    {
        return $this->pagarmeRequest("get", "/transfers/$transferId");
    }

    public function parseWebhook(array $data): mixed
    {
        $event = Arr::get($data, 'event_type', null);
        $origin = Arr::get($data, 'origin', null);
        $currentStatus = Arr::get($data, 'status', null);
        $id = Arr::get($data, 'gateway_id');
        $entityId = $data['id'];
        $raw = $data;

        #Quando o evento vem da Pagarme
        if ($data['origin'] === 'gateway') {
            $event = Arr::get($data, 'type');
            $id = Arr::get($data, 'data.id');
            $currentStatus = Arr::get($data, 'data.status', null);

            $entity = $this->updateEntity($data);
            if ($entity) {
                $entityId = $entity['id'] ?? null;
                $raw = $entity;
            }
        }

        return [
            'event' => $event,
            'id' => $id,
            'current_status' => $currentStatus,
            'origin' => $origin,
            'entity_id' => $entityId,
            'raw' => $raw
        ];
    }

    public function updateEntity(array $data)
    {
        if (str_contains($data['type'], 'transfer')) {
            $transferService = app(TransferServiceContract::class);
            $transferId = Arr::get($data, 'data.id', null);

            if (! $transferId) {
                Logger::warning(__METHOD__, "ID NOT FOUND", $data);
                return null;
            }
            $transfer = $transferService->updateTransfer($transferId, $data['data']);
            $transfer['gatewayTransfer'] = $data['data'];
            return $transfer;
        } else {

            Logger::warning(__METHOD__, "TYPE NOT IMPLEMENTED", $data);
            return null;
        }
    }

    public function getEntity(array $data): mixed
    {
        if (str_contains($data['type'], 'transfer')) {
            $transferRepository = app(TransferRepositoryContract::class);
            $transferId = Arr::get($data, 'data.id', null);

            if (! $transferId) {
                Logger::warning(__METHOD__, "ID NOT FOUND", $data);
                return null;
            }
            $transfer = $transferRepository->findOne(['transfer_id' => $transferId]);
            if (! $transfer) {
                Logger::warning(__METHOD__, "TRANSFER NOT FOUND", $data);
                return null;
            }
            $transfer['entity'] = "Transfer";
            $transfer['gatewayTransfer'] = Arr::get($data, 'data', null);
            return $transfer;
        } else {

            Logger::warning(__METHOD__, "TYPE NOT IMPLEMENTED", $data);
            return null;
        }
    }

    public function updateByWebhook(array $webhook): bool
    {
        $entity = $this->getEntity($webhook);
        Logger::info(__METHOD__ , "UPDATE ".$entity['entity'], [
            'original' => $entity,
            "change" => $webhook
        ]);

        if($entity['entity'] === "Transfer"){
            $transferRepository = app(TransferRepositoryContract::class);

            if($entity['status'] === "transferred"){
                Logger::emergency(__METHOD__, new Exception('POSTBACK DUPLICADO'), $webhook);
                return false;
            }

            $transferRepository->update($entity['id'],[
                'status' => Arr::get($webhook, 'data.status', $entity['status'])
            ]);
        }
        return true;
    }
}
