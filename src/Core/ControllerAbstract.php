<?php
declare(strict_types=1);

namespace Jarenal\Core;

class ControllerAbstract implements ControllerInterface
{
    protected $view;
    protected $session;
    protected $database;
    protected $container;

    public function __construct(Container $container, View $view, Session $session, Database $database)
    {
        $this->container = $container;
        $this->view = $view;
        $this->session = $session;
        $this->database = $database;
    }
}
