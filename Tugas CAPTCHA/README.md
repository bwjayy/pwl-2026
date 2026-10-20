# Sistem Manajemen Identitas & Hak Akses (IAM) TIK PNJ

Aplikasi pengelolaan data Akun, Tipe Akun, dan Hak Akses berbasis **PHP Native MVC** murni dengan integrasi **UUID v4** dan **Soft Delete**, dibangun untuk Ujian Tengah Semester (UTS) Praktik Pemrograman Web Lanjut Politeknik Negeri Jakarta.

---

## Prasyarat Sistem (Prerequisites)

Pastikan komputer/laptop Anda telah terpasang:
- **Git** ([Download Git](https://git-scm.com/))
- **PHP** versi 8.1 atau lebih tinggi (Diuji pada PHP 8.2 XAMPP)
- **Composer** ([Download Composer](https://getcomposer.org/))
- **MySQL / MariaDB** (melalui XAMPP)

---

## Panduan Instalasi dari Awal (Setup Guide)

Ikuti langkah-langkah berikut secara berurutan untuk menjalankan proyek di komputer lokal Anda:

### 1. Clone Repository
Buka Terminal / Command Prompt / Git Bash, lalu jalankan:
```bash
git clone https://github.com/rahmatmln/UTS-PWL.git
cd NAMA_REPO
```
*(Sesuaikan URL repositori dengan repository GitHub kelompok Anda).*

---

### 2. Install Dependensi Composer
Jalankan perintah ini di dalam direktori proyek untuk mengunduh library yang dibutuhkan (termasuk `ramsey/uuid`):
```bash
composer install
```
> Perintah ini akan otomatis membuat folder `vendor/` dan autoloader `vendor/autoload.php`.

---

### 3. Setup Basis Data MySQL
1. Buka aplikasi **XAMPP Control Panel**, lalu klik **Start** pada modul **MySQL** dan **Apache**.
2. Buat database baru bernama:
   ```text
   PBL_TI_2025_C_Kelompok1
   ```
3. Import file [**`database.sql`**](database.sql) yang ada di root proyek ini:
   - **Opsi phpMyAdmin**: Buka browser ke `http://localhost/phpmyadmin`, pilih database `PBL_TI_2025_C_Kelompok1`, klik menu **Import**, pilih file `database.sql`, lalu klik **Import / Go**.
   - **Opsi Terminal / Command Prompt**:
     ```bash
     mysql -u root -p PBL_TI_2025_C_Kelompok1 < database.sql
     ```
   File `database.sql` sudah otomatis membuat 3 tabel (`accounts`, `account_type`, `actions`) lengkap dengan skema UUID, timestamps, dan data bawaan (*seeder*).

---

### 4. Sesuaikan Konfigurasi Database (Jika Diperlukan)
Buka file [**`config/database.php`**](config/database.php) dan sesuaikan pengaturan koneksi dengan MySQL di komputer Anda:
```php
$host   = '127.0.0.1';
$port   = '3306'; // Ubah ke 3307 jika MySQL XAMPP Anda menggunakan port 3307
$dbname = 'PBL_TI_2025_C_Kelompok1';
$user   = 'root';
$pass   = '';     // Kosongkan jika default XAMPP tanpa password
```

---

### 5. Menjalankan Aplikasi

Anda dapat memilih salah satu dari dua cara berikut:

#### Opsi A: Menggunakan PHP Built-in Server (Paling Cepat & Direkomendasikan)
Jalankan perintah ini di terminal:
```bash
composer serve
```
atau:
```bash
php -S localhost:5000 index.php
```
Buka browser Anda dan akses:
```
http://localhost:5000
```

#### Opsi B: Menggunakan Apache XAMPP (`htdocs`)
1. Tempatkan folder proyek ini di dalam folder `htdocs` XAMPP, contoh:
   `C:\xampp\htdocs\UTS`
2. Pastikan servis Apache di XAMPP aktif.
3. Buka browser dan akses:
   ```
   http://localhost/UTS
   ```
   *(Sistem router otomatis mengenali base subfolder tanpa perlu konfigurasi tambahan).*

---

## Kredensial Akun Bawaan (Default Login)

Gunakan salah satu akun berikut untuk masuk ke sistem:

| Peran (Role) | Email | Password | Keterangan Akses |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@pnj.ac.id` | `admin123` | **Full Access**: Kelola seluruh akun, tipe akun, jenis aksi, dan filter data. |
| **Dosen** | `iam@balbalcode.my.id` | `dosen123` | **Role-Isolated**: Hanya melihat sesama dosen & ganti password sendiri. |
| **Mahasiswa** | `mhs@pnj.ac.id` | `password_baru_mhs` | **Role-Isolated**: Hanya melihat sesama mahasiswa & ganti password sendiri. |

---

## Struktur Direktori

```
├── config/             # Konfigurasi koneksi PDO MySQL
├── controllers/        # Logika aplikasi (Auth, Accounts, AccountType, Actions)
├── core/               # Helper session, otorisasi RBAC, & View Loader
├── models/             # Query basis data (UUID v4 & Soft Delete)
├── views/              # Tampilan antarmuka pengguna (Layout, Modul, Error)
├── .gitignore          # File pengecualian upload Git
├── composer.json       # Definisi dependensi Composer
├── database.sql        # Skrip DDL & DML database siap import
├── DESIGN.md           # Panduan desain visual & standar UI antislop
├── index.php           # Front Controller & URL router utama
└── README.md           # Panduan instalasi dan penggunaan
```

---

## Troubleshooting Umum

1. **Error: `Fatal error: Uncaught Error: Class "Ramsey\Uuid\Uuid" not found`**
   - **Solusi**: Jalankan perintah `composer install` di terminal untuk mengunduh library yang dibutuhkan.
2. **Error: `Connection refused` atau `Access denied for user`**
   - **Solusi**: Periksa kembali nomor port MySQL di `config/database.php` (default XAMPP adalah `3306`, namun beberapa instalasi menggunakan `3307`). Pastikan juga nama database `PBL_TI_2025_C_Kelompok1` sudah dibuat.
