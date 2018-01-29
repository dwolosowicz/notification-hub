<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Tests\Utils\TestCase;

class JSONWebTokenTest extends TestCase
{
    /** @test */
    public function token_is_obtainable()
    {
        $user = $this->factory->create(User::class);

        $this->client->request('POST', '/api/jwt/token', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'], json_encode([
            'username' => $user->getUsername(),
            'password' => 'password'
        ]));

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('token', json_decode($response->getContent(), true));
    }

    /** @test */
    public function fails_if_an_account_doesnt_exist()
    {
        $this->client->request('POST', '/api/jwt/token', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'], json_encode([
            'username' => 'fake_account',
            'password' => 'password'
        ]));

        $response = $this->client->getResponse();

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertArrayNotHasKey('token', json_decode($response->getContent(), true));
    }
}