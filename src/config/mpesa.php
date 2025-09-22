<?php


return [

'api_key' => env('MPESA_API_KEY'),

'public_key' => env('MPESA_PUBLIC_KEY'),

'is_test' => env('MPESA_TEST', true),
'service_provider_code' => env('MPESA_SERVICE_PROVIDER_CODE', '171717'),
'is_async' => env('MPESA_IS_ASYNC', false),

];
