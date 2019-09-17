<?php

namespace Jarenal\Core;

use mysqli;
use ReflectionClass;

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
        $session = new Session();
        $config = new Config(PROJECT_ROOT_DIR."/config/config.yaml");
        $database = new Database(new mysqli(), $config);
        $reflector = new ReflectionClass($route->getController());
        $controller = $reflector->newInstanceArgs([$view, $session, $database]);
        $output = call_user_func([$controller, $route->getAction()]);
        exit($output);
    }
}