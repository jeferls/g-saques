<?php

use Domain\Transfers\Contracts\TransferRepositoryContract;
use Domain\Transfers\Services\TransferService;

it('returns null when transfer to update does not exist', function () {
    $repository = $this->createMock(TransferRepositoryContract::class);
    $repository->expects($this->once())
        ->method('findById')
        ->with(1)
        ->willReturn(null);
    $repository->expects($this->never())->method('update');

    $service = new TransferService($repository);

    $result = $service->updateTransfer(1, ['amount' => 100]);

    expect($result)->toBeNull();
});

it('updates and returns transfer when record exists', function () {
    $repository = $this->createMock(TransferRepositoryContract::class);
    $existing = [
        'id' => 1,
        'transfer_id' => 123,
        'amount' => 100,
    ];
    $updated = [
        'id' => 1,
        'transfer_id' => 123,
        'amount' => 200,
    ];

    $repository->expects($this->once())
        ->method('findById')
        ->with(1)
        ->willReturn($existing);

    $repository->expects($this->once())
        ->method('update')
        ->with(1, ['amount' => 200])
        ->willReturn($updated);

    $service = new TransferService($repository);

    $result = $service->updateTransfer(1, ['amount' => 200]);

    expect($result)->toBe($updated);
});

it('retrieves a transfer by transfer id', function () {
    $repository = $this->createMock(TransferRepositoryContract::class);
    $transfer = [
        'id' => 1,
        'transfer_id' => 123,
        'amount' => 500,
    ];

    $repository->expects($this->once())
        ->method('findOne')
        ->with(['transfer_id' => 123])
        ->willReturn($transfer);

    $service = new TransferService($repository);

    $result = $service->getTransfer(123);

    expect($result)->toBe($transfer);
});

