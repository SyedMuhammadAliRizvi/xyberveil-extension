<?php

return [

  'secret_key' => env('PROXY_SECRET_KEY', env('APP_KEY')),

  'access_token_ttl_minutes' => (int) env('PROXY_ACCESS_TOKEN_TTL', 60),

  'refresh_token_ttl_days' => (int) env('PROXY_REFRESH_TOKEN_TTL_DAYS', 30),

  'trial_days' => (int) env('PROXY_TRIAL_DAYS', 30),

];
