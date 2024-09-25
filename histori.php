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

// Tentukan jumlah foto per halaman
$per_page = 4;

// Hitung jumlah total foto
$stmt_count = $conn->prepare("SELECT COUNT(*) as total FROM foto_history WHERE user_id = ?");
$stmt_count->bind_param("i", $_SESSION['user_id']);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$total_photos = $row_count['total'];
$stmt_count->close();

// Tentukan jumlah halaman
$total_pages = ceil($total_photos / $per_page);

// Tentukan halaman saat ini
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages) $current_page = $total_pages;

// Hitung offset
$offset = ($current_page - 1) * $per_page;

// Query untuk mengambil data foto yang sudah dihapus dengan limit dan offset
$stmt = $conn->prepare("SELECT * FROM foto_history WHERE user_id = ? LIMIT ?, ?");
$stmt->bind_param("iii", $_SESSION['user_id'], $offset, $per_page);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Foto</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .img-thumbnail {
            max-width: 80px;
            margin: 5px;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: bold;
        }
        table {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th {
            background-color: #007bff;
            color: white;
        }
        td {
            vertical-align: middle;
        }
        /* Menyesuaikan ukuran font di layar kecil */
        @media (max-width: 576px) {
            .table td, .table th {
                font-size: 12px;
                padding: 8px;
            }
            .img-thumbnail {
                max-width: 60px;
                margin: 2px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="./beranda_publik.php">Album Galeri Foto</a>
            <!-- Button untuk toggle navbar -->
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

    <div class="container mt-4">
    <!-- Tombol Hapus Semua -->
    <div class="d-flex justify-content-between mb-3">
        <a href="./proses/proses_hapus_semuahistori.php" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus semua foto dalam history?');">Hapus Semua</a>
    </div>


    <div class="container mt-4">
        <!-- Tabel dengan class table-responsive -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Caption</th>
                        <th>Tanggal Dihapus</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Menampilkan data foto yang sudah dihapus
                    if ($result->num_rows > 0) {
                        $no = $offset + 1; // Untuk nomor urut
                        while ($row = $result->fetch_assoc()) {
                            echo "
                            <tr>
                                <td>" . $no++ . "</td>
                                <td>
                                      <img src='./image/" . htmlspecialchars($row['lokasi_file']) . "' alt='Foto' class='img-thumbnail' onclick='openImageModal(\"./image/" . htmlspecialchars($row['lokasi_file']) . "\")'>
                                </td>
                                <td>" . htmlspecialchars($row['judul']) . "</td>
                                <td>" . htmlspecialchars($row['deskripsi']) . "</td>
                                <td>" . htmlspecialchars($row['caption']) . "</td>
                                <td>" . date('d F Y', strtotime($row['tanggal_hapus'])) . "</td>
                                <td>
                                    <a href='./proses/proses_pulihkan_fotoalbum.php?id=" . $row['id'] . "' class='btn btn-success btn-sm'>Pulihkan</a>
                                    <a href='./proses/proses_hapus_permanen_albumfoto.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Anda yakin ingin menghapus foto ini secara permanen?\");'>Hapus</a>
                                </td>
                            </tr>
                        ";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Tidak ada foto yang ditemukan dalam history.</td></tr>";
                    }

                    // Menutup statement dan koneksi
                    $stmt->close();
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($current_page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $current_page - 1 ?>"><<</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i === $current_page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $current_page + 1 ?>">>></a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <!-- Modal untuk Zoom Gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <img id="modalImage" src="" alt="Zoomed Image" class="img-fluid" style="border-radius: 10px;">
          </div>
        </div>
      </div>
    </div>

    <script src="./js/bootstrap.bundle.min.js"></script>
    <script>
    function openImageModal(src) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = src;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }
    </script>
</body>
</html>
