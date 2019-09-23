<?php
declare(strict_types=1);

namespace Jarenal\Core;

interface KernelInterface
{
    public function run(): string;
}
