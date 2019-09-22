<?php

namespace Jarenal\App\Model;

use Jarenal\Core\ModelAbstract;

class QuoteLineQueries extends ModelAbstract
{
    public function findByQuoteId($quote_id)
    {
        $sql = "SELECT * FROM `quote_line` WHERE quote_id=%s";
        $result = $this->database->executeQuery($sql, [$quote_id]);
        $quoteQueries = $this->container->get("Jarenal\App\Model\QuoteQueries");
        $productQueries = $this->container->get("Jarenal\App\Model\ProductQueries");
        $lines = [];
        while ($row = $result->fetch_object()) {
            $quote = $quoteQueries->findById($row->quote_id);
            $product = $productQueries->findById($row->product_id);
            $line = $this->container->get("Jarenal\App\Model\QuoteLine");
            $line->setId($row->id)
                ->setQuote($quote)
                ->setProduct($product)
                ->setSubtotal($row->subtotal)
                ->setQuantity($row->quantity)
                ->setMetadata(json_decode($row->metadata, true));
            $lines[] = $line;
        }
        return $lines;
    }
}
