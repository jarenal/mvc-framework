<?php

namespace Jarenal\App\Controller;

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
        $user = $this->session->get("user");
        var_dump($user);
        return $this->view->render("home/step3.tpl", ["title" => "3- Thank you!"]);
    }
}
