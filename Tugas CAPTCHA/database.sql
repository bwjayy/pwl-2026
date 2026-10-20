-- Database Schema and Seeds for UTS Pemrograman Web Lanjut
-- Database: PBL_TI_2025_C_Kelompok1

CREATE DATABASE IF NOT EXISTS `PBL_TI_2025_C_Kelompok1` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `PBL_TI_2025_C_Kelompok1`;

-- 1. Table account_type
DROP TABLE IF EXISTS `accounts`;
DROP TABLE IF EXISTS `account_type`;
CREATE TABLE `account_type` (
    `id` CHAR(36) NOT NULL,
    `name` VARCHAR(128) NOT NULL,
    `description` TEXT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Table accounts
CREATE TABLE `accounts` (
    `id` CHAR(36) NOT NULL,
    `name` VARCHAR(128) NOT NULL,
    `email` VARCHAR(128) NOT NULL UNIQUE,
    `password` TEXT NOT NULL,
    `account_type_id` CHAR(36) NOT NULL,
    `status` VARCHAR(128) NOT NULL DEFAULT 'Aktif',
    `identification_number` VARCHAR(128) NOT NULL,
    `identification_type` ENUM('NIM', 'NIP') NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `fk_accounts_account_type` (`account_type_id`),
    CONSTRAINT `fk_accounts_account_type` FOREIGN KEY (`account_type_id`) REFERENCES `account_type` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Table actions
DROP TABLE IF EXISTS `actions`;
CREATE TABLE `actions` (
    `id` CHAR(36) NOT NULL,
    `name` VARCHAR(128) NOT NULL,
    `description` TEXT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seeder Data
INSERT INTO `account_type` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
('11111111-1111-4111-8111-111111111111', 'Admin', 'Pengelola sistem, punya akses penuh', NOW(), NOW(), NULL),
('22222222-2222-4222-8222-222222222222', 'Dosen', 'Akun untuk dosen (identitas NIP)', NOW(), NOW(), NULL),
('33333333-3333-4333-8333-333333333333', 'Mahasiswa', 'Akun untuk mahasiswa (identitas NIM)', NOW(), NOW(), NULL);

INSERT INTO `accounts` (`id`, `name`, `email`, `password`, `account_type_id`, `status`, `identification_number`, `identification_type`, `created_at`, `updated_at`, `deleted_at`) VALUES
('aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa', 'Administrator', 'admin@pnj.ac.id', 'admin123', '11111111-1111-4111-8111-111111111111', 'Aktif', '520000000000000746', 'NIP', NOW(), NOW(), NULL),
('bbbbbbbb-bbbb-4bbb-8bbb-bbbbbbbbbbbb', 'Ikbal Maulana', 'iam@balbalcode.my.id', 'dosen123', '22222222-2222-4222-8222-222222222222', 'Aktif', '520000000000000746', 'NIP', NOW(), NOW(), NULL);

INSERT INTO `actions` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
('44444444-4444-4444-8444-444444444444', 'Create', 'Menambahkan data baru', NOW(), NOW(), NULL),
('55555555-5555-4555-8555-555555555555', 'Read', 'Melihat data', NOW(), NOW(), NULL),
('66666666-6666-4666-8666-666666666666', 'Update', 'Mengubah data yang sudah ada', NOW(), NOW(), NULL),
('77777777-7777-4777-8777-777777777777', 'Delete', 'Menghapus data (soft delete)', NOW(), NOW(), NULL);
