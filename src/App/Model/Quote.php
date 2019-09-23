<?php
declare(strict_types=1);

namespace Jarenal\App\Model;

use Exception;
use Jarenal\Core\ModelAbstract;
use Jarenal\Core\ModelInterface;

/**
 * Class Quote
 * @package Jarenal\App\Model
 */
class Quote extends ModelAbstract implements ModelInterface
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var User
     */
    public $user;
    /**
     * @var string
     */
    public $reference;
    /**
     * @var float
     */
    public $total;
    /**
     * @var array
     */
    public $lines = [];

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
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     * @return $this
     */
    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        $this->total = 0;
        if (is_array($this->lines) && $this->lines) {
            foreach ($this->lines as $line) {
                $this->total += $line->getSubtotal();
            }
        }
        return $this->total;
    }

    /**
     * @param float $total
     * @return $this
     */
    public function setTotal(float $total): self
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @param QuoteLine $line
     */
    public function addLine(QuoteLine $line): void
    {
        if ($this->id) {
            $line->addQuote($this);
        }
        $this->lines[] = $line;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getLines(): array
    {
        try {
            if (!$this->lines && $this->id) {
                $linesQueries = $this->container->get("Jarenal\App\Model\QuoteLineQueries");
                $this->lines = $linesQueries->findByQuoteId($this->id);
            }

            return $this->lines;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @throws Exception
     */
    public function save(): void
    {
        try {
            $this->database->connect();

            if ($this->id) {
                $sql = "UPDATE `quote` SET `user_id`=%s, `reference`=%s, `total`=%s WHERE `id`=%s";
                $this->database->executeQuery(
                    $sql,
                    [$this->getUser()->getId(), $this->reference, $this->getTotal(), $this->id]
                );
            } else {
                $sql = "INSERT INTO `quote` (`user_id`, `reference`, `total`) VALUES (\"%s\", \"%s\", \"%s\")";
                $this->database->executeQuery($sql, [$this->getUser()->getId(), $this->reference, $this->getTotal()]);
                $this->id = $this->database->getLastId();
            }

            if (is_array($this->lines) && $this->lines) {
                foreach ($this->lines as $line) {
                    $line->addQuote($this);
                    $line->save();
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->reference;
    }
}
