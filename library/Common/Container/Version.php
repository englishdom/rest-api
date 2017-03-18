<?php

namespace Common\Container;

class Version implements VersionInterface
{
    private $version = self::DEFAULT_VERSION;

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }
}
