<?php

namespace Jarenal\App\Model;

use Jarenal\Core\DatabaseInterface;

class ProductQueries
{
    private $database;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function findAll()
    {
        $sql = "SELECT * FROM `product`";
        $result = $this->database->executeQuery($sql);
        $products = [];
        while ($row = $result->fetch_object()) {
            $categoryQueries = new CategoryQueries($this->database);
            $category = $categoryQueries->findById($row->id);
            $currentProduct = new Product($this->database);
            $currentProduct->setId($row->id)
                ->setName($row->name)
                ->setCategory($category)
                ->setPrice($row->price);
            $products[] = $currentProduct;
        }
        return $products;
    }
}
