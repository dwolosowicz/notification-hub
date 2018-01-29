<?php

namespace App\Tests\Functional;

use App\Tests\Utils\TestCase;

class CreateAccountTest extends TestCase
{
    /** @test */
    public function creates_an_account()
    {
        $this->client->request('POST', '/api/account', [], [], ['HTTP_CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'], json_encode([
            'username' => 'johndoe',
            'email' => 'john@doe.com',
            'plainPassword' => 'random_password'
        ]));

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }

    /** @test */
    public function fails_to_create_an_account_if_payload_is_invalid()
    {
        $this->client->request('POST', '/api/account', [], [], ['HTTP_CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'], json_encode([]));

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }
}