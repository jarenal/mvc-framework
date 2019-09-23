<?php

namespace JarenalTests\Helpers;

use Jarenal\Core\Route;
use Jarenal\Core\View;

class ConstructorWithMixParameters
{
    public $price;
    public $view;
    public $category;
    public $route;

    public function __construct(int $price, View $view, string $category = "Service", Route $route = null)
    {
        $this->price = $price;
        $this->view = $view;
        $this->category = $category;
        $this->route = $route;
    }
}