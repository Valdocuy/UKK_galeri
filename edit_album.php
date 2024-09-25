<?php
session_start();
include './proses/koneksi.php'; // Koneksi ke database

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "<script>alert('Anda harus login terlebih dahulu!'); window.location.href = 'login.php';</script>";
    exit();
}

$album_id = $_GET['id'];

// Ambil data album dari database
$sql = "SELECT * FROM album WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $album_id);
$stmt->execute();
$result = $stmt->get_result();
$album = $result->fetch_assoc();

if (!$album) {
    echo "<script>alert('Album tidak ditemukan!'); window.location.href = 'album.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Album</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <style>
        /* Mengatur warna background navbar menjadi putih */
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="./beranda_publik.php">Album Galeri Foto</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./album.php">Kembali</a>
                </li>
            </ul>
        </div>
    </div>
</nav><br>

<div class="container mt-1">
    <div class="card">
        <div class="card-header">
            <h2>Edit Album</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="./proses/proses_edit_album.php">
                <input type="hidden" name="album_id" value="<?php echo $album['id']; ?>">
                <div class="mb-3">
                    <label for="albumName" class="form-label">Nama Album</label>
                    <input type="text" class="form-control" id="albumName" name="albumName" value="<?php echo htmlspecialchars($album['nama_album']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="albumDescription" class="form-label">Deskripsi Album</label>
                    <textarea class="form-control" id="albumDescription" name="albumDescription" required><?php echo htmlspecialchars($album['deskripsi']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="albumDate" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="albumDate" name="albumDate" value="<?php echo $album['tanggal_dibuat']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Album</button>
            </form>
        </div>
    </div>
</div>

<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>
