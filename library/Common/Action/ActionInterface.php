<?php

namespace Common\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ActionInterface extends MiddlewareInterface
{
    const RESPONSE = 'json-api';
    const META = 'json-api-meta';
    const HTTP_CODE = 'http-code';

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface;
}
