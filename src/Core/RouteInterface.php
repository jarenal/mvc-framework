<?php
declare(strict_types=1);

namespace Jarenal\Core;

interface RouteInterface
{
    public function setController(string $controller): self;

    public function getController(): string;

    public function setAction(string $action): self;

    public function getAction(): string;
}
