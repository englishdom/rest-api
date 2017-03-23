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
            'factories'  => [
                Container\ConfigInterface::class => Factory\ConfigFactory::class,
                Middleware\PrepareResponseMiddleware::class => Factory\PrepareResponseMiddlewareFactory::class,
            ],
        ];
    }
}
