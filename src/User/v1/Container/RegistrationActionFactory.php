<?php

namespace User\Container;

use Interop\Container\ContainerInterface;
use User\Action\RegistrationAction;
use Zend\Db\Adapter\AdapterInterface;

class RegistrationActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        return new RegistrationAction($dbAdapter);
    }
}
