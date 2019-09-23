<?php
declare(strict_types=1);

namespace Jarenal\App\Model;

use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Jarenal\Core\ModelAbstract;
use Jarenal\Core\ModelInterface;

/**
 * Class QuoteLine
 * @package Jarenal\App\Model
 */
class QuoteLine extends ModelAbstract implements ModelInterface
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var Quote
     */
    public $quote;
    /**
     * @var Product
     */
    public $product;
    /**
     * @var float
     */
    public $subtotal;
    /**
     * @var int
     */
    public $quantity;
    /**
     * @var array
     */
    public $metadata;

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
     * @return Quote
     */
    public function getQuote(): Quote
    {
        return $this->quote;
    }

    /**
     * @param Quote $quote
     * @return $this
     */
    public function setQuote(Quote $quote): self
    {
        $this->quote = $quote;
        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return float
     */
    public function getSubtotal(): float
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
     * @param float $subtotal
     * @return $this
     */
    public function setSubtotal(float $subtotal)
    {
        $this->subtotal = $subtotal;
        return $this;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     * @return $this
     */
    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     * @return $this
     */
    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;
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
                $sql = "UPDATE `quote_line` SET `quote_id`=%s, `product_id`=%s, `quantity`=%s, `subtotal`=%s, `metadata`=%s WHERE `id`=%s";
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
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @return float
     */
    private function getSubtotalForSubscription(): float
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

    /**
     * @return float
     */
    private function getSubtotalForService(): float
    {
        return (((float)$this->metadata["end_time"] - (float)$this->metadata["start_time"]) * (float)$this->metadata["weeks"]) * (float)$this->metadata["price"];
    }

    /**
     * @return float
     */
    private function getSubtotalForGoods(): float
    {
        return (float)$this->quantity * (float)$this->metadata["price"];
    }

    /**
     * @param $date
     * @return string
     */
    private function formateDate($date): string
    {
        $dateArray = explode("/", $date);
        return $dateArray[2] . "-" . $dateArray[1] . "-" . $dateArray[0];
    }
}
