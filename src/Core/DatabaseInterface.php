<?php
declare(strict_types=1);

namespace Jarenal\Core;

interface DatabaseInterface
{
    public function connect(): bool;
    public function close(): void;
    public function executeQuery(string $sql, array $params = []);
    public function getLastId(): int;
}
