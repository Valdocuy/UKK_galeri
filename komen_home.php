<?php
session_start();
include './proses/koneksi.php';

// Cek apakah user_id ada dalam session
if (!isset($_SESSION['user_id'])) {
    echo "
        <script>
            alert('Anda harus login terlebih dahulu!');
            window.location.href = 'login.php';
        </script>
    ";
    exit();
}

$foto_id = $_GET['foto_id'];

// Ambil foto dari database
$sql_foto = "SELECT * FROM foto WHERE id = ?";
$stmt_foto = $conn->prepare($sql_foto);
$stmt_foto->bind_param("i", $foto_id);
$stmt_foto->execute();
$result_foto = $stmt_foto->get_result();
$foto = $result_foto->fetch_assoc();

// Ambil komentar dari database
$sql_komen = "SELECT k.*, u.username FROM komenfoto k JOIN user u ON k.user_id = u.id WHERE k.foto_id = ?";
$stmt_komen = $conn->prepare($sql_komen);
$stmt_komen->bind_param("i", $foto_id);
$stmt_komen->execute();
$result_komen = $stmt_komen->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komentar Foto</title>
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

        .container-custom {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            font-size: 0.85rem;
            /* Memperkecil font secara keseluruhan */
        }

        .foto-container {
            flex: 1;
            max-width: 30%;
        }

        .foto-container img {
            width: 100%;
            border-radius: 5px;
        }

        .komentar-container {
            flex: 2;
            max-width: 65%;
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-size: 0.85rem;
        }

        .komentar-item {
            border-bottom: 1px solid #e0e0e0;
            padding: 8px 0;
        }

        .komentar-item:last-child {
            border-bottom: none;
        }

        .komentar-item strong {
            color: #007bff;
        }

        .komentar-item small {
            color: #999;
            display: block;
            margin-top: 3px;
        }

        /* Atur tinggi maksimum daftar komentar */
        #komentar-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .form-control {
            margin-top: 5px;
            font-size: 0.85rem;
        }

        .btn-primary {
            font-size: 0.85rem;
            padding: 5px 10px;
        }

        .hapus-komentar {
            font-size: 0.75rem;
            /* Ukuran teks kecil */
            color: red;
            cursor: pointer;
            text-decoration: underline;
            border: none;
            background: none;
            padding: 0;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="./beranda_publik.php">Album Galeri Foto</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./beranda_publik.php">Kembali</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav><br>

    <div class="container mt-3 container-custom">
        <!-- Bagian Gambar -->
        <div class="foto-container">
            <img src="./image/<?php echo htmlspecialchars($foto['lokasi_file']); ?>" alt="Gambar" class="img-fluid">
        </div>

        <!-- Bagian Komentar -->
        <div class="komentar-container">
            <h4>Komentar:</h4>
            <div id="komentar-list mt-3">
                <?php while ($komen = $result_komen->fetch_assoc()): ?>
                    <div class="komentar-item">
                        <strong><?php echo htmlspecialchars($komen['username']); ?></strong>: <?php echo htmlspecialchars($komen['isi_komentar']); ?>
                        <small><?php echo date('d M Y H:i', strtotime($komen['tanggal_komen'])); ?></small>

                        <!-- Tombol hapus hanya muncul jika komentar milik pengguna yang login -->
                        <?php if ($komen['user_id'] == $_SESSION['user_id']): ?>
                            <form method="post" action="./proses/proses_hapus_komentar.php" style="display:inline;">
                                <input type="hidden" name="komentar_id" value="<?php echo $komen['id']; ?>">
                                <input type="hidden" name="foto_id" value="<?php echo $foto_id; ?>">
                                <button type="submit" class="hapus-komentar">Hapus</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Form Tambahkan Komentar -->
            <h6>Tambahkan Komentar:</h6>
            <form id="form-komen" method="post" action="./proses/proses_komen.php">
                <textarea name="isi_komentar" class="form-control" rows="2" required></textarea>
                <input type="hidden" name="foto_id" value="<?php echo $foto_id; ?>">
                <button type="submit" class="btn btn-primary mt-2">Kirim</button>
            </form>
        </div>
    </div>

    <script src="./js/bootstrap.bundle.min.js"></script>
</body>

</html>