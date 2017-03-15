<?php

namespace Common\Container;

class Config implements ConfigInterface
{
    const DELIMITER = '.';

    /**
     * @var array
     */
    private $config;

    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function get($keyString, $default = null)
    {
        $keys = explode(self::DELIMITER, $keyString);
        $value = $this->config;

        foreach ($keys as $key) {
            if (is_array($value)) {
                $value = $this->getValue($key, $value);
            }
            if ($value === null) {
                $value = $default;
            }
        }
        return $value;
    }

    protected function getValue($key, array $config)
    {
        if (array_key_exists($key, $config)) {
            return $config[$key];
        }
        return null;
    }
}
