<?php

namespace Jarenal\App\Model;

use Jarenal\Core\DatabaseInterface;

class QuoteQueries
{
    private $database;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM `quote` WHERE id=%s";
        $result = $this->database->executeQuery($sql, [$id]);
        $row = $result->fetch_object();

        if ($row) {
            $userQueries = new UserQueries($this->database);
            $user = $userQueries->findById($row->user_id);
            $quote = new Quote($this->database);
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
