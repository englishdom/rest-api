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
                Middleware\PrepareResponseMiddleware::class => Middleware\PrepareResponseMiddleware::class,
            ],
            'factories'  => [
                Container\ConfigInterface::class => Container\ConfigFactory::class,
            ],
        ];
    }
}
