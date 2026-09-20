<?php
// 1. KONEKSI KE DATABASE & MEMBUAT TABEL

$conn = mysqli_connect("127.0.0.1", "root", "", "latihan");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$table_name = 'sales';

$sql = 'CREATE TABLE IF NOT EXISTS `' . $table_name . '` (
	`id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
	`id_produk` int(11) NOT NULL,
	`tgl_transaksi` date NOT NULL,
	`kuantitas` tinyint(4) NOT NULL,
	`harga` int(11) NOT NULL,
	`id_pelanggan` int(11) NOT NULL,
	PRIMARY KEY (`id_transaksi`),
	KEY `id_produk` (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';

$query = mysqli_query($conn, $sql);

if (!$query) {
	die ('ERROR: Tabel ' . $table_name . ' gagal dibuat: ' . mysqli_error($conn));
}
echo 'Tabel ' . $table_name . ' berhasil dibuat <br/>';

// 2. INSERT DATA KE DALAM TABEL
$sql = "INSERT INTO `$table_name` (`id_transaksi`, `id_produk`, `tgl_transaksi`, `kuantitas`, `harga`, `id_pelanggan`)
	VALUES (1, 100, '2016-09-20', 8, 265000, 1),
		(2, 100, '2016-10-11', 3, 270000, 2),
		(3, 101, '2016-08-17', 8, 250000, 2),
		(4, 101, '2016-08-24', 12, 380000, 2),
		(5, 101, '2016-05-10', 12, 250000, 1);";

$query = mysqli_query($conn, $sql);

if (!$query) {
	die ('ERROR: Data gagal dimasukkan pada tabel ' . $table_name . ' : ' . mysqli_error($conn));
}
echo 'Data berhasil dimasukkan pada tabel ' . $table_name . ' <br/><br/>';

// 3. MENAMPILKAN DATA (DENGAN TEMPORARY FIELD / ALIAS 'total_byr')
$sql = 'SELECT id_produk, tgl_transaksi, harga, kuantitas, harga*kuantitas AS total_byr
        FROM sales';

$query = mysqli_query($conn, $sql);

if (!$query) {
	die ('SQL Error: ' . mysqli_error($conn));
}

?>
<html>
<head>
    <title>Menampilkan Data Tabel MySQL Dengan mysqli_fetch_array / assoc / row</title>
    <style>
        body {font-family:tahoma, arial}
        table {border-collapse: collapse; margin-bottom: 20px;}
        th, td {font-size: 13px; border: 1px solid #DEDEDE; padding: 3px 5px; color: #303030}
        th {background: #CCCCCC; font-size: 12px; border-color:#B0B0B0}
        .subtotal td {background: #F8F8F8}
        .right{text-align: right}
    </style>
</head>
<body>

<h3>Tampilan Data (Menggunakan mysqli_fetch_array)</h3>
<table>
    <thead>
        <tr>
            <th>ID PRODUK</th>
            <th>TGL TRANSAKSI</th>
            <th>KUANTITAS</th>
            <th>HARGA</th>
            <th>TOTAL BAYAR</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // --- CONTOH 1: Menggunakan mysqli_fetch_array() ---
    while ($row = mysqli_fetch_array($query)) {
        echo '<tr>
                <td>'.$row['id_produk'].'</td>
                <td>'.$row['tgl_transaksi'].'</td>
                <td>'.$row['kuantitas'].'</td>
                <td>'.$row['harga'].'</td>
                <td class="right">'.number_format($row['total_byr'], 0, ',', '.').'</td>
            </tr>';
    }
    ?>
    </tbody>
</table>

<?php
 
// --- CONTOH 2: Menggunakan mysqli_fetch_assoc() ---
/*
$query = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($query)) {
    echo '<tr>
            <td>'.$row['id_produk'].'</td>
            <td>'.$row['tgl_transaksi'].'</td>
            <td>'.number_format($row['harga'], 0, ',', '.').'</td>
            <td class="right">'.$row['kuantitas'].'</td>
        </tr>';
}
*/


// --- CONTOH 3: Menggunakan mysqli_fetch_row() (Berdasarkan Index Angka) ---
/*
$query = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_row($query)) {
    echo '<tr>
            <td>'.$row[0].'</td>
            <td>'.$row[1].'</td>
            <td>'.number_format($row[2], 0, ',', '.').'</td>
            <td class="right">'.$row[3].'</td>
        </tr>';
}
*/

mysqli_free_result($query);
mysqli_close($conn);
?>
</body>
</html>