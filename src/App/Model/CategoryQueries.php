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
        $row = $result->fetch_object();

        if ($row) {
            $category = new Category($this->database);
            $category->setId($row->id)
                ->setName($row->name);
            return $category;
        } else {
            return null;
        }
    }
}
