<?php

namespace Common\Factory;

use Common\Container\ConfigInterface;
use Common\Middleware\PrepareResponseMiddleware;
use Interop\Container\ContainerInterface;

class PrepareResponseMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new PrepareResponseMiddleware($config);
    }
}
