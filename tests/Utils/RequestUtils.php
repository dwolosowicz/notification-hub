<?php

namespace App\Tests\Utils;

use Symfony\Component\BrowserKit\Client;

trait RequestUtils
{
    public function postJson(string $url, array $payload, Client $client = null): Client
    {
        $client = $client ?: $this->createClient();

        $client->request('POST', $url, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($payload));

        return $client;
    }

    public function post(string $url, array $data, Client $client = null): Client
    {
        $client = $client ?: $this->createClient();

        $client->request('POST', $url, $data);

        return $client;
    }
}