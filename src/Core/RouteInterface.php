<?php

namespace Jarenal\Core;

interface RouteInterface
{
    public function setController($controller);

    public function getController();

    public function setAction($action);

    public function getAction();
}
