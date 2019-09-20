<?php

namespace Jarenal\App\Model;

use Jarenal\Core\DatabaseInterface;

class UserQueries
{
    private $database;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM `user` WHERE id=%s";
        $result = $this->database->executeQuery($sql, [$id]);
        $row = $result->fetch_object();

        if ($row) {
            $user = new User($this->database);
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
