<?php

namespace Jarenal\Core;

class Kernel implements KernelInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function run()
    {
        $route = $this->router->getRoute();
        $view = new View();
        $reflector = new \ReflectionClass($route->getController());
        $controller = $reflector->newInstanceArgs([$view]);
        $output = call_user_func([$controller, $route->getAction()]);
        exit($output);
    }
}