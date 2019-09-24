<?php

namespace JarenalTests\Core;

use Exception;
use Jarenal\App\Controller\Steps;
use Jarenal\Core\Container;
use Jarenal\Core\Route;
use Jarenal\Core\Router;
use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    private $container;

    public function setUp(): void
    {
        $this->container = new Container();

        $controller = $this->getMockBuilder(Steps::class)
            ->disableOriginalConstructor()
            ->setMethods(["step1"])
            ->getMock();

        $controller->method("step1")
            ->willReturn("Hello World!");

        $route = $this->getMockBuilder(Route::class)
            ->setMethods(["getController", "getAction"])
            ->getMock();

        $route->method("getController")
            ->willReturn("Jarenal\App\Steps");

        $route->method("getAction")
            ->willReturn("step1");

        $router = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->setMethods(["getRoute"])
            ->getMock();

        $router->method("getRoute")
            ->willReturn($route);

        $this->container->add("Jarenal\App\Steps", function () use ($controller) {
            return $controller;
        });

        $this->container->add("Jarenal\Core\Route", function () use ($route) {
            return $route;
        });

        $this->container->add("Jarenal\Core\Router", function () use ($router) {
            return $router;
        });
    }

    public function testRunSuccess()
    {
        $kernel = $this->container->get("Jarenal\Core\Kernel");
        $this->assertEquals("Hello World!", $kernel->run());
    }

    public function testRunFail()
    {
        $controller = $this->getMockBuilder(Steps::class)
            ->disableOriginalConstructor()
            ->setMethods(["step1"])
            ->getMock();

        $controller->method("step1")
            ->willThrowException(
                new Exception("Something went wrong...")
            );

        $this->container->add("Jarenal\App\Steps", function () use ($controller) {
            return $controller;
        });

        $kernel = $this->container->get("Jarenal\Core\Kernel");
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Something went wrong...");
        $kernel->run();
    }
}
