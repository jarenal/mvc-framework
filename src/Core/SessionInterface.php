<?php

namespace Jarenal\Core;

interface SessionInterface
{
    public function set($param, $value);

    public function get($param);

    public function has($param): bool;
}