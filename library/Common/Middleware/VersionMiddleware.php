<?php
namespace Common\Middleware;

use Common\Container\VersionInterface;
use Common\Exception;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;

class VersionMiddleware implements MiddlewareInterface
{
    const APPLICATION_TYPE = 'application/vnd.api+json';
    const VERSION_EXPRESSION = 'v[0-9]{1,2}+';

    /**
     * @var VersionInterface
     */
    private $version;

    /**
     * ApiVersionMiddleware constructor.
     * @param VersionInterface $version
     */
    public function __construct(VersionInterface $version)
    {
        $this->version = $version;
    }


    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $this->checkContentTypeHeaderLine($request);
        $this->checkAcceptHeaderLine($request);
        $this->setVersion($request);

        return $delegate->process($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @throws Exception\NotAcceptableException
     */
    protected function checkAcceptHeaderLine(ServerRequestInterface $request)
    {
        if ($request->getHeaderLine('accept') != self::APPLICATION_TYPE) {
            throw new Exception\NotAcceptableException();
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @throws Exception\UnsupportedMediaException
     */
    protected function checkContentTypeHeaderLine(ServerRequestInterface $request)
    {
        if ($request->getHeaderLine('content-type') != self::APPLICATION_TYPE) {
            throw new Exception\UnsupportedMediaException();
        }
    }

    /**
     * @param ServerRequestInterface $request
     */
    protected function setVersion(ServerRequestInterface $request)
    {
        $uri = $request->getUri();
        preg_match('~'.self::VERSION_EXPRESSION.'~', $uri->getPath(), $matches);
        if (isset($matches[0])) {
            $this->version->setVersion($matches[0]);
        }
    }
}
