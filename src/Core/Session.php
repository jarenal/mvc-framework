<?php

namespace Jarenal\Core;

class Session implements SessionInterface
{

    public function set($param, $value)
    {
        $_SESSION[$param] = $value;
    }

    public function get($param)
    {
        return $_SESSION[$param];
    }

    public function has($param): bool
    {
        return isset($_SESSION[$param]) ? true : false;
    }
}