<?php

namespace Jarenal\App\Controller;

use Jarenal\Core\ControllerAbstract;

class Home extends ControllerAbstract
{
    public function index()
    {
        return $this->view->render("home/index.tpl", ["greeting" => "Hello World"]);
    }
}
