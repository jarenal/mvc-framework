<?php
declare(strict_types=1);

namespace Jarenal\Core;

/**
 * Class Session
 * @package Jarenal\Core
 */
class Session implements SessionInterface
{

    /**
     * @param $param
     * @param $value
     */
    public function set($param, $value): void
    {
        $_SESSION[$param] = $value;
    }

    /**
     * @param $param
     * @param $default
     * @return mixed
     */
    public function get($param, $default)
    {
        if (isset($_SESSION[$param])) {
            return $_SESSION[$param];
        } else {
            return $default;
        }
    }

    /**
     * @param $param
     * @return bool
     */
    public function has($param): bool
    {
        return isset($_SESSION[$param]) ? true : false;
    }
}