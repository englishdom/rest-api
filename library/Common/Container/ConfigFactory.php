<?php

namespace Common\Container;

use Interop\Container\ContainerInterface;

class ConfigFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        return new Config($config);
    }
}
