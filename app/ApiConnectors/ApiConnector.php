<?php

namespace App\ApiConnectors;

use Unsplash\HttpClient;

class ApiConnector
{
    public function connectToUnsplash()
    {
        HttpClient::init([
            'applicationId' => env('UNSPLASH_APP_ID'),
            'secret'        => env('UNSPLASH_APP_SECRET'),
            'callbackUrl'   => 'pib_sqli/oauth/callback',
            'utmSource'     => 'pib_sqli',
        ]);
    }
}