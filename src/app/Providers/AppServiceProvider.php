<?php

namespace App\Providers;

use Domain\Transfers\Contracts\TransferRepositoryContract;
use Domain\Transfers\Contracts\TransferServiceContract;
use Domain\Transfers\Repositories\TransferRepository;
use Domain\Transfers\Services\TransferService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Services
        $this->app->bind(TransferServiceContract::class, TransferService::class);

        // Repositories
        $this->app->bind(TransferRepositoryContract::class, TransferRepository::class);

    }

    public function boot(): void
    {
        //
    }
}
