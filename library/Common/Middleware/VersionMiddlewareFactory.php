<?php

namespace Common\Middleware;

use Common\Container\VersionInterface;
use Interop\Container\ContainerInterface;

class VersionMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $version = $container->get(VersionInterface::class);
        return new VersionMiddleware($version);
    }
}
