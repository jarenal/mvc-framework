<?php
declare(strict_types=1);

namespace Jarenal\App\Model;

use Exception;
use Jarenal\Core\ModelAbstract;
use Jarenal\Core\ModelInterface;

/**
 * Class User
 * @package Jarenal\App\Model
 */
class User extends ModelAbstract implements ModelInterface
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $password;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $phone;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function save(): void
    {
        try {
            $this->database->connect();

            if ($this->id) {
                $sql = "UPDATE `user` SET `name`=%s, `password`=%s, `email`=%s, `phone`=%s WHERE `id`=%s";
                $this->database->executeQuery(
                    $sql,
                    [$this->name, $this->password, $this->email, $this->phone, $this->id]
                );
            } else {
                $sql = "INSERT INTO `user` (`name`, `password`, `email`, `phone`) VALUES (\"%s\", \"%s\", \"%s\", \"%s\")";
                $this->database->executeQuery($sql, [$this->name, $this->password, $this->email, $this->phone]);
                $this->id = $this->database->getLastId();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
