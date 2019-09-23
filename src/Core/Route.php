<?php
declare(strict_types=1);

namespace Jarenal\Core;

/**
 * Class Route
 * @package Jarenal\Core
 */
class Route implements RouteInterface
{
    /**
     * @var
     */
    private $controller;
    /**
     * @var
     */
    private $action;

    /**
     * @param string $controller
     * @return RouteInterface
     */
    public function setController(string $controller): RouteInterface
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @param string $action
     * @return RouteInterface
     */
    public function setAction(string $action): RouteInterface
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
