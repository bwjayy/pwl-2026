<?php

require_once __DIR__ . '/../models/ActionsModel.php';

class Actions
{
    private ActionsModel $model;
    private Loader $load;

    public function __construct()
    {
        if (!Auth::isAdmin()) {
            http_response_code(403);
            require_once __DIR__ . '/../core/Loader.php';
            $load = new Loader();
            $load->view('views/errors/403.php', [
                'message' => 'Hanya Administrator yang memiliki wewenang untuk mengelola Jenis Aksi.'
            ]);
            exit;
        }

        $this->model = new ActionsModel();
        $this->load = new Loader();
    }

    private function validate(array $data): array
    {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'Nama aksi wajib diisi.';
        }

        return $errors;
    }

    public function index(): void
    {
        $search = trim($_GET['q'] ?? '');
        $actions = $this->model->getActions($search !== '' ? $search : null);

        $flashSuccess = $_SESSION['flash_success'] ?? null;
        $flashError = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->load->view('views/actions/index.php', [
            'actions' => $actions,
            'search' => $search,
            'flashSuccess' => $flashSuccess,
            'flashError' => $flashError,
            'activeNav' => 'actions',
        ]);
    }

    public function create(): void
    {
        $this->load->view('views/actions/form.php', [
            'isEdit' => false,
            'values' => [],
            'errors' => [],
            'activeNav' => 'actions',
        ]);
    }

    public function store(): void
    {
        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $this->load->view('views/actions/form.php', [
                'isEdit' => false,
                'values' => $_POST,
                'errors' => $errors,
                'activeNav' => 'actions',
            ]);
            return;
        }

        $this->model->createAction([
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? '',
        ]);

        $_SESSION['flash_success'] = 'Jenis aksi berhasil ditambahkan.';
        header('Location: ' . BASE_URL . '/actions');
        exit;
    }

    public function edit(string $id): void
    {
        $action = $this->model->getActionById($id);

        if (!$action) {
            $_SESSION['flash_error'] = 'Data jenis aksi tidak ditemukan.';
            header('Location: ' . BASE_URL . '/actions');
            exit;
        }

        $this->load->view('views/actions/form.php', [
            'isEdit' => true,
            'id' => $id,
            'values' => $action,
            'errors' => [],
            'activeNav' => 'actions',
        ]);
    }

    public function update(string $id): void
    {
        $action = $this->model->getActionById($id);

        if (!$action) {
            $_SESSION['flash_error'] = 'Data jenis aksi tidak ditemukan.';
            header('Location: ' . BASE_URL . '/actions');
            exit;
        }

        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $this->load->view('views/actions/form.php', [
                'isEdit' => true,
                'id' => $id,
                'values' => $_POST,
                'errors' => $errors,
                'activeNav' => 'actions',
            ]);
            return;
        }

        $this->model->updateAction($id, [
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? '',
        ]);

        $_SESSION['flash_success'] = 'Jenis aksi berhasil diperbarui.';
        header('Location: ' . BASE_URL . '/actions');
        exit;
    }

    public function delete(string $id): void
    {
        $action = $this->model->getActionById($id);

        if (!$action) {
            $_SESSION['flash_error'] = 'Data jenis aksi tidak ditemukan.';
            header('Location: ' . BASE_URL . '/actions');
            exit;
        }

        $this->model->deleteAction($id);

        $_SESSION['flash_success'] = 'Jenis aksi berhasil dihapus (soft delete).';
        header('Location: ' . BASE_URL . '/actions');
        exit;
    }
}
