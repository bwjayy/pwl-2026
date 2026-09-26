<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php';

if (isset($_POST['submit'])) {
    $judul   = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi     = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);

    
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    $nama_baru = "";
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $nama_file = $_FILES['gambar']['name'];
        $tmp_file  = $_FILES['gambar']['tmp_name'];
        $ext       = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $nama_baru = time() . '_' . uniqid() . '.' . $ext;
        $tujuan    = 'uploads/' . $nama_baru;

        if (!move_uploaded_file($tmp_file, $tujuan)) {
            die("Gagal memindahkan file upload. Pastikan izin folder uploads sudah benar.");
        }
    }

    
    $query_sql = "INSERT INTO portal_berita (judul, gambar, isi, penulis, tanggal) 
                  VALUES ('$judul', '$nama_baru', '$isi', '$penulis', '$tanggal')";

    try {
        $query = mysqli_query($koneksi, $query_sql);
        if ($query) {
            echo "<script>alert('Berita berhasil disimpan!'); window.location='input.php';</script>";
        } else {
            echo "Gagal query: " . mysqli_error($koneksi);
        }
    } catch (Exception $e) {
        echo "<h3>Terjadi Kesalahan Database:</h3>";
        echo "<p style='color:red;'>" . $e->getMessage() . "</p>";
    }

} else {
    header("Location: input.php");
}
?>