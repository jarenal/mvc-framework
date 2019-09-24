<?php
session_start();

require_once __DIR__."/../vendor/autoload.php";

use Jarenal\Core\Config;
use Jarenal\Core\Container;

define("PROJECT_ROOT_DIR", __DIR__."/../");

$container = new Container();
$container->add("Jarenal\Core\Config", function () {
    return new Config(__DIR__."/../config/config.yaml");
});
$container->add("mysqli", function () {
    return new mysqli();
});

$kernel = $container->get("Jarenal\Core\Kernel");

die($kernel->run());
