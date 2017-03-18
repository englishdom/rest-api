<?php

namespace Common\Exception;

abstract class AbstractException extends \Exception
{
    private $identifier;

    /**
     * @inheritdoc
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        $this->createIdentifier($message, $code);
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    private function createIdentifier($message, $code)
    {
        $this->identifier = md5($message . $code);
    }
}
