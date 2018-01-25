<?php

namespace App\Tests\Functional;

use App\Tests\Utils\Requestable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateAccountTest extends WebTestCase
{
    use Requestable;

    /** @test */
    public function creates_an_account()
    {
        $client = $this->postJson('/api/account', [
            'username' => 'johndoe',
            'email' => 'john@doe.com',
            'plainPassword' => 'random_password'
        ]);

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    /** @test */
    public function fails_to_create_an_account_if_payload_is_invalid()
    {
        $client = $this->postJson('/api/account', []);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}