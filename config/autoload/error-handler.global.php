<?php

use Zend\Http;
use Common\Exception;
use Common\Container;
use Zend\Expressive\Middleware\ErrorResponseGenerator;
use Zend\Stratigility\Middleware\ErrorHandler;

return [
    'dependencies' => [
        'factories'  => [
            ErrorResponseGenerator::class => Container\ErrorResponseGeneratorFactory::class,
        ],
        'delegators' => [
            ErrorHandler::class => [
                Container\LoggingErrorListenerDelegatorFactory::class,
            ]
        ]
    ],
    'error-handler' => [
        'response-code' => [
            Exception\BadRequestException::class => Http\Response::STATUS_CODE_400,
            Exception\NotFoundException::class => Http\Response::STATUS_CODE_404,
            Exception\ConflictException::class => Http\Response::STATUS_CODE_409,
            Exception\UnauthorizedException::class => Http\Response::STATUS_CODE_401,
            Exception\UnsupportedMediaException::class => Http\Response::STATUS_CODE_415,
            Exception\NotAcceptableException::class => Http\Response::STATUS_CODE_406,
        ],
        'logging-path' => 'data/log',
        'logging-exceptions' => [
            \Exception::class,
        ],
    ]
];
