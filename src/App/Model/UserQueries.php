<?php
declare(strict_types=1);

namespace Jarenal\App\Model;

use Exception;
use Jarenal\Core\ModelAbstract;

/**
 * Class UserQueries
 * @package Jarenal\App\Model
 */
class UserQueries extends ModelAbstract
{
    /**
     * @param int $id
     * @return User|null
     * @throws Exception
     */
    public function findById(int $id): ?User
    {
        try {
            $sql = "SELECT * FROM `user` WHERE id=%s";
            $result = $this->database->executeQuery($sql, [$id]);
            $row = $result->fetch_object();

            if ($row) {
                $user = $this->container->get("Jarenal\App\Model\User");
                $user->setId((int)$row->id)
                    ->setName($row->name)
                    ->setEmail($row->email)
                    ->setPhone($row->phone);
                return $user;
            } else {
                return null;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
