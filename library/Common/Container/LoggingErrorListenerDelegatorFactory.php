<?php

namespace Common\Container;

use Interop\Container\ContainerInterface;
use Zend\Stratigility\Middleware\ErrorHandler;

class LoggingErrorListenerDelegatorFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $name
     * @param callable $callback
     * @return ErrorHandler
     */
    public function __invoke(ContainerInterface $container, $name, callable $callback)
    {
        $config = $container->get(ConfigInterface::class);
        $listener = new LoggingErrorListener(
            $config->get('logging', false),
            $config->get('error-handler.logging-exceptions', [])
        );
        /* @var $repository ErrorHandler */
        $repository = $callback();
        $repository->attachListener($listener);
        return $repository;
    }
}
