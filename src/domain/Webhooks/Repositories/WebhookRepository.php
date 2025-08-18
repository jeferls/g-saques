<?php

declare(strict_types=1);

namespace Domain\Webhooks\Repositories;

use App\Models\Webhook;
use Domain\Shared\Repositories\BaseRepository;

class WebhookRepository extends BaseRepository
{
    protected string $modelClass = Webhook::class;
}
