<?php

namespace Domain\Shared\Builders;

use Domain\Shared\Contracts\PaymentGatewayContract;
use Domain\Shared\Enums\PaymentGatewayEnum;
use Domain\Pagarme\Services\PagarmeService;
use InvalidArgumentException;
use ValueError;

class PaymentGatewayBuilder
{
    public function __construct(private PaymentGatewayEnum $gateway)
    {
    }

    public static function fromRequest(array $payload): self
    {
        if (!array_key_exists('gateway', $payload)) {
            throw new InvalidArgumentException('Gateway not provided');
        }

        try {
            $gateway = PaymentGatewayEnum::from($payload['gateway']);
        } catch (ValueError) {
            throw new InvalidArgumentException('Unsupported gateway: ' . $payload['gateway']);
        }

        return new self($gateway);
    }

    public function build(): PaymentGatewayContract
    {
        return match ($this->gateway) {
            PaymentGatewayEnum::PAGARME => new PagarmeService(),
        };
    }
}
