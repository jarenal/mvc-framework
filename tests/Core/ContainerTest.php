<?php
declare(strict_types=1);

namespace JarenalTests\Core;

use Exception;
use Jarenal\Core\Config;
use Jarenal\Core\Container;
use Jarenal\Core\Route;
use Jarenal\Core\View;
use JarenalTests\Helpers\ConstructorWithAlowsNullParameters;
use JarenalTests\Helpers\ConstructorWithDefaultValueParameters;
use JarenalTests\Helpers\ConstructorWithOutParameters;
use JarenalTests\Helpers\WithOutConstructor;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use stdClass;

class ContainerTest extends TestCase
{
    public function testConstructor(): void
    {
        $container = new Container();
        $factories = $container->getFactories();
        $this->assertInstanceOf(\Closure::class, $factories["Jarenal\Core\Container"]);
    }

    public function testGetFactoriesReturnsArray(): void
    {
        $container = new Container();
        $factories = $container->getFactories();
        $this->assertIsArray($factories);
        $this->assertCount(1, $factories);
    }

    public function testAddIncreaseFactoriesArray(): void
    {
        $container = new Container();
        $container->add(Config::class);
        $factories = $container->getFactories();
        $this->assertIsArray($factories);
        $this->assertCount(2, $factories);
    }

    public function testAddReceivesClosure(): void
    {
        $container = new Container();
        $container->add(Config::class, function () {
            return new stdClass();
        });
        $factories = $container->getFactories();
        $this->assertInstanceOf(\Closure::class, $factories["Jarenal\Core\Config"]);
    }

    public function testAddWithoutClosure(): void
    {
        $container = new Container();
        $container->add(Config::class);
        $factories = $container->getFactories();
        $this->assertEquals("Jarenal\Core\Config", $factories["Jarenal\Core\Config"]);
    }

    public function testGetInstanceFromClosureWithoutParameters(): void
    {
        $container = new Container();
        $container->add("Jarenal\Core\Foo", function () {
            return new stdClass();
        });
        $instance = $container->get("Jarenal\Core\Foo");
        $this->assertInstanceOf(stdClass::class, $instance);
    }

    public function testGetInstanceFromClosureWithParameters(): void
    {
        $container = new Container();
        $container->add("Jarenal\Core\Foo", function ($price, $category) {
            $obj = new stdClass();
            $obj->price = $price;
            $obj->category = $category;
            return $obj;
        });
        $instance = $container->get("Jarenal\Core\Foo", ["price" => 10, "category" => "goods"]);
        $this->assertInstanceOf(stdClass::class, $instance);
        $this->assertEquals($instance->price, 10);
        $this->assertEquals($instance->category, "goods");
    }

    public function testGetAddClassToFactoryAutomatically(): void
    {
        $container = new Container();
        $factories = $container->getFactories();
        $this->assertCount(1, $factories);
        $container->get("Jarenal\Core\Route");
        $factories = $container->getFactories();
        $this->assertCount(2, $factories);
    }

    public function testGetWhenClassDoesNotExist(): void
    {
        $container = new Container();
        $this->expectException(ReflectionException::class);
        $this->expectExceptionMessage("Class Jarenal\Core\Foo does not exist");
        $container->get("Jarenal\Core\Foo");
    }

    public function testBuildClassIsNotInstantiable(): void
    {
        $container = new Container();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Class JarenalTests\Helpers\FooBar is not instantiable");
        $container->get("JarenalTests\Helpers\FooBar");
    }

    public function testBuildClassWithoutConstructor(): void
    {
        $container = new Container();
        $instance = $container->get("JarenalTests\Helpers\WithOutConstructor");
        $this->assertInstanceOf(WithOutConstructor::class, $instance);
    }

    public function testBuildConstructorWithOutParameters(): void
    {
        $container = new Container();
        $instance = $container->get("JarenalTests\Helpers\ConstructorWithOutParameters");
        $this->assertInstanceOf(ConstructorWithOutParameters::class, $instance);
    }

    public function testBuildConstructorWithDefaultValueParameters(): void
    {
        $container = new Container();
        $instance = $container->get("JarenalTests\Helpers\ConstructorWithDefaultValueParameters");
        $this->assertInstanceOf(ConstructorWithDefaultValueParameters::class, $instance);
        $this->assertEquals($instance->price, 10);
        $this->assertEquals($instance->category, "Goods");
    }

    public function testBuildConstructorWithAlowsNullParameters(): void
    {
        $container = new Container();
        $instance = $container->get("JarenalTests\Helpers\ConstructorWithAlowsNullParameters", [null, null]);
        $this->assertInstanceOf(ConstructorWithAlowsNullParameters::class, $instance);
        $this->assertEquals(null, $instance->route);
        $this->assertEquals(null, $instance->view);
    }

    public function testBuildConstructorCantResolveDependencies(): void
    {
        $container = new Container();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Can't resolve class dependency price");
        $container->get("JarenalTests\Helpers\ConstructorWithParameters");
    }

    public function testBuildSuccessWithMixParameters(): void
    {
        $container = new Container();
        $instance = $container->get("JarenalTests\Helpers\ConstructorWithMixParameters", [0 => 100]);
        $this->assertEquals(100, $instance->price);
        $this->assertInstanceOf(View::class, $instance->view);
        $this->assertEquals("Service", $instance->category);
        $this->assertInstanceOf(Route::class, $instance->route);
    }
}