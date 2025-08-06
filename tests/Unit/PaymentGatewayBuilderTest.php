<?php

use Domain\Pagarme\Services\PagarmeService;
use Domain\Shared\Builders\PaymentGatewayBuilder;
use InvalidArgumentException;

test('builds pagarme service from payload', function () {
    $builder = PaymentGatewayBuilder::fromRequest(['gateway' => 'PAGARME']);

    $service = $builder->build();

    expect($service)->toBeInstanceOf(PagarmeService::class);
});

test('throws exception for unsupported gateway', function () {
    PaymentGatewayBuilder::fromRequest(['gateway' => 'UNKNOWN'])->build();
})->throws(InvalidArgumentException::class);
