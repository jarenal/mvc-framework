<?php

namespace Jarenal\App\Controller;

use Exception;
use Jarenal\Core\ControllerAbstract;

/**
 * Class Steps
 * @package Jarenal\App\Controller
 */
class Steps extends ControllerAbstract
{
    /**
     * @return string
     */
    public function step1(): string
    {
        try {
            return $this->view->render("steps/step1.tpl", ["title" => "Step #1"]);
        } catch (Exception $ex) {
            return $this->view->render("error/message.tpl", ["message" => $ex->getMessage()]);
        }
    }

    /**
     * @return string
     */
    public function step2(): string
    {
        try {
            $cart = $this->session->get("cart", []);
            $this->session->set("user", $_POST["user"]);
            $productQueries = $this->container->get("Jarenal\App\Model\ProductQueries");
            $products = $productQueries->findAll();
            return $this->view->render(
                "steps/step2.tpl",
                ["title" => "Step #2", "products" => $products, "cart_counter" => count($cart)]
            );
        } catch (Exception $ex) {
            return $this->view->render("error/message.tpl", ["message" => $ex->getMessage()]);
        }
    }

    /**
     * @return string
     */
    public function step3(): string
    {
        try {
            if ($_SERVER["REQUEST_METHOD"] === "GET") {
                $quoteQueries = $this->container->get("Jarenal\App\Model\QuoteQueries");
                $quote = $quoteQueries->findById($_GET["id"]);
                $user = $quote->getUser();
            } else {
                $userData = $this->session->get("user", []);

                $user = $this->container->get("Jarenal\App\Model\User");
                $user->setName($userData["name"])
                    ->setPassword($userData["password"])
                    ->setEmail($userData["email"])
                    ->setPhone($userData["phone"])
                    ->save();

                $quote = $this->container->get("Jarenal\App\Model\Quote");
                $quote->addUser($user)
                    ->setReference(uniqid("Q-"));

                $cartData = $this->session->get("cart", []);
                foreach ($cartData as $current) {
                    $item = [];
                    $productQueries = $this->container->get("Jarenal\App\Model\ProductQueries");
                    $product = $productQueries->findById($current["id"]);
                    $item["product"] = $product;
                    $item["quantity"] = $current["quantity"];
                    $item["metadata"] = $current["metadata"];
                    $line = $this->container->get("Jarenal\App\Model\QuoteLine");
                    $line->addProduct($item["product"])
                        ->setQuantity($item["quantity"])
                        ->setMetadata($item["metadata"]);
                    $quote->addLine($line);
                }

                $quote->save();

                $this->session->set("user", []);
                $this->session->set("cart", []);
            }

            return $this->view->render(
                "steps/step3.tpl",
                [
                    "title" => "Step #3",
                    "quote" => $quote,
                    "lines" => $quote->getLines(),
                    "total" => $quote->getTotal(),
                    "user" => $user
                ]
            );
        } catch (Exception $ex) {
            return $this->view->render("error/message.tpl", ["message" => $ex->getMessage()]);
        }
    }
}
