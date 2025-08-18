<?php

namespace App\Console\Commands;

use App\Models\Webhook;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\ExchangeRate;
use Domain\Shared\Helpers\Queue;
use Domain\Webhooks\Facades\WebhookFacade;
use Domain\Worldpay\Helpers\WorldpayHelper;
use Domain\Worldpay\Helpers\WorldpaySftp;

class WebhookRetry extends Command
{
    protected $signature = 'webhook:retry';

    protected $description = 'Retry send webhooks failed';


    public function handle(): void
    {

        $webhooks = Webhook::whereIn('status', ['failed', 'pending'])->where('attempts', '>', 0)->where('entity_id', '!=', null);

        foreach ($webhooks->cursor() as $webhook) {
            Queue::publish('_webhook_send', WebhookFacade::class, 'send', $webhook->toArray(), true);
        }
    }
}
