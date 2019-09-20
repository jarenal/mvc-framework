<?php

namespace Jarenal\App\Model;

use Exception;
use Jarenal\Core\ModelAbstract;
use Jarenal\Core\ModelInterface;

class User extends ModelAbstract implements ModelInterface
{
    public $id;
    public $name;
    public $password;
    public $email;
    public $phone;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function save()
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
