<?php

return [

    'paths' => ['api/*', 'login', 'logout','register'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['https://recipist-frontend.vercel.app','http://localhost:5173','http://127.0.0.1:5173','https://recipist-backend.ir'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];

