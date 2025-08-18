<?php

declare(strict_types=1);

namespace Domain\Transfers\Contracts;

interface TransferServiceContract
{
   public function transfer(array $transfer): void;
   // public function createTransfer(array $payload): void;

   public function getTransfer(int|string $id): array|null;

   public function updateTransfer(int|string $transferId, array $data): mixed;
}
