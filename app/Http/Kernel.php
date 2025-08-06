<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     */
    protected $middleware = [
        //
    ];

    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [],
        'api' => ['ensure-api-key', 'throttle:60,1'],
    ];

    /**
     * The application's route middleware aliases.
     */
    protected $middlewareAliases = [
        'ensure-api-key' => \App\Http\Middleware\EnsureApiKeyIsValid::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    ];
}
