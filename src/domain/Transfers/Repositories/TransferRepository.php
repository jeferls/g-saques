<?php

declare(strict_types=1);

namespace Domain\Transfers\Repositories;

use App\Models\Transfer;
use Domain\Shared\Repositories\BaseRepository;
use Domain\Transfers\Contracts\TransferRepositoryContract;

class TransferRepository extends BaseRepository implements TransferRepositoryContract
{
    protected string $modelClass = Transfer::class;
}
