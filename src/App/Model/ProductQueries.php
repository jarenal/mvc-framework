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
            $category = $categoryQueries->findById($row->category_id);
            $currentProduct = new Product($this->database);
            $currentProduct->setId($row->id)
                ->setName($row->name)
                ->setCategory($category)
                ->setPrice($row->price)
                ->setDescription($row->description);
            $products[] = $currentProduct;
        }
        return $products;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM `product` WHERE `id`=%s";
        $result = $this->database->executeQuery($sql, [$id]);
        $row = $result->fetch_object();

        if ($row) {
            $categoryQueries = new CategoryQueries($this->database);
            $category = $categoryQueries->findById($row->category_id);
            $product = new Product($this->database);
            $product->setId($row->id)
                ->setName($row->name)
                ->setCategory($category)
                ->setPrice($row->price)
                ->setDescription($row->description);
            return $product;
        } else {
            return null;
        }
    }
}
