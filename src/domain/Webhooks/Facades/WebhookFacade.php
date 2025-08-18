<?php

namespace Domain\Webhooks\Facades;

use Domain\Webhooks\Services\WebhookService;

class WebhookFacade
{
    public static function register(array $data): void
    {        
        $service = app(WebhookService::class);
        $service->register($data);
    }

    public static function send(array $webhook, bool $decrement = true): void
    {        
        $service = app(WebhookService::class);
        $service->send($webhook, $decrement);
    }

    
}
