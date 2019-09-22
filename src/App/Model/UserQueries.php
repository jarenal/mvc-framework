<?php

namespace Jarenal\App\Model;

use Jarenal\Core\ModelAbstract;

class UserQueries extends ModelAbstract
{
    public function findById($id)
    {
        $sql = "SELECT * FROM `user` WHERE id=%s";
        $result = $this->database->executeQuery($sql, [$id]);
        $row = $result->fetch_object();

        if ($row) {
            $user = $this->container->get("Jarenal\App\Model\User");
            $user->setId($row->id)
                ->setName($row->name)
                ->setEmail($row->email)
                ->setPhone($row->phone);
            return $user;
        } else {
            return null;
        }
    }
}
