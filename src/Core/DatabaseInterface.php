<?php

namespace Jarenal\Core;

use mysqli_result;

interface DatabaseInterface
{
    public function connect();
    public function close();
    public function executeQuery($sql, $params = []): mysqli_result;
    public function getLastId();
}