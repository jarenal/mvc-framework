<?php
require_once __DIR__."/../vendor/autoload.php";

define("PROJECT_ROOT_DIR", __DIR__."/../");

use Jarenal\Core\Config;
use Jarenal\Core\Kernel;
use Jarenal\Core\Router;

$config = new Config(__DIR__."/../config/config.yaml");
$router = new Router($config);
$kernel = new Kernel($router);
$kernel->run();
