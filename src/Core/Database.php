<?php

namespace Jarenal\Core;

use Exception;
use mysqli;

class Database implements DatabaseInterface
{
    private $mysqli;
    private $config;
    private $connected;

    public function __construct(mysqli $mysqli, Config $config)
    {
        $this->mysqli = $mysqli;
        $this->config = $config;
    }

    public function connect()
    {
        try {
            $db = $this->config->get("database");

            $this->connected = $this->mysqli->real_connect(
                $db["host"],
                $db["username"],
                $db["password"],
                $db["name"],
                $db["port"]
            );

            if ($this->mysqli->connect_errno) {
                throw new Exception("Connection failure: " . $this->mysqli->connect_error);
            }

            return true;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function close()
    {
        try {
            $close = $this->mysqli->close();
            if ($close === false) {
                throw new Exception("Closing failure.");
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function executeQuery($sql, $params = [])
    {
        try {
            if (!$this->connected) {
                $this->connect();
            }

            if ($params && is_array($params)) {
                $clean_params = array();
                foreach ($params as $value) {
                    // Prevent SQL Injection
                    $clean_params[] = $this->mysqli->real_escape_string($value);
                }

                $sql = vsprintf($sql, $clean_params);
            }

            $result = $this->mysqli->query($sql);
            if ($result === false) {
                throw new Exception($this->mysqli->error);
            }

            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getLastId()
    {
        return $this->mysqli->insert_id;
    }
}
