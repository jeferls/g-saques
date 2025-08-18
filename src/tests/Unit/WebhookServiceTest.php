<?php

use Domain\Webhooks\Repositories\WebhookRepository;
use Domain\Webhooks\Services\WebhookService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

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

    $repository = $this->createMock(WebhookRepository::class);
    $repository->expects($this->once())
        ->method('findById')
        ->with(1)
        ->willReturn($webhook);
    $repository->expects($this->once())
        ->method('update')
        ->with(1, $this->callback(function ($data) {
            return $data['status'] === 'sent' && $data['response_status'] === 200;
        }))
        ->willReturn(array_merge($webhook, ['status' => 'sent', 'response_status' => 200]));

    $service = new WebhookService($repository);
    $service->send(['id' => 1], true);
});
