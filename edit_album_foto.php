<?php
session_start(); // Pastikan session dimulai

// Cek apakah user_id ada dalam session
if (!isset($_SESSION['user_id'])) {
    echo "
        <script>
            alert('Anda harus login terlebih dahulu!');
            window.location.href = 'login.php'; // Redirect ke halaman login jika belum login
        </script>
    ";
    exit();
}

include './proses/koneksi.php'; // Koneksi ke database

// Ambil id foto dari parameter URL
$foto_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Query untuk mengambil data foto berdasarkan id
$stmt = $conn->prepare("SELECT * FROM foto WHERE id = ?");
$stmt->bind_param("i", $foto_id);
$stmt->execute();
$result = $stmt->get_result();
$foto = $result->fetch_assoc();

if (!$foto) {
    echo "<script>alert('Foto tidak ditemukan.'); window.location.href = 'album.php';</script>";
    exit();
}

// Menutup statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Foto</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            max-width: 600px; /* Ukuran maksimum card */
            margin: auto; /* Pusatkan card di halaman */
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
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
    </nav>

    <div class="container">
        <div class="card mt-3">
            <div class="card-body">
                <form action="./proses/proses_edit_albumfoto.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $foto['id']; ?>">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul" value="<?php echo htmlspecialchars($foto['judul']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?php echo htmlspecialchars($foto['deskripsi']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="caption" class="form-label">Caption</label>
                        <input type="text" class="form-control" id="caption" name="caption" value="<?php echo htmlspecialchars($foto['caption']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="lokasi_file" class="form-label">Gambar</label><br>
                        <img src="./image/<?php echo htmlspecialchars($foto['lokasi_file']); ?>" alt="Foto" class="img-thumbnail" style="max-width: 150px; margin-bottom: 10px;">
                        <input type="file" class="form-control" id="lokasi_file" name="lokasi_file" accept="image/*">
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>
