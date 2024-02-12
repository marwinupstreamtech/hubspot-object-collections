<?php

return [

    // The URL the application is running on
    'app_id' => env('HUBSPOT_APP_ID'),

    // A fixed hubspot.com API token for a single account
    'api_client_id' => env('HUBSPOT_APP_CLIENT_ID'),

    // Set the API Version to use
    'api_client_secret' => env('HUBSPOT_APP_CLIENT_SECRET'),

    // hubspot.com App Framework Configuration
    'api_redirect_uri' => env('HUBSPOT_REDIRECT_URI'),
    'api_scopes' => env('HUBSPOT_SCOPES'),
    'api_token' => env('HUBSPOT_PRIVATE_APP_API_TOKEN'),
];
