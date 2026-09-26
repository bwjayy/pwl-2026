<!DOCTYPE html>
<html>
<head>
    <title>Portal Berita - Input Berita</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px 40px;
        }
        .navbar {
            margin-bottom: 30px;
        }
        .navbar a {
            text-decoration: none;
            margin-right: 20px;
        }
        .brand {
            font-size: 20px;
            font-weight: bold;
            color: #000;
        }
        .nav-link {
            color: #666;
            font-size: 15px;
        }
        .form-group {
            margin-bottom: 15px;
            width: 80%;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn-submit {
            background-color: #007bff;
            color: white;
            padding: 8px 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <span class="brand">Portal Berita</span>
        <span class="nav-link">Home</span>
        <a href="input.php" class="nav-link" style="color: #000; font-weight: bold;">Input Berita</a>
    </div>

    <h2>Input Berita</h2>

    <form action="insert.php" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label>Judul Berita:</label>
            <input type="text" name="judul" required>
        </div>

        <div class="form-group">
            <label>Gambar:</label>
            <input type="file" name="gambar" accept="image/*" required>
        </div>

        <div class="form-group">
            <label>Isi Berita:</label>
            <textarea name="isi" rows="8" required></textarea>
        </div>

        <div class="form-group">
            <label>Penulis:</label>
            <input type="text" name="penulis" required>
        </div>

        <div class="form-group">
            <label>Tanggal:</label>
            <input type="date" name="tanggal" required>
        </div>

        <button type="submit" name="submit" class="btn-submit">Submit</button>

    </form>

</body>
</html>