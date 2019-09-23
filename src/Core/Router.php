<?php
declare(strict_types=1);

namespace Jarenal\Core;

/**
 * Class Router
 * @package Jarenal\Core
 */
class Router implements RouterInterface
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var mixed|null
     */
    private $routes;
    /**
     * @var Container
     */
    private $container;

    /**
     * Router constructor.
     * @param Container $container
     * @param Config $config
     */
    public function __construct(Container $container, Config $config)
    {
        $this->container = $container;
        $this->config = $config;
        $this->routes = $this->config->get("routes");
    }

    /**
     * @return RouteInterface
     * @throws \Exception
     */
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
