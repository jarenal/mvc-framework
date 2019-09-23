<?php
declare(strict_types=1);

namespace Jarenal\App\Model;

use Exception;
use Jarenal\Core\ModelAbstract;

/**
 * Class QuoteLineQueries
 * @package Jarenal\App\Model
 */
class QuoteLineQueries extends ModelAbstract
{
    /**
     * @param int $quote_id
     * @return array
     * @throws Exception
     */
    public function findByQuoteId(int $quote_id): array
    {
        try {
            $sql = "SELECT * FROM `quote_line` WHERE quote_id=%s";
            $result = $this->database->executeQuery($sql, [$quote_id]);
            $quoteQueries = $this->container->get("Jarenal\App\Model\QuoteQueries");
            $productQueries = $this->container->get("Jarenal\App\Model\ProductQueries");
            $lines = [];
            while ($row = $result->fetch_object()) {
                $quote = $quoteQueries->findById((int)$row->quote_id);
                $product = $productQueries->findById((int)$row->product_id);
                $line = $this->container->get("Jarenal\App\Model\QuoteLine");
                $line->setId((int)$row->id)
                    ->setQuote($quote)
                    ->setProduct($product)
                    ->setSubtotal((float)$row->subtotal)
                    ->setQuantity((int)$row->quantity)
                    ->setMetadata(json_decode($row->metadata, true));
                $lines[] = $line;
            }
            return $lines;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
