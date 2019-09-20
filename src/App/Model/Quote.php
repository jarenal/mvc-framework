<?php

namespace Jarenal\App\Model;

use Jarenal\Core\ModelAbstract;
use Jarenal\Core\ModelInterface;

class Quote extends ModelAbstract implements ModelInterface
{
    public $id;
    public $user;
    public $reference;
    public $total;
    public $lines = [];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Quote
     */
    public function setId($id)
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
     * @param mixed $user
     * @return Quote
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     * @return Quote
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotal()
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
     * @param mixed $total
     * @return Quote
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    public function addLine(QuoteLine $line)
    {
        if ($this->id) {
            $line->setQuote($this);
        }
        $this->lines[] = $line;
    }

    public function getLines()
    {
        if (!$this->lines && $this->id) {
            $linesQueries = new QuoteLineQueries($this->database);
            $this->lines = $linesQueries->findByQuoteId($this->id);
        }

        return $this->lines;
    }

    public function save()
    {
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
                $line->setQuote($this);
                $line->save();
            }
        }
    }

    public function __toString()
    {
        return (string)$this->reference;
    }
}
