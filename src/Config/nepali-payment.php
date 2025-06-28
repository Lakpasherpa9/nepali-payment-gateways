<?php

return [
  'esewa' => [
    'merchant_code' => env('ESEWA_MERCHANT_CODE', 'EPAYTEST'),
    'secret_key' => env('ESEWA_SECRET_KEY', '8gBm/:&EnhH.1/q'),
    'base_url' => env('ESEWA_BASE_URL', 'https://rc-epay.esewa.com.np/api/epay/'),
    'success_url' => env('ESEWA_SUCCESS_URL'),
    'failure_url' => env('ESEWA_FAILURE_URL'),
  ],

  'khalti' => [
    'secret_key' => env('KHALTI_SECRET_KEY'),
    'public_key' => env('KHALTI_PUBLIC_KEY'),
    'base_url' => env('KHALTI_BASE_URL', 'https://khalti.com/api/v2/'),
    'return_url' => env('KHALTI_RETURN_URL'),
  ],

  'fonepay' => [
    'merchant_code' => env('FONEPAY_MERCHANT_CODE'),
    'secret_key' => env('FONEPAY_SECRET_KEY'),
    'base_url' => env('FONEPAY_BASE_URL', 'https://dev-clientapi.fonepay.com/'),
    'return_url' => env('FONEPAY_RETURN_URL'),
  ],

  // Default settings
  'currency' => 'NPR',
  'timeout' => 30,
];
