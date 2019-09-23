<?php
declare(strict_types=1);

namespace Jarenal\Core;

interface RouterInterface
{
    public function getRoute(): RouteInterface;
}
