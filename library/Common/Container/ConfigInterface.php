<?php

namespace Common\Container;

interface ConfigInterface
{

    public function get($keyString, $default = null);
}
