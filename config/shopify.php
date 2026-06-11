<?php

return [
    'api_key'      => env('SHOPIFY_API_KEY'),
    'api_secret'   => env('SHOPIFY_API_SECRET'),
    'shop_domain'  => env('SHOPIFY_SHOP_DOMAIN'),
    'access_token' => env('SHOPIFY_ACCESS_TOKEN'),
    'scopes'       => env('SHOPIFY_SCOPES', 'read_products,write_products,read_orders,write_orders'),
    'api_version'  => '2024-01',
];
