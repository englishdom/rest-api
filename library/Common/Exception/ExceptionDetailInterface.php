<?php

namespace Common\Exception;

interface ExceptionDetailInterface
{
    /**
     * Get detail information about exception
     * @return string
     */
    public function getDetail();
}
