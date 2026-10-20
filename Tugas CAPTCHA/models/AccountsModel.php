<?php

use Ramsey\Uuid\Uuid;

class AccountsModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAccounts(?string $search = null, ?string $typeFilter = null, ?string $enforceTypeId = null): array
    {
        $sql = "
            SELECT 
                a.*, 
                at.name AS account_type_name
            FROM `accounts` a
            LEFT JOIN `account_type` at ON a.account_type_id = at.id
            WHERE a.deleted_at IS NULL
        ";
        $params = [];

        // Role isolation for non-admin
        if (!empty($enforceTypeId)) {
            $sql .= " AND a.account_type_id = ? ";
            $params[] = $enforceTypeId;
        }

        // Search by name, email, or identification number
        if (!empty($search)) {
            $sql .= " AND (a.name LIKE ? OR a.email LIKE ? OR a.identification_number LIKE ?) ";
            $q = "%{$search}%";
            $params[] = $q;
            $params[] = $q;
            $params[] = $q;
        }

        // Filter by account type (name or id)
        if (!empty($typeFilter)) {
            $sql .= " AND (at.name LIKE ? OR at.id = ?) ";
            $params[] = "%{$typeFilter}%";
            $params[] = $typeFilter;
        }

        $sql .= " ORDER BY a.created_at DESC ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAccountById(string $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.*, 
                at.name AS account_type_name
            FROM `accounts` a
            LEFT JOIN `account_type` at ON a.account_type_id = at.id
            WHERE a.id = ? AND a.deleted_at IS NULL
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.*, 
                at.name AS role_name
            FROM `accounts` a
            LEFT JOIN `account_type` at ON a.account_type_id = at.id
            WHERE a.email = ? AND a.deleted_at IS NULL
            LIMIT 1
        ");
        $stmt->execute([trim($email)]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function createAccount(array $data): string
    {
        $id = Uuid::uuid4()->toString();
        $now = date('Y-m-d H:i:s');

        $stmt = $this->db->prepare("
            INSERT INTO `accounts` (
                `id`, `name`, `email`, `password`, `account_type_id`, 
                `status`, `identification_number`, `identification_type`, 
                `created_at`, `updated_at`, `deleted_at`
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)
        ");

        $stmt->execute([
            $id,
            trim($data['name'] ?? ''),
            trim($data['email'] ?? ''),
            $data['password'] ?? '',
            $data['account_type_id'] ?? '',
            $data['status'] ?? 'Aktif',
            trim($data['identification_number'] ?? ''),
            $data['identification_type'] ?? 'NIM',
            $now,
            $now,
        ]);

        return $id;
    }

    public function updateAccount(string $id, array $data): bool
    {
        $now = date('Y-m-d H:i:s');

        if (!empty($data['password'])) {
            $stmt = $this->db->prepare("
                UPDATE `accounts`
                SET 
                    `name` = ?,
                    `email` = ?,
                    `password` = ?,
                    `account_type_id` = ?,
                    `status` = ?,
                    `identification_number` = ?,
                    `identification_type` = ?,
                    `updated_at` = ?
                WHERE `id` = ? AND `deleted_at` IS NULL
            ");
            return $stmt->execute([
                trim($data['name'] ?? ''),
                trim($data['email'] ?? ''),
                $data['password'],
                $data['account_type_id'] ?? '',
                $data['status'] ?? 'Aktif',
                trim($data['identification_number'] ?? ''),
                $data['identification_type'] ?? 'NIM',
                $now,
                $id,
            ]);
        }

        $stmt = $this->db->prepare("
            UPDATE `accounts`
            SET 
                `name` = ?,
                `email` = ?,
                `account_type_id` = ?,
                `status` = ?,
                `identification_number` = ?,
                `identification_type` = ?,
                `updated_at` = ?
            WHERE `id` = ? AND `deleted_at` IS NULL
        ");
        return $stmt->execute([
            trim($data['name'] ?? ''),
            trim($data['email'] ?? ''),
            $data['account_type_id'] ?? '',
            $data['status'] ?? 'Aktif',
            trim($data['identification_number'] ?? ''),
            $data['identification_type'] ?? 'NIM',
            $now,
            $id,
        ]);
    }

    public function deleteAccount(string $id): bool
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("
            UPDATE `accounts`
            SET `deleted_at` = ?
            WHERE `id` = ? AND `deleted_at` IS NULL
        ");
        return $stmt->execute([$now, $id]);
    }

    public function updatePassword(string $id, string $password): bool
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("
            UPDATE `accounts`
            SET `password` = ?, `updated_at` = ?
            WHERE `id` = ? AND `deleted_at` IS NULL
        ");
        return $stmt->execute([$password, $now, $id]);
    }
}
