<?php

namespace Common\Middleware;

use Common\Action\ActionInterface;
use Common\Container\ConfigInterface;
use Common\Exception\RuntimeException;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Http\Response as HttpResponse;
use Zend\Diactoros\Stream;

class PrepareResponseMiddleware implements MiddlewareInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * PrepareResponseMiddleware constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        /* Get HTTP code */
        $httpCode = $request->getAttribute(ActionInterface::HTTP_CODE);
        if (!$httpCode) {
            $httpCode = HttpResponse::STATUS_CODE_200;
        }

        $response = (new Response())->withStatus($httpCode)->withHeader('Content-Type', 'application/vnd.api+json');

        $fractal = $request->getAttribute(ActionInterface::RESPONSE);
        if (!$fractal instanceof Item && !$fractal instanceof Collection) {
            throw new RuntimeException('Unsupported type');
        }

        /* Set META info */
        $meta = $request->getAttribute(ActionInterface::META);
        if (!empty($meta) && is_array($meta)) {
            $fractal->setMeta($meta);
        }

        $fractalManager = new Manager();
        $fractalManager->setSerializer(new JsonApiSerializer($this->config->get('host') . 'api'));
        $jsonData = $fractalManager->createData($fractal)->toJson();

        $stream = new Stream('php://memory', 'w');
        $stream->write($jsonData);

        return $response->withBody($stream);
    }
}
