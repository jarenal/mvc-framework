<?php
declare(strict_types=1);

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
