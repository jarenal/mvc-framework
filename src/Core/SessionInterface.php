<?php
declare(strict_types=1);

namespace Jarenal\Core;

interface SessionInterface
{
    public function set($param, $value): void;

    public function get($param, $default);

    public function has($param): bool;
}