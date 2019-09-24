<?php

namespace Jarenal\Core;

use mysqli;

class MysqliWrapper
{
    public $mysqli;

    public function __construct()
    {
        $this->mysqli = new mysqli();
    }
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->mysqli, $method), $args);
    }

    public function __get($name)
    {
        return $this->mysqli->$name;
    }
}
