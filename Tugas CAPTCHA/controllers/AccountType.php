<?php

require_once __DIR__ . '/../models/AccountTypeModel.php';

class AccountType
{
    private AccountTypeModel $model;
    private Loader $load;

    public function __construct()
    {
        if (!Auth::isAdmin()) {
            http_response_code(403);
            require_once __DIR__ . '/../core/Loader.php';
            $load = new Loader();
            $load->view('views/errors/403.php', [
                'message' => 'Hanya Administrator yang memiliki wewenang untuk mengelola Tipe Akun.'
            ]);
            exit;
        }

        $this->model = new AccountTypeModel();
        $this->load = new Loader();
    }

    private function validate(array $data): array
    {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'Nama tipe akun wajib diisi.';
        }

        return $errors;
    }

    public function index(): void
    {
        $search = trim($_GET['q'] ?? '');
        $types = $this->model->getAccountType($search !== '' ? $search : null);

        $flashSuccess = $_SESSION['flash_success'] ?? null;
        $flashError = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->load->view('views/account_type/index.php', [
            'types' => $types,
            'search' => $search,
            'flashSuccess' => $flashSuccess,
            'flashError' => $flashError,
            'activeNav' => 'account_type',
        ]);
    }

    public function create(): void
    {
        $this->load->view('views/account_type/form.php', [
            'isEdit' => false,
            'values' => [],
            'errors' => [],
            'activeNav' => 'account_type',
        ]);
    }

    public function store(): void
    {
        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $this->load->view('views/account_type/form.php', [
                'isEdit' => false,
                'values' => $_POST,
                'errors' => $errors,
                'activeNav' => 'account_type',
            ]);
            return;
        }

        $this->model->createAccountType([
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? '',
        ]);

        $_SESSION['flash_success'] = 'Tipe akun berhasil ditambahkan.';
        header('Location: ' . BASE_URL . '/account-type');
        exit;
    }

    public function edit(string $id): void
    {
        $type = $this->model->getAccountTypeById($id);

        if (!$type) {
            $_SESSION['flash_error'] = 'Data tipe akun tidak ditemukan.';
            header('Location: ' . BASE_URL . '/account-type');
            exit;
        }

        $this->load->view('views/account_type/form.php', [
            'isEdit' => true,
            'id' => $id,
            'values' => $type,
            'errors' => [],
            'activeNav' => 'account_type',
        ]);
    }

    public function update(string $id): void
    {
        $type = $this->model->getAccountTypeById($id);

        if (!$type) {
            $_SESSION['flash_error'] = 'Data tipe akun tidak ditemukan.';
            header('Location: ' . BASE_URL . '/account-type');
            exit;
        }

        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $this->load->view('views/account_type/form.php', [
                'isEdit' => true,
                'id' => $id,
                'values' => $_POST,
                'errors' => $errors,
                'activeNav' => 'account_type',
            ]);
            return;
        }

        $this->model->updateAccountType($id, [
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? '',
        ]);

        $_SESSION['flash_success'] = 'Tipe akun berhasil diperbarui.';
        header('Location: ' . BASE_URL . '/account-type');
        exit;
    }

    public function delete(string $id): void
    {
        $type = $this->model->getAccountTypeById($id);

        if (!$type) {
            $_SESSION['flash_error'] = 'Data tipe akun tidak ditemukan.';
            header('Location: ' . BASE_URL . '/account-type');
            exit;
        }

        $this->model->deleteAccountType($id);

        $_SESSION['flash_success'] = 'Tipe akun berhasil dihapus (soft delete).';
        header('Location: ' . BASE_URL . '/account-type');
        exit;
    }
}
