<?php

namespace Domain\Shared\Contracts;

abstract class ServiceGatewayContract
{
    abstract public function transfer(array $payload,  array $transfer);
    abstract public function parse(array $payload): mixed;
    abstract public function parseWebhook(array $payload): mixed;
    abstract public function getEntity(array $payload): mixed;
    abstract public function getTransfer(int|string $transferId): mixed;
    abstract public function updateByWebhook(array $webhook): bool;
}
