<?php
namespace Common\Action;

use Common\Exception;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Expressive\Router\RouteResult;
use Throwable;
use Zend\Http;
use Zend\Log;

class ErrorHandlerMiddleware
{
    /**
     * @var array
     */
    protected $codeMap = [
        Exception\BadRequestException::class => Http\Response::STATUS_CODE_400,
        Exception\NotFoundException::class => Http\Response::STATUS_CODE_404,
        Exception\ConflictException::class => Http\Response::STATUS_CODE_409,
        Exception\UnauthorizedException::class => Http\Response::STATUS_CODE_401,
        Exception\UnsupportedMediaException::class => Http\Response::STATUS_CODE_415,
        Exception\NotAcceptableException::class => Http\Response::STATUS_CODE_406,
        \Exception::class => Http\Response::STATUS_CODE_500,
    ];

    protected $logException = [
        Exception\BadRequestException::class,
        Exception\ConflictException::class,
        \Exception::class,
    ];

    public function __invoke(Request $request, Response $response, $err = null)
    {
        if (!$err instanceof \Exception && !$err instanceof \Throwable) {
            $hasRoute = $request->getAttribute(RouteResult::class) !== null;
            if (!$hasRoute) {
                $err = new Exception\NotFoundException('Not found');
            } else {
                $err = new \Exception('Internal server error');
            }
        }

        return $this->prepareJson($response, $err);
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
     * @param string $identifier
     * @param int $httpCode
     * @param Throwable $exception
     * @param string $logUrl
     * @return array
     */
    protected function fillTemplate(string $identifier, int $httpCode, Throwable $exception, string $logUrl): array
    {
        $result = [
            'errors' => [
                'id' => (string)$identifier,
                'status' => (string)$httpCode,
                'title' => (string)$exception->getMessage(),
                'code' => (string)$exception->getCode(),
                'links' => [
                    'about' => $logUrl
                ]
            ]
        ];
        if (method_exists($exception, 'getDetail') && $exception->getDetail()) {
            $result['errors']['detail'] = $exception->getDetail();
        }

        return $result;
    }

    /**
     * @param Response $response
     * @param Throwable $exception
     * @return MessageInterface
     */
    protected function prepareJson(Response $response, Throwable $exception): MessageInterface
    {
        $exceptionName = get_class($exception);
        $httpCode = Http\Response::STATUS_CODE_503;
        $result = null;
        if (array_key_exists($exceptionName, $this->codeMap)) {
            $httpCode = $this->codeMap[$exceptionName];
        }

        if (in_array($exceptionName, $this->logException)) {
            $identifier = md5(microtime().rand(1, 10000) . $exception->getCode());
            $logUrl = $this->writeToLog($identifier, $exception);
            $result = $this->fillTemplate($identifier, $httpCode, $exception, $logUrl);
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
