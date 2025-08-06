<?php

namespace Domain\Pagarme\Services;

use Domain\Shared\Contracts\PaymentGatewayContract;

class PagarmeService implements PaymentGatewayContract
{
    public function tokenizeCard(array $payload): array
    {
        // Placeholder implementation
        return [
            'gateway' => 'PAGARME',
            'token' => $payload['token'] ?? 'sample-token',
        ];
    }
}
