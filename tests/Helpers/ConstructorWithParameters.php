<?php

namespace JarenalTests\Helpers;

class ConstructorWithParameters
{
    public $price;
    public $category;

    public function __construct(int $price, string $category)
    {
        $this->price = $price;
        $this->category = $category;
    }
}