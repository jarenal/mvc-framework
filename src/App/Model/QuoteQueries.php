<?php

namespace Jarenal\App\Model;

use Jarenal\Core\ModelAbstract;

class QuoteQueries extends ModelAbstract
{
    public function findById($id)
    {
        $sql = "SELECT * FROM `quote` WHERE id=%s";
        $result = $this->database->executeQuery($sql, [$id]);
        $row = $result->fetch_object();

        if ($row) {
            $userQueries = $this->container->get("Jarenal\App\Model\UserQueries");
            $user = $userQueries->findById($row->user_id);
            $quote = $this->container->get("Jarenal\App\Model\Quote");
            $quote->setId($row->id)
                ->setUser($user)
                ->setReference($row->reference)
                ->setTotal($row->total);
            return $quote;
        } else {
            return null;
        }
    }
}
