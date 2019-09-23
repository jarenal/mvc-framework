<?php
declare(strict_types=1);

namespace Jarenal\App\Model;

use Exception;
use Jarenal\Core\ModelAbstract;

/**
 * Class CategoryQueries
 * @package Jarenal\App\Model
 */
class CategoryQueries extends ModelAbstract
{
    /**
     * @param int $id
     * @return Category|null
     * @throws Exception
     */
    public function findById(int $id): ?Category
    {
        try {
            $sql = "SELECT * FROM `category` WHERE id=%s";
            $result = $this->database->executeQuery($sql, [$id]);
            $row = $result->fetch_object();

            if ($row) {
                $category = $this->container->get("Jarenal\App\Model\Category");
                $category->setId((int)$row->id)
                    ->setName($row->name);
                return $category;
            } else {
                return null;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
