<?php

namespace Jarenal\Core;

class ModelAbstract
{
    protected $container;
    protected $database;

    public function __construct(Container $container, Database $database)
    {
        $this->container = $container;
        $this->database = $database;
    }
}
