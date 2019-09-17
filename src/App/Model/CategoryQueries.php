<?php

namespace Jarenal\App\Model;

use Jarenal\Core\DatabaseInterface;

class CategoryQueries
{
    private $database;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM `category` WHERE id=%s";
        $result = $this->database->executeQuery($sql, [$id]);
        $obj = $result->fetch_object();
        $category = new Category($this->database);
        $category->setId($obj->id)
            ->setName($obj->name);
        return $category;
    }
}
