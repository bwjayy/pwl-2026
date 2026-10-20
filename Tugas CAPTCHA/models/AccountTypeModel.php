<?php

use Ramsey\Uuid\Uuid;

class AccountTypeModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAccountType(?string $search = null): array
    {
        if (!empty($search)) {
            $stmt = $this->db->prepare("
                SELECT * FROM `account_type`
                WHERE `deleted_at` IS NULL
                  AND (`name` LIKE ? OR `description` LIKE ?)
                ORDER BY `created_at` DESC
            ");
            $q = "%{$search}%";
            $stmt->execute([$q, $q]);
            return $stmt->fetchAll();
        }

        $stmt = $this->db->query("
            SELECT * FROM `account_type`
            WHERE `deleted_at` IS NULL
            ORDER BY `created_at` DESC
        ");
        return $stmt->fetchAll();
    }

    public function getAccountTypeById(string $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM `account_type`
            WHERE `id` = ? AND `deleted_at` IS NULL
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function createAccountType(array $data): string
    {
        $id = Uuid::uuid4()->toString();
        $now = date('Y-m-d H:i:s');

        $stmt = $this->db->prepare("
            INSERT INTO `account_type` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`)
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

    public function updateAccountType(string $id, array $data): bool
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("
            UPDATE `account_type`
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

    public function deleteAccountType(string $id): bool
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("
            UPDATE `account_type`
            SET `deleted_at` = ?
            WHERE `id` = ? AND `deleted_at` IS NULL
        ");
        return $stmt->execute([$now, $id]);
    }
}
