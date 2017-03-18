<?php

namespace Common\Container;

use Common\Exception\ExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Zend\Log;

class LoggingErrorListener
{
    private $loggingMode;
    private $loggingPath;

    /**
     * LoggingErrorListener constructor.
     * @param $loggingMode
     * @param $loggingPath
     * @internal param LoggerInterface $logger
     */
    public function __construct($loggingMode, $loggingPath)
    {
        $this->loggingMode = $loggingMode;
        $this->loggingPath = $loggingPath;
    }

    public function __invoke(Throwable $error, ServerRequestInterface $request, ResponseInterface $response)
    {
        if ($this->loggingMode) {
            $logger = $this->createLogger($error);
            $logger->err(
                $error->getMessage(),
                [
                    'code' => $response->getStatusCode(),
                    'method' => $request->getMethod(),
                    'uri' => (string)$request->getUri(),
                ]
            );
        }
    }

    /**
     * Create logger
     * @param Throwable $error
     * @return Log\LoggerInterface
     */
    protected function createLogger(Throwable $error): Log\LoggerInterface
    {
        if ($this->loggingPath) {
            $stream = $this->loggingPath . '/error-handler';
            if ($error instanceof ExceptionInterface) {
                $stream = $this->loggingPath . '/' . $error->getIdentifier();
            }
        } else {
            $stream = 'php://output';
        }

        $writer = new Log\Writer\Stream($stream);
        $format = '%timestamp% %priorityName% (%priority%): %message%';
        $formatter = new Log\Formatter\Simple($format);
        $writer->setFormatter($formatter);

        $logger = new Log\Logger();
        $logger->addWriter($writer);
        return $logger;
    }
}
