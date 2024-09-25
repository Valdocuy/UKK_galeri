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

// Ambil album_id dari parameter URL
$album_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Pagination
$limit = 4; // Jumlah foto per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query untuk menghitung total foto
$totalStmt = $conn->prepare("SELECT COUNT(*) FROM foto WHERE album_id = ?");
$totalStmt->bind_param("i", $album_id);
$totalStmt->execute();
$totalStmt->bind_result($totalPhotos);
$totalStmt->fetch();
$totalStmt->close();

$totalPages = ceil($totalPhotos / $limit);

// Query untuk mengambil data foto berdasarkan album_id dengan pagination
$stmt = $conn->prepare("SELECT * FROM foto WHERE album_id = ? LIMIT ? OFFSET ?");
$stmt->bind_param("iii", $album_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foto Album</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .img-thumbnail {
            max-width: 100px;
            margin: 10px;
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

        /* Media query untuk tampilan mobile */
        @media (max-width: 768px) {
            .img-thumbnail {
                max-width: 70px; /* Mengurangi ukuran gambar pada layar ponsel */
            }

            table {
                font-size: 0.8rem; /* Memperkecil font di layar kecil */
            }

            th, td {
                padding: 5px; /* Mengurangi padding di layar kecil */
            }

            .pagination {
                font-size: 0.8rem; /* Memperkecil pagination */
            }
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

    <div class="container mt-4">
        <!-- Tabel responsif -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Caption</th>
                        <th>Tanggal Unggah</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Menampilkan data foto dalam album
                    if ($result->num_rows > 0) {
                        $no = $offset + 1; // Untuk nomor urut
                        while ($row = $result->fetch_assoc()) {
                            echo "
                            <tr>
                                <td>" . $no++ . "</td>
                                <td>
                                    <img src='./image/" . htmlspecialchars($row['lokasi_file']) . "' alt='Foto' class='img-thumbnail' data-bs-toggle='modal' data-bs-target='#photoModal' onclick='showPhoto(\"./image/" . htmlspecialchars($row['lokasi_file']) . "\")'>
                                </td>
                                <td>" . htmlspecialchars($row['judul']) . "</td>
                                <td>" . htmlspecialchars($row['deskripsi']) . "</td>
                                <td>" . htmlspecialchars($row['caption']) . "</td>
                                <td>" . date('d F Y', strtotime($row['tanggal_unggah'])) . "</td>
                                <td>
                                    <a href='./edit_album_foto.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='./proses/proses_hapus_albumfoto.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Anda yakin ingin menghapus foto ini?\");'>Hapus</a>
                                </td>
                            </tr>
                        ";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Tidak ada foto yang ditemukan.</td></tr>";
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
                <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?id=<?php echo $album_id; ?>&page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
                </li>
                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?id=<?php echo $album_id; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php if($page >= $totalPages) echo 'disabled'; ?>">
                    <a class="page-link" href="?id=<?php echo $album_id; ?>&page=<?php echo $page + 1; ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Foto" class="img-fluid" style="max-width: 70%; height: auto;">
                </div>
            </div>
        </div>
    </div>

    <script src="./js/bootstrap.bundle.min.js"></script>
    <script>
        function showPhoto(src) {
            document.getElementById('modalImage').src = src;
        }
    </script>
</body>
</html>

