<?php

use Domain\Pagarme\Services\PagarmeService;
use Domain\Shared\Builders\ServiceGatewayBuilder;
use InvalidArgumentException;

test('builds pagarme service from payload', function () {
    $builder = ServiceGatewayBuilder::fromRequest(['gateway' => 'PAGARME']);

    $service = $builder->build();

    expect($service)->toBeInstanceOf(PagarmeService::class);
});

test('throws exception for unsupported gateway', function () {
    ServiceGatewayBuilder::fromRequest(['gateway' => 'UNKNOWN'])->build();
})->throws(InvalidArgumentException::class);
