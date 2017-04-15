<?php

namespace Common\Middleware;


use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    const APPLICATION_TYPE = 'Authorization';

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $this->checkAuthorization($request);
    }

    protected function checkAuthorization(ServerRequestInterface $request)
    {
        if ($request->getHeaderLine('content-type') == self::APPLICATION_TYPE) {
            throw new Exception\NotAcceptableException();
        }
    }
}
