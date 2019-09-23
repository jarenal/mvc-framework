<?php
declare(strict_types=1);

namespace Jarenal\App\Model;

use Jarenal\Core\ModelAbstract;
use Jarenal\Core\ModelInterface;

/**
 * Class Product
 * @package Jarenal\App\Model
 */
class Product extends ModelAbstract implements ModelInterface
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var Category
     */
    public $category;
    /**
     * @var string
     */
    public $name;
    /**
     * @var float
     */
    public $price;
    /**
     * @var string
     */
    public $description;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Product
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Product
     */
    public function addCategory(Category $category): self
    {
        $this->category = $category;
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
     * @return Product
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Product
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Product
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
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
        return (string)$this->name;
    }
}
