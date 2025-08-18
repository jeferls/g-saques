<?php

use Domain\Transfers\Contracts\TransferRepositoryContract;
use Domain\Transfers\Services\TransferService;
use Mockery;

// Ensure Mockery expectations are verified and mocks are cleaned up
afterEach(function () {
    Mockery::close();
});

it('returns null when transfer to update does not exist', function () {
    $repository = Mockery::mock(TransferRepositoryContract::class);
    $repository->shouldReceive('findById')->with(1)->andReturn(null);
    $repository->shouldNotReceive('update');

    $service = new TransferService($repository);

    $result = $service->updateTransfer(1, ['amount' => 100]);

    expect($result)->toBeNull();
});

it('updates and returns transfer when record exists', function () {
    $repository = Mockery::mock(TransferRepositoryContract::class);
    $repository->shouldReceive('findById')->with(1)->andReturn([
        'id' => 1,
        'transfer_id' => 123,
        'amount' => 100,
    ]);
    $repository->shouldReceive('update')->with(1, ['amount' => 200])->andReturn([
        'id' => 1,
        'transfer_id' => 123,
        'amount' => 200,
    ]);

    $service = new TransferService($repository);

    $result = $service->updateTransfer(1, ['amount' => 200]);

    expect($result)->toBe([
        'id' => 1,
        'transfer_id' => 123,
        'amount' => 200,
    ]);
});

it('retrieves a transfer by transfer id', function () {
    $repository = Mockery::mock(TransferRepositoryContract::class);
    $repository->shouldReceive('findOne')->with(['transfer_id' => 123])->andReturn([
        'id' => 1,
        'transfer_id' => 123,
        'amount' => 500,
    ]);

    $service = new TransferService($repository);

    $result = $service->getTransfer(123);

    expect($result)->toBe([
        'id' => 1,
        'transfer_id' => 123,
        'amount' => 500,
    ]);
});

