<?php

namespace Jarenal\App\Model;

use Jarenal\Core\ModelAbstract;
use Jarenal\Core\ModelInterface;

class Category extends ModelAbstract implements ModelInterface
{
    private $id;
    private $name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Category
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
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function save()
    {
        // TODO: Implement save() method.
    }

    public function __toString()
    {
        return (string)strtolower($this->name);
    }
}