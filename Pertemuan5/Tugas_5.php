<?php

$conn = mysqli_connect("127.0.0.1", "root", "", "latihan");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$table_name = 'mahasiswa';

$sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
	`NIM` int(5) NOT NULL,
	`Nama` varchar(20) NOT NULL,
	`Tugas` int(5) NOT NULL,
	`UTS` int(5) NOT NULL,
	`UAS` int(5) NOT NULL,
	PRIMARY KEY (`NIM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$query = mysqli_query($conn, $sql);

if (!$query) {
	die ('ERROR: Tabel ' . $table_name . ' gagal dibuat: ' . mysqli_error($conn));
}
echo 'Tabel ' . $table_name . ' berhasil dibuat <br/>';

$sql = "INSERT INTO `$table_name` (`NIM`, `Nama`, `Tugas`, `UTS`, `UAS`)
	VALUES (10001, 'Bagus', 85, 90, 88),
		(10002, 'Raja', 78, 80, 85),
		(10003, 'Rahmat', 90, 95, 92),
		(10004, 'Farhan', 70, 75, 80),
		(10005, 'Rasya', 88, 85, 90)
	ON DUPLICATE KEY UPDATE NIM=VALUES(NIM);";

$query = mysqli_query($conn, $sql);

if (!$query) {
	die ('ERROR: Data gagal dimasukkan pada tabel ' . $table_name . ' : ' . mysqli_error($conn));
}
echo 'Data berhasil dimasukkan pada tabel ' . $table_name . ' <br/><br/>';

$sql = 'SELECT NIM, Nama, Tugas, UTS, UAS, (Tugas + UTS + UAS) / 3 AS Nilai_Akhir
        FROM mahasiswa';

$query = mysqli_query($conn, $sql);

if (!$query) {
	die ('SQL Error: ' . mysqli_error($conn));
}
?>

<html>
<head>
    <title>Tugas Data Mahasiswa</title>
    <style>
        body { font-family: tahoma, arial; }
        table { border-collapse: collapse; margin-bottom: 20px; }
        th, td { font-size: 13px; border: 1px solid #DEDEDE; padding: 5px 8px; color: #303030; }
        th { background: #CCCCCC; font-size: 12px; border-color: #B0B0B0; }
        .right { text-align: right; }
    </style>
</head>
<body>

<h3>Tabel Data Mahasiswa</h3>
<table>
    <thead>
        <tr>
            <th>NIM</th>
            <th>NAMA</th>
            <th>TUGAS</th>
            <th>UTS</th>
            <th>UAS</th>
            <th>NILAI AKHIR</th>
        </tr>
    </thead>
    <tbody>
    <?php

    while ($row = mysqli_fetch_array($query)) {
        echo '<tr>
                <td>'.$row['NIM'].'</td>
                <td>'.$row['Nama'].'</td>
                <td class="right">'.$row['Tugas'].'</td>
                <td class="right">'.$row['UTS'].'</td>
                <td class="right">'.$row['UAS'].'</td>
                <td class="right">'.number_format($row['Nilai_Akhir'], 2, ',', '.').'</td>
            </tr>';
    }
    ?>
    </tbody>
</table>

<?php

mysqli_free_result($query);
mysqli_close($conn);
?>
</body>
</html>