<?php
declare(strict_types=1);

namespace Jarenal\App\Model;

use Exception;
use Jarenal\Core\ModelAbstract;

/**
 * Class ProductQueries
 * @package Jarenal\App\Model
 */
class ProductQueries extends ModelAbstract
{
    /**
     * @return array
     * @throws Exception
     */
    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM `product`";
            $result = $this->database->executeQuery($sql);
            $products = [];
            while ($row = $result->fetch_object()) {
                $categoryQueries = $this->container->get("Jarenal\App\Model\CategoryQueries");
                $category = $categoryQueries->findById((int)$row->category_id);
                $currentProduct = $this->container->get("Jarenal\App\Model\Product");
                $currentProduct->setId((int)$row->id)
                    ->setName($row->name)
                    ->setCategory($category)
                    ->setPrice((float)$row->price)
                    ->setDescription($row->description);
                $products[] = $currentProduct;
            }
            return $products;
        } catch (Exception $ex) {
        }
    }

    /**
     * @param int $id
     * @return Product|null
     * @throws Exception
     */
    public function findById(int $id): ?Product
    {
        try {
            $sql = "SELECT * FROM `product` WHERE `id`=%s";
            $result = $this->database->executeQuery($sql, [$id]);
            $row = $result->fetch_object();

            if ($row) {
                $categoryQueries = $this->container->get("Jarenal\App\Model\CategoryQueries");
                $category = $categoryQueries->findById((int)$row->category_id);
                $product = $this->container->get("Jarenal\App\Model\Product");
                $product->setId((int)$row->id)
                    ->setName($row->name)
                    ->setCategory($category)
                    ->setPrice((float)$row->price)
                    ->setDescription($row->description);
                return $product;
            } else {
                return null;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
