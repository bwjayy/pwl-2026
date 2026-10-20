<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil CAPTCHA</title>
</head>
<body>
    <h2 align="center">Informasi Login:</h2>
    <p align="center">
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $nama = htmlspecialchars($_POST["username"] ?? $_POST["email"] ?? '');
    $pwd = htmlspecialchars($_POST["password"] ?? '');
    $inputCaptcha = trim($_POST["kodecaptcha"] ?? '');
    $sessionCaptcha = $_SESSION["code"] ?? '';

    if ($sessionCaptcha === '' || strcasecmp($inputCaptcha, $sessionCaptcha) !== 0) {
        echo "Username anda adalah <b>$nama</b><br />";
        echo "Password anda adalah <b>$pwd</b><br /><br />";
        echo "<span style='color:red;'>Kode CAPTCHA anda salah</span>";
    } else {
        echo "Username anda <b>$nama</b><br />";
        echo "Password anda <b>$pwd</b><br /><br />";
        echo "<span style='color:green;'>Kode CAPTCHA anda benar</span>";
    }
    ?>
    </p>
</body>
</html>

