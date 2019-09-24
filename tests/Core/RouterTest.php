<?php

namespace JarenalTests\Core;

use Jarenal\Core\Config;
use Jarenal\Core\Container;
use Jarenal\Core\Route;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private $container;

    public function setUp(): void
    {
        $this->container = new Container();

        $config = $this->getMockBuilder(Config::class)
            ->setMethods(["get"])
            ->disableOriginalConstructor()
            ->getMock();

        $routes = [
            "homepage" => [
                "controller" => "Jarenal\App\Controller\Homepage",
                "action" => "index",
                "method" => ["GET"],
                "pattern" => "/homepage"
            ]
        ];

        $config->method("get")
            ->willReturn($routes);

        $this->container->add("Jarenal\Core\Config", function () use ($config) {
            return $config;
        });
    }

    public function testGetRouteSuccess()
    {
        $router = $this->container->get("Jarenal\Core\Router");
        $_SERVER["REQUEST_URI"] = "/homepage";
        $_SERVER["REQUEST_METHOD"] = "GET";
        $route = $router->getRoute();
        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals("Jarenal\App\Controller\Homepage", $route->getController());
        $this->assertEquals("index", $route->getAction());
    }

    public function testGetRouteNotFound()
    {
        $router = $this->container->get("Jarenal\Core\Router");
        $_SERVER["REQUEST_URI"] = "/homepage";
        $_SERVER["REQUEST_METHOD"] = "POST";
        $route = $router->getRoute();
        $this->assertEquals(null, $route);
    }

    public function testGetRouteWithoutRoutesFromConfig()
    {
        $config = $this->getMockBuilder(Config::class)
            ->setMethods(["get"])
            ->disableOriginalConstructor()
            ->getMock();

        $routes = [];

        $config->method("get")
            ->willReturn($routes);

        $this->container->add("Jarenal\Core\Config", function () use ($config) {
            return $config;
        });

        $route = $this->getMockBuilder(Route::class)
            ->setMethods(["setController", "setAction"])
            ->getMock();

        $this->container->add("Jarenal\Core\Route", function () use ($route) {
            return $route;
        });

        $router = $this->container->get("Jarenal\Core\Router");

        $route->expects($this->never())
            ->method("setController");

        $route->expects($this->never())
            ->method("setAction");

        $this->assertEquals(null, $router->getRoute());
    }
}
