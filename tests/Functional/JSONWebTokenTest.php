<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Tests\Utils\RequestUtils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JSONWebTokenTest extends WebTestCase
{
    use RequestUtils;

    /** @test */
    public function token_is_obtainable()
    {
        $this->createUser('johndoe', 'johndoe@doe.com', 'johndoe123');

        $client = $this->post('/api/jwt/token', [
            '_username' => 'johndoe',
            '_password' => 'johndoe123'
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('token', json_decode($client->getResponse()->getContent(), true));
    }

    /** @test */
    public function fails_if_an_account_doesnt_exist()
    {
        $client = $this->postJson('/api/jwt/token', [
            '_username' => 'fake_account',
            '_password' => 'some_password'
        ]);

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertArrayNotHasKey('token', json_decode($client->getResponse()->getContent(), true));
    }

    protected function createUser($username, $email, $password)
    {
        $container = $this->createClient()->getContainer();

        $user = new User();
        $user->setUsername($username);
        $user->setUsernameCanonical($username);
        $user->setEmail($email);
        $user->setEmailCanonical($email);
        $user->setPassword($container->get('security.password_encoder')->encodePassword($user, $password));

        $container->get('doctrine.orm.entity_manager')->persist($user);
        $container->get('doctrine.orm.entity_manager')->flush();
    }
}