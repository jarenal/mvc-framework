<?php

namespace Jarenal\Core;

class ModelAbstract
{
    protected $database;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }
}
