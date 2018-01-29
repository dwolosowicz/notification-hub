<?php

namespace App\Tests\Utils;

use League\FactoryMuffin\Exceptions\DirectoryNotFoundException;
use League\FactoryMuffin\FactoryMuffin;
use League\FactoryMuffin\Stores\RepositoryStore;
use Symfony\Bundle\FrameworkBundle\Client;

trait FactoryMuffinLoader
{
    /** @var FactoryMuffin */
    protected $factory;

    protected function setUpFactoryMuffin(Client $client)
    {
        $container = $client->getContainer();

        $store = new RepositoryStore(
            $container->get('doctrine.orm.entity_manager')
        );

        $this->factory = new FactoryMuffin($store);

        $factoriesPath = $container->getParameter('kernel.project_dir') . '/tests/factories';

        try {
            $this->factory->loadFactories(
                $factoriesPath
            );
        } catch (DirectoryNotFoundException $e) {
            throw new \RuntimeException("FactoryMuffin library requires a \"$factoriesPath\" directory to be present.");
        }
    }
}