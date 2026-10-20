<?php

require_once __DIR__ . '/../models/AccountsModel.php';
require_once __DIR__ . '/../models/AccountTypeModel.php';

class Accounts
{
    private AccountsModel $model;
    private AccountTypeModel $typeModel;
    private Loader $load;

    public function __construct()
    {
        $this->model = new AccountsModel();
        $this->typeModel = new AccountTypeModel();
        $this->load = new Loader();
    }

    private function requireAdmin(): void
    {
        if (!Auth::isAdmin()) {
            http_response_code(403);
            $this->load->view('views/errors/403.php', [
                'message' => 'Hanya Administrator yang berwenang untuk menambah, mengubah, atau menghapus data akun.'
            ]);
            exit;
        }
    }

    private function validate(array $data, bool $isEdit = false): array
    {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'Nama lengkap wajib diisi.';
        }

        if (empty(trim($data['email'] ?? ''))) {
            $errors['email'] = 'Alamat email wajib diisi.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid.';
        }

        if (!$isEdit && empty($data['password'] ?? '')) {
            $errors['password'] = 'Password wajib diisi untuk akun baru.';
        }

        if (empty($data['account_type_id'] ?? '')) {
            $errors['account_type_id'] = 'Pilih tipe akun.';
        }

        if (empty(trim($data['identification_number'] ?? ''))) {
            $errors['identification_number'] = 'Nomor identitas (NIM / NIP) wajib diisi.';
        }

        if (!in_array($data['identification_type'] ?? '', ['NIM', 'NIP'], true)) {
            $errors['identification_type'] = 'Jenis identitas harus NIM atau NIP.';
        }

        if (!in_array($data['status'] ?? '', ['Aktif', 'Nonaktif'], true)) {
            $errors['status'] = 'Status harus Aktif atau Nonaktif.';
        }

