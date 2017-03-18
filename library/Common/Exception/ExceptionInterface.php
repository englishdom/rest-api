<?php

namespace Common\Exception;

interface ExceptionInterface
{
    /**
     * Get error identifier
     * @return string
     */
    public function getIdentifier(): string;
}
