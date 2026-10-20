<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('acakCaptcha')) {
    function acakCaptcha() {
        $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $pass = array();
        $panjangAlpha = strlen($alphabet) - 2;
        for ($i = 0; $i < 5; $i++) {
            $n = rand(0, $panjangAlpha);
            $pass[] = $alphabet[$n];
        }
        // Ubah array menjadi string
        return implode($pass);
    }
}

// Untuk mengacak captcha
$code = acakCaptcha();
$_SESSION["code"] = $code;

// Disable cache agar gambar selalu update saat di-refresh
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

if (function_exists('imagecreatetruecolor')) {
    // Lebar dan tinggi captcha (173 x 50) sesuai modul
    $wh = imagecreatetruecolor(173, 50);

    // Background color biru (22, 86, 165)
    $bgc = imagecolorallocate($wh, 22, 86, 165);

    // Text color abu-abu (223, 230, 233)
    $fc = imagecolorallocate($wh, 223, 230, 233);

    imagefill($wh, 0, 0, $bgc);

    // ( $image, $fontsize, $x, $y, $string, $fontcolor )
    imagestring($wh, 5, 52, 16, $code, $fc);

    // Buat gambar JPEG
    header('Content-Type: image/jpeg');
    imagejpeg($wh);
    imagedestroy($wh);
} else {
    // Fallback otomatis (SVG) jika ekstensi GD belum diaktifkan di php.ini
    header('Content-Type: image/svg+xml');
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="173" height="50">';
    echo '<rect width="173" height="50" fill="rgb(22, 86, 165)"/>';
    echo '<text x="86" y="33" font-family="Courier New, monospace" font-size="22" font-weight="bold" fill="rgb(223, 230, 233)" text-anchor="middle" letter-spacing="4">' . htmlspecialchars($code) . '</text>';
    echo '</svg>';
}

