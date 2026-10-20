<?php

use Ramsey\Uuid\Uuid;

class ActionsModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getActions(?string $search = null): array
    {
        if (!empty($search)) {
            $stmt = $this->db->prepare("
                SELECT * FROM `actions`
                WHERE `deleted_at` IS NULL
                  AND (`name` LIKE ? OR `description` LIKE ?)
                ORDER BY `created_at` DESC
            ");
            $q = "%{$search}%";
            $stmt->execute([$q, $q]);
            return $stmt->fetchAll();
        }

        $stmt = $this->db->query("
            SELECT * FROM `actions`
            WHERE `deleted_at` IS NULL
            ORDER BY `created_at` DESC
        ");
        return $stmt->fetchAll();
    }

    public function getActionById(string $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM `actions`
            WHERE `id` = ? AND `deleted_at` IS NULL
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function createAction(array $data): string
    {
        $id = Uuid::uuid4()->toString();
        $now = date('Y-m-d H:i:s');

        $stmt = $this->db->prepare("
            INSERT INTO `actions` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`)
            VALUES (?, ?, ?, ?, ?, NULL)
        ");
        $stmt->execute([
            $id,
            trim($data['name'] ?? ''),
            trim($data['description'] ?? ''),
            $now,
            $now,
        ]);

        return $id;
    }

    public function updateAction(string $id, array $data): bool
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("
            UPDATE `actions`
            SET `name` = ?, `description` = ?, `updated_at` = ?
            WHERE `id` = ? AND `deleted_at` IS NULL
        ");
        return $stmt->execute([
            trim($data['name'] ?? ''),
            trim($data['description'] ?? ''),
            $now,
            $id,
        ]);
    }

    public function deleteAction(string $id): bool
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("
            UPDATE `actions`
            SET `deleted_at` = ?
            WHERE `id` = ? AND `deleted_at` IS NULL
        ");
        return $stmt->execute([$now, $id]);
    }
}
