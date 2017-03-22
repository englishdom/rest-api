<?php

namespace Common\Container;

use Common\Exception\RuntimeException;
use Psr\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    const APPEND = 1;
    const PREPEND = 0;

    private $container;

    /**
     * @param string $key
     * @param mixed $value
     * @param int $action
     * @param bool $reWrite
     * @return bool
     * @throws RuntimeException
     */
    public function attach($key, $value, $action = self::APPEND, $reWrite = false)
    {
        if (array_key_exists($key, $this->container) && !$reWrite) {
            throw new RuntimeException('Item exists and can not rewrite.');
        }

        switch ($action) {
            case self::APPEND:
                array_push($this->container, [$key => $value]);
                break;
            case self::PREPEND:
                array_unshift($this->container, [$key => $value]);
                break;
            default:
                throw new RuntimeException('The action type did not support. Please use constants from Container.');
                break;
        }
        return true;
    }

    /**
     * @param $key
     * @return bool
     */
    public function detach($key)
    {
        if (array_key_exists($key, $this->container)) {
            unset($this->container[$key]);
            return true;
        }
        return false;
    }

    /**
     * @param string $id
     * @return mixed|null
     */
    public function get($id)
    {
        if (array_key_exists($id, $this->container)) {
            return $this->container[$id];
        }
        return null;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return array_key_exists($id, $this->container);
    }
}
