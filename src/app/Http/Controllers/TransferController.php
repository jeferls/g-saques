<?php

namespace App\Http\Controllers;

use Domain\Shared\Factory\GatewayFactory;
use Domain\Shared\Helpers\APIResponse;
use Domain\Shared\Helpers\Logger;
use Domain\Shared\Helpers\Queue;
use Domain\Transfers\Contracts\TransferServiceContract;
use Domain\Transfers\Facades\TransferFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class TransferController
{
    public function __construct() {}

    public function transferBatch(Request $request)
    {
        Log::info(__METHOD__ . ' REQUEST', $request->all());

        $items = $request->input('items');

        if (!empty($items) && is_array($items)) {
            foreach ($items as $item) {
                Queue::publish('_transfer_item', TransferFacade::class, 'transfer', $item);
            }
        }

        return APIResponse::success(["message" => "Batch is processing"], ['status' => 'processing', 'items' => $items]);
    }

    public function getTransfer(int|string $transferId)
    {
        $service = app(TransferServiceContract::class);
        $trasfer = $service->getTransfer($transferId);
        if ($trasfer) {
            $response = GatewayFactory::build($trasfer['gateway'])->getTransfer($transferId);

            if ($response->failed()) {
                return APIResponse::badRequest($response->body());
            }

            return APIResponse::success(["message" => "GET TRANSACTION"], $response->json());
        }

        Logger::warning(__METHOD__, "TRANSFER NOT FOUND", ['transferId' => $transferId]);
        return APIResponse::notFound(["TRANSFER NOT FOUND: " . $transferId]);
    }
}
