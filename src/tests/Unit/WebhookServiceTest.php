<?php

use Domain\Webhooks\Repositories\WebhookRepository;
use Domain\Webhooks\Services\WebhookService;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

uses(TestCase::class);

afterEach(function () {
    Mockery::close();
});

it('sends webhook and updates repository', function () {
    Http::fake([
        'http://example.com/*' => Http::response(['result' => 'ok'], 200),
    ]);

    $webhook = [
        'id' => 1,
        'url' => 'http://example.com/hook',
        'raw' => json_encode(['foo' => 'bar']),
        'attempts' => 3,
    ];

    $repository = Mockery::mock(WebhookRepository::class);
    $repository->shouldReceive('findById')->with(1)->andReturn($webhook);
    $repository->shouldReceive('update')->with(1, Mockery::on(function ($data) {
        return $data['status'] === 'sent' && $data['response_status'] === 200;
    }))->andReturn(array_merge($webhook, ['status' => 'sent', 'response_status' => 200]));

    $service = new WebhookService($repository);
    $service->send(['id' => 1], true);
});
