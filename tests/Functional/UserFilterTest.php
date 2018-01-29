<?php

namespace App\Tests\Functional;

use App\Entity\Channel;
use App\Entity\User;
use App\Tests\Utils\TestCase;

class UserFilterTest extends TestCase
{
    /** @test */
    public function doctrine_filtering_out_the_entities_that_dont_belong_to_the_authenticated_user()
    {
        $user1 = $this->factory->create(User::class);
        $user2 = $this->factory->create(User::class);

        $this->factory->create(Channel::class, ['user' => $user1]);
        $this->factory->create(Channel::class, ['user' => $user2]);

        $this->client->request('POST', '/api/jwt/token', [
            '_username' => $user1->getUsername(),
            '_password' => 'password'
        ]);

        $jsonBody = json_decode($this->client->getResponse()->getContent(), true);

        $this->client->request(
            'GET',
            '/api/channels',
            [],
            [],
            [
                'HTTP_CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer {$jsonBody['token']}"
            ]
        );

        $this->assertCount(
            1,
            json_decode($this->client->getResponse()->getContent(), true),
            'The default query should be filtered by the currently authenticated user.'
        );
    }
}