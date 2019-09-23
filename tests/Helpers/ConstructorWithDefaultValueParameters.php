<?php

namespace JarenalTests\Helpers;

class ConstructorWithDefaultValueParameters
{
    public $price;
    public $category;

    public function __construct($price = 10, $category = "Goods")
    {
        $this->price = $price;
        $this->category = $category;
    }
}