<?php
declare(strict_types=1);

namespace JarenalTests\Core;

use Exception;
use Jarenal\Core\Config;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    private $fileSystem;

    public function setUp(): void
    {
        $structure = ["config" => ["config.yaml" => "foo: \"bar\""]];
        $this->fileSystem = vfsStream::setup("root", 644, $structure);
    }

    public function testConfigFileExist(): void
    {
        $config = new Config($this->fileSystem->getChild("config/config.yaml")->url());
        $this->assertInstanceOf(Config::class, $config);
    }

    public function testConfigFileDoesNotExist(): void
    {
        $this->expectException(Exception::class);
        new Config($this->fileSystem->url() . "/config/no_exist.yaml");
    }

    public function testExistingParameter(): void
    {
        $config = new Config($this->fileSystem->getChild("config/config.yaml")->url());
        $this->assertInstanceOf(Config::class, $config);
        $this->assertEquals("bar", $config->get("foo"));
    }

    public function testNotExistingParameter(): void
    {
        $config = new Config($this->fileSystem->getChild("config/config.yaml")->url());
        $this->assertInstanceOf(Config::class, $config);
        $this->assertEquals(null, $config->get("noExist"));
    }
}