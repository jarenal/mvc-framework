<?php
declare(strict_types=1);

namespace Jarenal\App\Model;

use Jarenal\Core\ModelAbstract;
use Jarenal\Core\ModelInterface;

/**
 * Class Category
 * @package Jarenal\App\Model
 */
class Category extends ModelAbstract implements ModelInterface
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * @param mixed $id
     * @return Category
     */
    public function setId(int $id): self
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
     * @param mixed $name
     * @return Category
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     *
     */
    public function save(): void
    {
        // TODO: Implement save() method.
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)strtolower($this->name);
    }
}
