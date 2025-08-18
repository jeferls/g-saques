<?php

namespace App\Http\Controllers;

use Domain\Shared\Helpers\Logger;
use Domain\Shared\Helpers\Queue;
use Domain\Webhooks\Facades\WebhookFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class WebhookController
{
    public function __construct() {}

    public function receiveWebhook(Request $request)
    {
        $data = $request->all();
        Log::info(__METHOD__ . ' WEBHOOK RECEIVED', $data);

        if (!empty($data['id']) && str_starts_with($data['id'], 'hook_')) {
            $data['gateway'] = "PAGARME";
            $data['origin'] = "gateway";
        } else {
            Logger::warning(__METHOD__, ' NOT FOUND INTEGRATION', $data);
        }

        Queue::publish('_webhook_register', WebhookFacade::class, 'register', $data);
    }
}
