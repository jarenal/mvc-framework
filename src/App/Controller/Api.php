<?php

namespace Jarenal\App\Controller;

use Exception;
use Jarenal\Core\ControllerAbstract;

class Api extends ControllerAbstract
{
    public function products()
    {
        header("Content-type: application/json");

        try {
            if (!isset($_POST["product"]) || !is_array($_POST["product"]) || !$_POST["product"]) {
                throw new Exception("Product is required", 400);
            }
            $cart = $this->session->get("cart", []);
            $cart[] = $_POST["product"];
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
