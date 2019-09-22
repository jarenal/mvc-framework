<?php

namespace Jarenal\App\Model;

use Jarenal\Core\ModelAbstract;

class CategoryQueries extends ModelAbstract
{
    public function findById($id)
    {
        $sql = "SELECT * FROM `category` WHERE id=%s";
        $result = $this->database->executeQuery($sql, [$id]);
        $row = $result->fetch_object();

        if ($row) {
            $category = $this->container->get("Jarenal\App\Model\Category");
            $category->setId($row->id)
                ->setName($row->name);
            return $category;
        } else {
            return null;
        }
    }
}
