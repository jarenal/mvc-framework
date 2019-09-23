<?php
declare(strict_types=1);

namespace Jarenal\Core;

use Exception;

/**
 * Class Kernel
 * @package Jarenal\Core
 */
class Kernel implements KernelInterface
{
    /**
     * @var Router
     */
    private $router;
    /**
     * @var Container
     */
    private $container;

    /**
     * Kernel constructor.
     * @param Container $container
     * @param Router $router
     */
    public function __construct(Container $container, Router $router)
    {
        $this->container = $container;
        $this->router = $router;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function run(): string
    {
        try {
            $route = $this->router->getRoute();
            $controller = $this->container->get($route->getController());
            $output = call_user_func([$controller, $route->getAction()]);
            exit($output);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}