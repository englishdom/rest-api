<?php

namespace Common\Container;

interface VersionInterface
{
    const DEFAULT_VERSION = '1';

    /**
     * @param string $version
     */
    public function setVersion($version);

    /**
     * @return string
     */
    public function getVersion();
}
