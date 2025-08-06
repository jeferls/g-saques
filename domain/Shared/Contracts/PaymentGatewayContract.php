<?php

namespace Domain\Shared\Contracts;

interface PaymentGatewayContract
{
    public function tokenizeCard(array $payload): array;
}
