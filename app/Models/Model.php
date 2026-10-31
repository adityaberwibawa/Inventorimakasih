<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    /**
     * @param array<string, mixed> $params
     * @return array<int, array<string, mixed>>
     */
    protected function fetchAll(string $sql, array $params = []): array
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>|null
     */
    protected function fetch(string $sql, array $params = []): ?array
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        $result = $statement->fetch();

        return $result === false ? null : $result;
    }

    /**
     * @param array<string, mixed> $params
     */
    protected function execute(string $sql, array $params = []): bool
    {
        $statement = $this->db->prepare($sql);

        return $statement->execute($params);
    }

    protected function lastInsertId(): int
    {
        return (int) $this->db->lastInsertId();
    }
}
