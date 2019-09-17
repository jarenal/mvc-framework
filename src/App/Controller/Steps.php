<?php

namespace Jarenal\App\Controller;

use Jarenal\App\Model\ProductQueries;
use Jarenal\App\Model\Quote;
use Jarenal\App\Model\User;
use Jarenal\Core\Config;
use Jarenal\Core\ControllerAbstract;
use Jarenal\Core\Database;
use mysqli;

class Steps extends ControllerAbstract
{
    public function step1()
    {
        return $this->view->render("steps/step1.tpl", ["title" => "1- Enter your personal data"]);
    }

    public function step2()
    {
        $this->session->set("user", $_POST["user"]);
        $config = new Config(PROJECT_ROOT_DIR."/config/config.yaml");
        $database = new Database(new mysqli(), $config);
        $productQueries = new ProductQueries($database);
        $products = $productQueries->findAll();
        return $this->view->render("steps/step2.tpl", ["title" => "2- Choose your products", "products" => $products]);
    }

    public function step3()
    {
        $data = $this->session->get("user");
        var_dump($data);

        $user = new User($this->database);
        $user->setName($data["name"])
            ->setPassword($data["password"])
            ->setEmail($data["email"])
            ->setPhone($data["phone"])
            ->save();

        $quote = new Quote($this->database);
        $quote->setUser($user)
            ->setReference(uniqid("Q-"))
            ->setTotal(123.45)
            ->save();

        return $this->view->render("steps/step3.tpl", ["title" => "3- Thank you!"]);
    }
}
