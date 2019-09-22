<?php

namespace Jarenal\Core;

class Kernel implements KernelInterface
{
    private $router;
    private $container;

    public function __construct(Container $container, Router $router)
    {
        $this->container = $container;
        $this->router = $router;
    }

    public function run()
    {
        $route = $this->router->getRoute();
        $controller = $this->container->get($route->getController());
        $output = call_user_func([$controller, $route->getAction()]);
        exit($output);
    }
}