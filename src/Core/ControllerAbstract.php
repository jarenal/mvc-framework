<?php

namespace Jarenal\Core;

class ControllerAbstract implements ControllerInterface
{
    protected $view;
    protected $session;

    public function __construct(ViewInterface $view, Session $session)
    {
        $this->view = $view;
        $this->session = $session;
    }
}
