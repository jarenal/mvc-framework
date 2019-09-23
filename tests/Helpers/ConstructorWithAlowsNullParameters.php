<?php

namespace JarenalTests\Helpers;

use Jarenal\Core\Route;
use Jarenal\Core\View;

class ConstructorWithAlowsNullParameters
{
    public $route = true;
    public $view = true;

    public function __construct(Route $route = null, View $view = null)
    {
        $this->route = $route;
        $this->view = $view;
    }
}