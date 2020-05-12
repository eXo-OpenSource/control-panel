<?php

return [
    'credentials' => array(
        'live' => array(
            'client_id' => env('PAYPAL_LIVE_CLIENT_ID',''),
            'secret' => env('PAYPAL_LIVE_SECRET',''),
        ),
        'sandbox' => array(
            'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID',''),
            'secret' => env('PAYPAL_SANDBOX_SECRET',''),
        )
    ),


    'settings' => array(
        'mode' => env('PAYPAL_MODE','sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'DEBUG'
    ),
];
