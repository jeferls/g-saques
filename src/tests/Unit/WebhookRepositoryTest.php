<?php

use App\Models\Webhook;
use Domain\Shared\Repositories\BaseRepository;
use Domain\Webhooks\Repositories\WebhookRepository;
use Tests\TestCase;

uses(TestCase::class);

it('initializes with Webhook model', function () {
    $repository = new WebhookRepository();
    expect($repository)->toBeInstanceOf(BaseRepository::class);

    $reflection = new ReflectionClass($repository);
    $property = $reflection->getProperty('modelClass');
    $property->setAccessible(true);

    expect($property->getValue($repository))->toBe('\\' . Webhook::class);
});
