<?php

namespace Jarenal\App\Model;

use Jarenal\Core\DatabaseInterface;

class QuoteLineQueries
{
    private $database;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function findByQuoteId($quote_id)
    {
        $sql = "SELECT * FROM `quote_line` WHERE quote_id=%s";
        $result = $this->database->executeQuery($sql, [$quote_id]);
        $quoteQueries = new QuoteQueries($this->database);
        $productQueries = new ProductQueries($this->database);
        $lines = [];
        while ($row = $result->fetch_object()) {
            $quote = $quoteQueries->findById($row->quote_id);
            $product = $productQueries->findById($row->product_id);
            $line = new QuoteLine($this->database);
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