        return $errors;
    }

    public function index(): void
    {
        $search = trim($_GET['q'] ?? '');
        $typeFilter = trim($_GET['type'] ?? '');
        $enforceTypeId = Auth::isAdmin() ? null : Auth::accountTypeId();

        $accounts = $this->model->getAccounts(
            $search !== '' ? $search : null,
            $typeFilter !== '' ? $typeFilter : null,
            $enforceTypeId
        );

        $accountTypes = $this->typeModel->getAccountType();

        $flashSuccess = $_SESSION['flash_success'] ?? null;
        $flashError = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->load->view('views/accounts/index.php', [
            'accounts' => $accounts,
            'accountTypes' => $accountTypes,
            'search' => $search,
            'typeFilter' => $typeFilter,
            'flashSuccess' => $flashSuccess,
            'flashError' => $flashError,
            'activeNav' => 'accounts',
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();
        $accountTypes = $this->typeModel->getAccountType();

        $this->load->view('views/accounts/form.php', [
            'isEdit' => false,
            'accountTypes' => $accountTypes,
            'values' => [
                'status' => 'Aktif',
                'identification_type' => 'NIM',
            ],
            'errors' => [],
            'activeNav' => 'accounts',
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();
        $errors = $this->validate($_POST, false);

        // Check if email already taken
        if (empty($errors['email'])) {
            $existing = $this->model->findByEmail($_POST['email']);
            if ($existing) {
                $errors['email'] = 'Email ini sudah terdaftar.';
            }
        }

        if (!empty($errors)) {
            $accountTypes = $this->typeModel->getAccountType();
            $this->load->view('views/accounts/form.php', [
                'isEdit' => false,
                'accountTypes' => $accountTypes,
                'values' => $_POST,
                'errors' => $errors,
                'activeNav' => 'accounts',
            ]);
            return;
        }

        $this->model->createAccount([
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'account_type_id' => $_POST['account_type_id'],
            'status' => $_POST['status'] ?? 'Aktif',
            'identification_number' => $_POST['identification_number'],
            'identification_type' => $_POST['identification_type'],
        ]);

        $_SESSION['flash_success'] = 'Data akun berhasil ditambahkan.';
        header('Location: ' . BASE_URL . '/accounts');
        exit;
    }

    public function edit(string $id): void
    {
        $account = $this->model->getAccountById($id);

        if (!$account) {
            $_SESSION['flash_error'] = 'Data akun tidak ditemukan.';
            header('Location: ' . BASE_URL . '/accounts');
            exit;
        }

        // Non-admin can only edit their own account password
        if (!Auth::isAdmin()) {
            if ($id !== Auth::id()) {
                http_response_code(403);
                $this->load->view('views/errors/403.php', [
                    'message' => 'Anda hanya memiliki wewenang untuk mengubah password pada akun milik Anda sendiri.'
                ]);
                exit;
            }
        }

        $accountTypes = $this->typeModel->getAccountType();

        $this->load->view('views/accounts/form.php', [
            'isEdit' => true,
            'isSelfPasswordOnly' => !Auth::isAdmin(),
            'id' => $id,
            'account' => $account,
            'values' => $account,
            'accountTypes' => $accountTypes,
            'errors' => [],
            'activeNav' => 'accounts',
        ]);
    }

    public function update(string $id): void
    {
        $account = $this->model->getAccountById($id);

        if (!$account) {
            $_SESSION['flash_error'] = 'Data akun tidak ditemukan.';
            header('Location: ' . BASE_URL . '/accounts');
            exit;
        }

        // Non-admin self password update flow
        if (!Auth::isAdmin()) {
            if ($id !== Auth::id()) {
                http_response_code(403);
                $this->load->view('views/errors/403.php', [
                    'message' => 'Anda hanya memiliki wewenang untuk mengubah password pada akun milik Anda sendiri.'
                ]);
                exit;
            }

            $password = trim($_POST['password'] ?? '');
            if (empty($password)) {
                $accountTypes = $this->typeModel->getAccountType();
                $this->load->view('views/accounts/form.php', [
                    'isEdit' => true,
                    'isSelfPasswordOnly' => true,
                    'id' => $id,
                    'account' => $account,
                    'values' => $account,
                    'accountTypes' => $accountTypes,
                    'errors' => ['password' => 'Password baru tidak boleh kosong.'],
                    'activeNav' => 'accounts',
                ]);
                return;
            }

            $this->model->updatePassword($id, $password);
            $_SESSION['flash_success'] = 'Password akun Anda berhasil diperbarui.';
            header('Location: ' . BASE_URL . '/accounts');
            exit;
        }

        // Admin full update flow
        $errors = $this->validate($_POST, true);

        // Check email collision
        if (empty($errors['email']) && $_POST['email'] !== $account['email']) {
            $existing = $this->model->findByEmail($_POST['email']);
            if ($existing && $existing['id'] !== $id) {
                $errors['email'] = 'Email ini sudah dipakai oleh akun lain.';
            }
        }

        if (!empty($errors)) {
            $accountTypes = $this->typeModel->getAccountType();
            $this->load->view('views/accounts/form.php', [
                'isEdit' => true,
                'isSelfPasswordOnly' => false,
                'id' => $id,
                'account' => $account,
                'values' => $_POST,
                'accountTypes' => $accountTypes,
                'errors' => $errors,
                'activeNav' => 'accounts',
            ]);
            return;
        }

        $this->model->updateAccount($id, [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => !empty($_POST['password']) ? $_POST['password'] : null,
            'account_type_id' => $_POST['account_type_id'],
            'status' => $_POST['status'] ?? 'Aktif',
            'identification_number' => $_POST['identification_number'],
            'identification_type' => $_POST['identification_type'],
        ]);

        $_SESSION['flash_success'] = 'Data akun berhasil diperbarui.';
        header('Location: ' . BASE_URL . '/accounts');
        exit;
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        $account = $this->model->getAccountById($id);

        if (!$account) {
            $_SESSION['flash_error'] = 'Data akun tidak ditemukan.';
            header('Location: ' . BASE_URL . '/accounts');
            exit;
        }

        // Prevent deleting own logged in account
        if ($id === (Auth::user()['id'] ?? '')) {
            $_SESSION['flash_error'] = 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.';
            header('Location: ' . BASE_URL . '/accounts');
            exit;
        }

        $this->model->deleteAccount($id);

        $_SESSION['flash_success'] = 'Data akun berhasil dihapus (soft delete).';
        header('Location: ' . BASE_URL . '/accounts');
        exit;
    }
}
