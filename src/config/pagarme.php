<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pagarme configuration
    |--------------------------------------------------------------------------
    |
    |
    */

    'V5' => [
        'API_URL' =>  env('APP_ENV') === 'production' ? env('PAGARME_V5_API_URL') : 'https://api.pagar.me/core/v5',
        'API_KEY' =>  env('APP_ENV') === 'production' ? env('PAGARME_V5_API_KEY') : 'sk_test_760b27da0220485aacbc0ec4ba35e973',
        'STATUS' => [
            'created' => 'created',
            'pending_transfer' => 'pending_transfer',
            'transferred' => 'transferred',
            'failed' => 'failed',
            'processing' => 'processing',
            'canceled' => 'canceled',
        ],
    ],    

];