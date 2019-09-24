<?php
declare(strict_types=1);

namespace Jarenal\App\Model;

use Exception;
use Jarenal\Core\ModelAbstract;

/**
 * Class QuoteQueries
 * @package Jarenal\App\Model
 */
class QuoteQueries extends ModelAbstract
{

    /**
     * @param int $id
     * @return Quote|null
     * @throws Exception
     */
    public function findById(int $id): ?Quote
    {
        try {
            $sql = "SELECT * FROM `quote` WHERE id=%s";
            $result = $this->database->executeQuery($sql, [$id]);
            return $this->buildQuoteFromResult($result);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param string $reference
     * @return Quote|null
     * @throws Exception
     */
    public function findByReference(string $reference): ?Quote
    {
        try {
            $sql = "SELECT * FROM `quote` WHERE reference='%s'";
            $result = $this->database->executeQuery($sql, [$reference]);
            return $this->buildQuoteFromResult($result);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param $result
     * @return Quote|null
     * @throws Exception
     */
    private function buildQuoteFromResult($result): ?Quote
    {
        $row = $result->fetch_object();

        if ($row) {
            $userQueries = $this->container->get("Jarenal\App\Model\UserQueries");
            $user = $userQueries->findById((int)$row->user_id);
            $quote = $this->container->get("Jarenal\App\Model\Quote");
            $quote->setId((int)$row->id)
                ->addUser($user)
                ->setReference($row->reference)
                ->setTotal((float)$row->total);
            return $quote;
        } else {
            return null;
        }
    }
}
