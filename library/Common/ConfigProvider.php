<?php

namespace Common;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies()
    {
        return [
            'invokables' => [
                Container\VersionInterface::class => Container\Version::class,
            ],
            'factories'  => [
                Container\ConfigInterface::class => Container\ConfigFactory::class,
                Middleware\VersionMiddleware::class => Middleware\VersionMiddlewareFactory::class,
            ],
        ];
    }
}
