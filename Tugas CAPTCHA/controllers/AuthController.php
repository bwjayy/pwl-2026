<?php

require_once __DIR__ . '/../models/AccountsModel.php';

class AuthController
{
    private AccountsModel $accountsModel;
    private Loader $load;

    public function __construct()
    {
        $this->accountsModel = new AccountsModel();
        $this->load = new Loader();
    }

    public function login(): void
    {
        if (Auth::check()) {
            header('Location: ' . BASE_URL . '/accounts');
            exit;
        }

        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        $this->load->view('views/auth/login.php', [
            'error' => $error,
        ]);
    }

    public function captcha(): void
    {
        require __DIR__ . '/../captcha.php';
        exit;
    }

    public function authenticate(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $captcha = trim($_POST['kodecaptcha'] ?? '');

        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'Silakan isi email dan password.';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if (empty($captcha)) {
            $_SESSION['login_error'] = 'Silakan isikan kode CAPTCHA.';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $sessionCaptcha = $_SESSION['code'] ?? '';
        if (strcasecmp($captcha, $sessionCaptcha) !== 0) {
            $_SESSION['login_error'] = 'Kode CAPTCHA yang dimasukkan salah.';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $user = $this->accountsModel->findByEmail($email);

        if (!$user) {
            $_SESSION['login_error'] = 'Akun dengan email tersebut tidak ditemukan.';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($user['status'] !== 'Aktif') {
            $_SESSION['login_error'] = 'Akun Anda sedang nonaktif. Hubungi administrator.';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Direct plaintext check per user requirement
        if ($user['password'] !== $password) {
            $_SESSION['login_error'] = 'Password yang dimasukkan tidak cocok.';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        Auth::login($user);

        header('Location: ' . BASE_URL . '/accounts');
        exit;
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
