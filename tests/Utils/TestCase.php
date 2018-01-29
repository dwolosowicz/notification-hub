<?php

namespace App\Tests\Utils;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestCase extends WebTestCase {
    use FactoryMuffinLoader;

    /** @var Client */
    protected $client;

    public function setUp()
    {
        $this->client = $this->createClient();

        $this->setUpFactoryMuffin($this->client);
    }
}