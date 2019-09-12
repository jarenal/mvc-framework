<?php


namespace Jarenal\Core;


class ControllerAbstract implements ControllerInterface
{
    protected $view;

    public function __construct(ViewInterface $view)
    {
        $this->view = $view;
    }
}