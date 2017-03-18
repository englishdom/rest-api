<?php

namespace Common\Container;

use Common\Middleware\ErrorResponseGenerator;
use Interop\Container\ContainerInterface;

class ErrorResponseGeneratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return ErrorResponseGenerator
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $responseCode = $config->get('error-handler.response-code', []);

        return new ErrorResponseGenerator($responseCode);
    }
}
