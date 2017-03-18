<?php
namespace Common\Middleware;

use Common\Exception;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\RouteResult;
use Throwable;
use Zend\Http;
use Zend\Log;

final class ErrorResponseGenerator
{
    /**
     * @var array
     */
    private $responseCode;

    /**
     * ErrorResponseGenerator constructor.
     * @param array $codeMap
     */
    public function __construct(array $codeMap)
    {
        $this->responseCode = $codeMap;
    }


    public function __invoke($err, ServerRequestInterface $request, ResponseInterface $response)
    {
        if (!$err instanceof \Exception && !$err instanceof \Throwable) {
            $hasRoute = $request->getAttribute(RouteResult::class) !== null;
            if (!$hasRoute) {
                $err = new Exception\NotFoundException('Not found');
            } else {
                $err = new \Exception('Internal server error');
            }
        }

        return $this->prepareJson($request, $response, $err);
    }

    /**
     * @param int $identifier
     * @param Throwable $exception
     * @return string
     */
    protected function writeToLog($identifier, $exception): string
    {
        $logUrl = 'data/logs/' . $identifier;
        $logger = new Log\Logger;
        $writer = new Log\Writer\Stream($logUrl);

        $logger->addWriter($writer);
        $logger->log(Log\Logger::INFO, $exception->getMessage() . ': ' . $exception->getTraceAsString());
        return $logUrl;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Throwable $exception
     * @param int $httpCode
     * @return array
     */
    protected function fillTemplate(ServerRequestInterface $request, Throwable $exception, int $httpCode): array
    {
        $identifier = 'unknown';
        if ($exception instanceof Exception\ExceptionInterface) {
            $identifier = $exception->getIdentifier();
        }

        $result = [
            'errors' => [
                'id' => (string)$identifier,
                'status' => (string)$httpCode,
                'title' => (string)$exception->getMessage(),
                'code' => (string)$exception->getCode(),
                'source' => [
                    'pointer' => $request->getUri()->getPath(),
                    'parameter' => $request->getUri()->getQuery()
                ]
            ]
        ];
        if ($exception instanceof Exception\ExceptionDetailInterface) {
            $result['errors']['detail'] = $exception->getDetail();
        }

        return $result;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param Throwable $exception
     * @return MessageInterface
     */
    protected function prepareJson(ServerRequestInterface $request, ResponseInterface $response, Throwable $exception)
    {
        $exceptionName = get_class($exception);
        if (array_key_exists($exceptionName, $this->responseCode)) {
            $httpCode = $this->responseCode[$exceptionName];
            $result = null;
        } else {
            $result = $this->fillTemplate($request, $exception, Http\Response::STATUS_CODE_503);
            $httpCode = 200;
            $result = json_encode($result);
        }

        $newResponse = $response
            ->withHeader('Content-type', 'application/vnd.api+json')
            ->withStatus($httpCode);
        $newResponse->getBody()->write($result);

        return $newResponse;
    }
}
