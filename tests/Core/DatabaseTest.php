<?php
declare(strict_types=1);

namespace JarenalTests\Core;

use Exception;
use Jarenal\Core\Config;
use Jarenal\Core\Container;
use Jarenal\Core\MysqliWrapper;
use JarenalTests\Helpers\MysqliMock;
use mysqli;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private $container;

    public function setUp(): void
    {
        $this->container = new Container();

        $config = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $config->method("get")
            ->willReturn([
                "host" => "localhost",
                "username" => "test",
                "password" => "1234",
                "name" => "dbname",
                "port" => 3306
            ]);

        $this->container->add("Jarenal\Core\Config", function () use ($config) {
            return $config;
        });

        $mysqli = $this->getMockBuilder(MysqliWrapper::class)
            ->setMethods(["real_connect", "query", "real_escape_string"])
            ->getMock();

        $mysqli->method("real_connect")
            ->willReturn(true);

        $mysqli->method("query")
            ->willReturn(true);

        $this->container->add("Jarenal\Core\MysqliWrapper", function () use ($mysqli) {
            return $mysqli;
        });
    }

    public function testConnectSuccess()
    {
        $config = $this->container->get("Jarenal\Core\Config");
        $config->expects($this->once())
            ->method("get");

        $mysqli = $this->container->get("Jarenal\Core\MysqliWrapper");
        $mysqli->expects($this->once())
            ->method("real_connect")
            ->with("localhost", "test", "1234", "dbname", 3306);

        $database = $this->container->get("Jarenal\Core\Database");
        $connected = $database->connect();
        $this->assertEquals(true, $connected);
        $this->assertEquals(true, $database->isConnected());
    }

    public function testConnectFailed()
    {
        $mysqli = $this->getMockBuilder(MysqliWrapper::class)
            ->setMethods(["real_connect"])
            ->getMock();

        $mysqli->method("real_connect")
            ->willReturn(false);

        $this->container->add("Jarenal\Core\MysqliWrapper", function () use ($mysqli) {
            return $mysqli;
        });

        // Asserts
        $mysqli->expects($this->once())
            ->method("real_connect");

        $mysqli->connect_errno = true;
        $mysqli->connect_error = "Some error";

        $database = $this->container->get("Jarenal\Core\Database");
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Connection failure");
        $database->connect();
    }

    public function testExecuteQueryWhenDBIsNotConnected()
    {
        $mysqli = $this->container->get("Jarenal\Core\MysqliWrapper");
        $mysqli->expects($this->once())
            ->method("real_connect");
        $mysqli->expects($this->once())
            ->method("query")
            ->with("SELECT * FROM `someTable` WHERE 1");

        $database = $this->container->get("Jarenal\Core\Database");
        $this->assertEquals(true, $database->executeQuery("SELECT * FROM `someTable` WHERE 1"));
    }

    public function testExecuteQueryWhenDBIsConnected()
    {
        $mysqli = $this->container->get("Jarenal\Core\MysqliWrapper");
        $mysqli->expects($this->once())
            ->method("real_connect");
        $mysqli->expects($this->once())
            ->method("query")
            ->with("SELECT * FROM `someTable` WHERE 1");

        $database = $this->container->get("Jarenal\Core\Database");
        $database->connect();
        $this->assertEquals(true, $database->executeQuery("SELECT * FROM `someTable` WHERE 1"));
    }

    public function testExecuteQueryWithParams()
    {
        $mysqli = $this->container->get("Jarenal\Core\MysqliWrapper");
        $mysqli->expects($this->once())
            ->method("real_connect");
        $mysqli->expects($this->once())
            ->method("query")
            ->with("SELECT * FROM `someTable` WHERE a=1 AND b=2 AND c=3");
        $mysqli->expects($this->exactly(3))
            ->method("real_escape_string")
            ->withConsecutive(
                [$this->equalTo("1")],
                [$this->equalTo("2")],
                [$this->equalTo("3")]
            )
            ->willReturnOnConsecutiveCalls(
                $this->returnArgument(0),
                $this->returnArgument(0),
                $this->returnArgument(0)
            );

        $database = $this->container->get("Jarenal\Core\Database");
        $this->assertEquals(true, $database->executeQuery("SELECT * FROM `someTable` WHERE a=%s AND b=%s AND c=%s", [1, 2, 3]));
    }

    public function testExecuteQueryWithoutParams()
    {
        $mysqli = $this->container->get("Jarenal\Core\MysqliWrapper");
        $mysqli->expects($this->once())
            ->method("real_connect");
        $mysqli->expects($this->once())
            ->method("query")
            ->with("SELECT * FROM `someTable` WHERE 1");
        $mysqli->expects($this->never())
            ->method("real_escape_string");

        $database = $this->container->get("Jarenal\Core\Database");
        $this->assertEquals(true, $database->executeQuery("SELECT * FROM `someTable` WHERE 1", []));
    }

    public function testExecuteQueryFailed()
    {
        $mysqli = $this->getMockBuilder(MysqliWrapper::class)
            ->setMethods(["real_connect", "query", "real_escape_string"])
            ->getMock();

        $mysqli->method("real_connect")
            ->willReturn(true);

        $mysqli->method("query")
            ->willReturn(false);

        $this->container->add("Jarenal\Core\MysqliWrapper", function () use ($mysqli) {
            return $mysqli;
        });

        $mysqli->expects($this->once())
            ->method("real_connect");
        $mysqli->expects($this->once())
            ->method("query")
            ->with("SELECT * FROM `someTable` WHERE 1");
        $mysqli->expects($this->never())
            ->method("real_escape_string");

        $mysqli->error = "Query error";
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Query error");
        $database = $this->container->get("Jarenal\Core\Database");
        $database->executeQuery("SELECT * FROM `someTable` WHERE 1", []);
    }
}