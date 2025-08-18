<?php

use App\Models\Transfer;
use Domain\Shared\Repositories\BaseRepository;
use Domain\Transfers\Repositories\TransferRepository;
use Tests\TestCase;

uses(TestCase::class);

it('initializes with Transfer model', function () {
    $repository = new TransferRepository();
    expect($repository)->toBeInstanceOf(BaseRepository::class);

    $reflection = new ReflectionClass($repository);
    $property = $reflection->getProperty('modelClass');
    $property->setAccessible(true);

    expect($property->getValue($repository))->toBe('\\' . Transfer::class);
});
