<?php
declare(strict_types=1);

namespace Jarenal\Core;

interface ViewInterface
{
    public function render(string $template, array $data): string;
}