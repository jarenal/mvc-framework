<?php
declare(strict_types=1);

namespace Jarenal\Core;

interface ConfigInterface
{
    public function get(string $parameter);
}