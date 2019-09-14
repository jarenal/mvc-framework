<?php

namespace Jarenal\Core;

interface DatabaseInterface
{
    public function connect();
    public function close();
    public function executeQuery($sql, $params = []);
    public function getLastId();
}