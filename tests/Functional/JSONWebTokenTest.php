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

        $this->client->request('POST', '/api/jwt/token', [
            '_username' => $user->getUsername(),
            '_password' => 'password'
        ]);

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('token', json_decode($response->getContent(), true));
    }

    /** @test */
    public function fails_if_an_account_doesnt_exist()
    {
        $this->client->request('POST', '/api/jwt/token', [
            '_username' => 'fake_account',
            '_password' => 'password'
        ]);

        $response = $this->client->getResponse();

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertArrayNotHasKey('token', json_decode($response->getContent(), true));
    }
}