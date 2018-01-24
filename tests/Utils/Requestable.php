<?php

namespace App\Tests\Utils;

use Symfony\Component\BrowserKit\Client;

trait Requestable
{
    public function postJson(string $url, array $payload, Client $client = null): Client
    {
        $client = $client ?: $this->createClient();

        $client->request('POST', $url, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($payload));

        return $client;
    }
}