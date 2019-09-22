<?php

namespace Jarenal\App\Model;

use Jarenal\Core\ModelAbstract;

class ProductQueries extends ModelAbstract
{
    public function findAll()
    {
        $sql = "SELECT * FROM `product`";
        $result = $this->database->executeQuery($sql);
        $products = [];
        while ($row = $result->fetch_object()) {
            $categoryQueries = $this->container->get("Jarenal\App\Model\CategoryQueries");
            $category = $categoryQueries->findById($row->category_id);
            $currentProduct = $this->container->get("Jarenal\App\Model\Product");
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
            $categoryQueries = $this->container->get("Jarenal\App\Model\CategoryQueries");
            $category = $categoryQueries->findById($row->category_id);
            $product = $this->container->get("Jarenal\App\Model\Product");
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
