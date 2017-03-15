<?php
namespace Common\Action;

use Common\Container\Version;
use Common\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class VersionMiddleware
{
    const APPLICATION_TYPE = 'application/vnd.api+json';
    const VERSION_EXPRESSION = 'v[0-9]{1,2}+';

    /**
     * @var Version
     */
    private $version;

    /**
     * ApiVersionMiddleware constructor.
     * @param Version $version
     */
    public function __construct(Version $version)
    {
        $this->version = $version;
    }


    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $this->checkContentTypeHeaderLine($request);
        $this->checkAcceptHeaderLine($request);
        $this->setVersion($request);

        return $next($request, $response);
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
