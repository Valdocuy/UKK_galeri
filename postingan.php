<?php
session_start(); 


if (!isset($_SESSION['user_id'])) {
    echo "
        <script>
            alert('Anda harus login terlebih dahulu!');
            window.location.href = '../login.php'; // Redirect ke halaman login jika belum login
        </script>
    ";
    exit();
}

include './proses/koneksi.php'; 

$user_id = $_SESSION['user_id'];

// Mengambil daftar album dari database yang sesuai dengan user_id
$sql = "SELECT id, nama_album FROM album WHERE user_id = ?"; 
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


$albums = [];
while ($row = $result->fetch_assoc()) {
    $albums[] = $row; // Menyimpan setiap album ke dalam array
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galery</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <style>
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            padding: 20px;
            max-width: 900px; /* Lebar maksimum card */
            margin: auto; /* Memposisikan card di tengah */
            margin-top: 20px; /* Jarak atas untuk memberi ruang */
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="./beranda_publik.php">Album Galer Foto</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="./beranda.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./album.php">Album</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./postingan.php">Up Foto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./profil.php">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./histori.php">History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./keluar.php">Keluar</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="card">
        <h2 class="mb-4">Tambah Foto</h2>
        <form action="./proses/proses_posting_foto.php" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col">
                    <label for="album" class="form-label">Pilih Album</label>
                    <select class="form-select" id="album" name="album_id" required>
                        <option value="">Pilih Album</option>
                        <?php

                        foreach ($albums as $album) {
                            echo "<option value='{$album['id']}'>{$album['nama_album']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col">
                    <label for="judul" class="form-label">Judul Foto</label>
                    <input type="text" class="form-control" id="judul" name="judul" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="1" required></textarea>
                </div>
                <div class="col">
                    <label for="caption" class="form-label">Caption</label>
                    <input type="text" class="form-control" id="caption" name="caption" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="tanggal" class="form-label">Tanggal Unggah</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal_unggah" required>
                </div>
                <div class="col">
                    <label for="foto" class="form-label">Upload Foto</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Foto</button>
        </form>
    </div>
</div>

<script src="./js/bootstrap.bundle.min.js"></script>
<script>

    document.addEventListener("DOMContentLoaded", function() {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById("tanggal").value = today;
    });
</script>
</body>
</html>

<?php

$stmt->close();
$conn->close();
?>
