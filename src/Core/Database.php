<?php
declare(strict_types=1);

namespace Jarenal\Core;

use Exception;
use mysqli;

/**
 * Class Database
 * @package Jarenal\Core
 */
class Database implements DatabaseInterface
{
    /**
     * @var mysqli
     */
    private $mysqli;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var bool
     */
    private $connected = false;

    /**
     * Database constructor.
     * @param mysqli $mysqli
     * @param Config $config
     */
    public function __construct(MysqliWrapper $mysqli, Config $config)
    {
        $this->mysqli = $mysqli;
        $this->config = $config;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function connect(): bool
    {
        try {
            $db = $this->config->get("database");

            $this->connected = $this->mysqli->real_connect(
                $db["host"],
                $db["username"],
                $db["password"],
                $db["name"],
                (int)$db["port"]
            );

            if ($this->connected === false && $this->mysqli->connect_errno) {
                throw new Exception("Connection failure: " . $this->mysqli->connect_error);
            }

            return true;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @throws Exception
     */
    public function close(): void
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

    /**
     * @param string $sql
     * @param array $params
     * @return bool|\mysqli_result
     * @throws Exception
     */
    public function executeQuery(string $sql, array $params = [])
    {
        try {
            if (!$this->isConnected()) {
                $this->connect();
            }

            if ($params && is_array($params)) {
                $clean_params = array();
                foreach ($params as $value) {
                    // Prevent SQL Injection
                    $clean_params[] = $this->mysqli->real_escape_string((string)$value);
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

    /**
     * @return int
     */
    public function getLastId(): int
    {
        return $this->mysqli->insert_id;
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->connected;
    }
}
