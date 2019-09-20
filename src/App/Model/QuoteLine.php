<?php

namespace Jarenal\App\Model;

use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Jarenal\Core\ModelAbstract;
use Jarenal\Core\ModelInterface;

class QuoteLine extends ModelAbstract implements ModelInterface
{
    public $id;
    public $quote;
    public $product;
    public $subtotal;
    public $quantity;
    public $metadata;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return QuoteLine
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * @param mixed $quote
     * @return QuoteLine
     */
    public function setQuote(Quote $quote)
    {
        $this->quote = $quote;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     * @return QuoteLine
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubtotal()
    {
        switch ($this->metadata["category_id"]) {
            case 1:
                $this->subtotal = $this->getSubtotalForSubscription();
                break;
            case 2:
                $this->subtotal = $this->getSubtotalForService();
                break;
            case 3:
                $this->subtotal = $this->getSubtotalForGoods();
                break;
            default:
                $this->subtotal = 0;
                break;
        }

        return $this->subtotal;
    }

    /**
     * @param mixed $subtotal
     * @return QuoteLine
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return QuoteLine
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param mixed $metadata
     * @return QuoteLine
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
        return $this;
    }

    public function save()
    {
        $this->database->connect();

        if ($this->id) {
            $sql = "UPDATE `quote_line` SET `quote_id`=%s, `product_id`=%s, `subtotal`=%s, `quantity`=%s, `metadata`=%s WHERE `id`=%s";
            $this->database->executeQuery($sql, [
                $this->getQuote()->getId(),
                $this->getProduct()->getId(),
                $this->getSubtotal(),
                $this->quantity,
                json_encode($this->metadata),
                $this->id
            ]);
        } else {
            $sql = "INSERT INTO `quote_line` (`quote_id`, `product_id`, `subtotal`, `quantity`, `metadata`) VALUES (\"%s\", \"%s\", \"%s\", \"%s\", \"%s\")";
            $this->database->executeQuery($sql, [
                $this->getQuote()->getId(),
                $this->getProduct()->getId(),
                $this->getSubtotal(),
                $this->quantity,
                json_encode($this->metadata)
            ]);
            $this->id = $this->database->getLastId();
        }
    }

    private function getSubtotalForSubscription()
    {
        try {
            $endDate = new DateTime($this->formateDate($this->metadata["end_date"]));
            $startDate = new DateTime($this->formateDate($this->metadata["start_date"]));
            $totalDays = $endDate->diff($startDate)->days;
            $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
            foreach ($period as $day) {
                $current = $day->format('N');
                if ($current == 6 || $current == 7) {
                    $totalDays--;
                }
            }
            return (float)$totalDays * (float)$this->metadata["price"];
        } catch (Exception $e) {
            return 0;
        }
    }

    private function getSubtotalForService()
    {
        return (((float)$this->metadata["end_time"] - (float)$this->metadata["start_time"]) * (float)$this->metadata["weeks"]) * (float)$this->metadata["price"];
    }

    private function getSubtotalForGoods()
    {
        return (float)$this->quantity * (float)$this->metadata["price"];
    }

    private function formateDate($date)
    {
        $dateArray = explode("/", $date);
        return $dateArray[2] . "-" . $dateArray[1] . "-" . $dateArray[0];
    }
}
