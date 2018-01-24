<?php

namespace App\Lines;

use App\Lines\Model\LineConfig;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TypesProvider implements ContainerAwareInterface
{
    /** @var ContainerInterface */
    protected $container;

    public function getTypes(): array
    {
        $linesConfig = $this->container->getParameter('lines');

        return array_map(function($code, $isEnabled) {
            return new LineConfig($code, $isEnabled);
        }, $linesConfig, array_keys($linesConfig));
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}