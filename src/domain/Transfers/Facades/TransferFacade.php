<?php

namespace Domain\Transfers\Facades;

use Domain\Transfers\Contracts\TransferServiceContract;

class TransferFacade
{
    public static function transfer(array $transfer): void
    {
        $service = app(TransferServiceContract::class);
        $service->transfer($transfer);
    }
    public static function updateTransfer(int|string $transferId, array $data): array|null
    {
        $service = app(TransferServiceContract::class);
        return $service->updateTransfer($transferId, $data);
    }
}
