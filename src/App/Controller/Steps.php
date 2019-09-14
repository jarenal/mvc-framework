<?php

namespace Jarenal\App\Controller;

use Jarenal\App\Model\Quote;
use Jarenal\App\Model\User;
use Jarenal\Core\ControllerAbstract;

class Steps extends ControllerAbstract
{
    public function step1()
    {
        return $this->view->render("home/step1.tpl", ["title" => "1- Enter your personal data"]);
    }

    public function step2()
    {
        $this->session->set("user", $_POST["user"]);
        return $this->view->render("home/step2.tpl", ["title" => "2- Choose your products"]);
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

        return $this->view->render("home/step3.tpl", ["title" => "3- Thank you!"]);
    }
}
