<?php

namespace Jarenal\Core;

class ControllerAbstract implements ControllerInterface
{
    protected $view;
    protected $session;
    protected $database;

    public function __construct(ViewInterface $view, SessionInterface $session, DatabaseInterface $database)
    {
        $this->view = $view;
        $this->session = $session;
        $this->database = $database;
    }
}
