<?php

namespace Common\Factory;

use Common\Container\Config;
use Interop\Container\ContainerInterface;

class ConfigFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        return new Config($config);
    }
}
