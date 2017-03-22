<?php

namespace User;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'factories'  => [
                Action\AuthenticationAction::class => Container\AuthenticationActionFactory::class,
                Action\RegistrationAction::class => Container\RegistrationActionFactory::class,
            ],
        ];
    }
}
