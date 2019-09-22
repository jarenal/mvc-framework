<?php

namespace Jarenal\Core;

class Router implements RouterInterface
{
    private $config;
    private $routes;
    private $container;

    public function __construct(Container $container, Config $config)
    {
        $this->container = $container;
        $this->config = $config;
        $this->routes = $this->config->get("routes");
    }

    public function getRoute(): RouteInterface
    {
        foreach ($this->routes as $current) {
            $pattern = str_replace("/", "\/", $current["pattern"]);
            if (preg_match("/" . $pattern . "/", $_SERVER["REQUEST_URI"]) && in_array(
                $_SERVER["REQUEST_METHOD"],
                $current["method"]
            )) {
                $route = $this->container->get("Jarenal\Core\Route");
                $route->setController($current["controller"])
                    ->setAction($current["action"]);
                return $route;
            }
        }
    }
}
