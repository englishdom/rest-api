<?php

namespace User\Container;

use Common\Container\ConfigInterface;
use Psr\Container\ContainerInterface;
use User\Action\AuthenticationAction;

class AuthenticationActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new AuthenticationAction($config);
    }
}
