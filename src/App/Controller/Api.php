<?php
declare(strict_types=1);

namespace Jarenal\App\Controller;

use Exception;
use Jarenal\Core\ControllerAbstract;

/**
 * Class Api
 * @package Jarenal\App\Controller
 */
class Api extends ControllerAbstract
{
    /**
     * @return string
     */
    public function products(): string
    {
        header("Content-type: application/json");

        try {
            if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                throw new Exception("Request method is not POST", 400);
            }

            if (!isset($_POST["id"]) || !isset($_POST["quantity"])) {
                throw new Exception("Product is required", 400);
            }

            $_POST["metadata"] = isset($_POST["metadata"]) ? $_POST["metadata"] : [];

            $productQueries = $this->container->get("Jarenal\App\Model\ProductQueries");
            $product = $productQueries->findById((int)$_POST["id"]);

            $cart = $this->session->get("cart", []);
            $item = [];
            $item["id"] = $_POST["id"];
            $item["quantity"] = $_POST["quantity"];
            $item["metadata"] = array_merge(
                $_POST["metadata"],
                [
                    "price" => $product->getPrice(),
                    "category_id" => $product->getCategory()->getId(),
                    "category" => $product->getCategory()->getName()
                ]
            );
            $cart[] = $item;
            $this->session->set("cart", $cart);
            $response = ["cart_counter" => count($cart)];
            http_response_code(200);
        } catch (Exception $ex) {
            $response = ["code" => $ex->getCode(), "message" => $ex->getMessage()];

            switch ($ex->getCode()) {
                case 400:
                    $responseCode = 400;
                    break;
                default:
                    $responseCode = 500;
                    break;
            }
            http_response_code($responseCode);
        }

        return json_encode($response);
    }
}
