<?php
session_start(); // Memulai session

// Memastikan bahwa pengguna sudah login
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

// Ambil data album dari database milik pengguna yang sedang login
$sql = "SELECT a.id, a.nama_album, a.deskripsi, a.tanggal_dibuat, u.nama_lengkap 
        FROM album a 
        JOIN user u ON a.user_id = u.id 
        WHERE a.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

// Memastikan bahwa koneksi berhasil
if ($result === false) {
    echo "Error: " . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Album</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Mengatur warna background navbar menjadi putih */
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
        }

        /* Mengatur ukuran card */
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Mengatur tampilan tombol tambah album */
        .btn-add-album {
            margin-bottom: 20px;
        }

        /* Mengatur tampilan dropdown */
        .dropdown-menu {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .dropdown-item {
            color: black;
        }

        .dropdown-item:hover {
            background-color: #f1f1f1;
        }

        /* Gaya untuk tombol titik tiga */
        .dropdown-toggle::after {
            display: none; /* Menghilangkan panah default */
        }

        .dropdown-toggle {
            background-color: transparent;
            border: none;
            color: black; /* Warna titik tiga */
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
    </nav><br>

    <!-- Tombol untuk menambah album -->
    <div class="container">
        <button class="btn btn-success btn-add-album" data-bs-toggle="modal" data-bs-target="#addAlbumModal">+ Tambah Album</button>
    </div>

    <!-- Modal Tambah Album -->
    <div class="modal fade" id="addAlbumModal" tabindex="-1" aria-labelledby="addAlbumModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAlbumModalLabel">Tambah Album</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addAlbumForm" method="POST" action="./proses/proses_tambah_album.php">
                        <div class="mb-3">
                            <label for="albumName" class="form-label">Nama Album</label>
                            <input type="text" class="form-control" id="albumName" name="albumName" required>
                        </div>
                        <div class="mb-3">
                            <label for="albumDescription" class="form-label">Deskripsi Album</label>
                            <textarea class="form-control" id="albumDescription" name="albumDescription" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="albumDate" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="albumDate" name="albumDate" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Album</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian untuk menampilkan album -->
    <div class="container mt-1">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3">
            <?php
            // Loop untuk menampilkan setiap album dalam card
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '
                    <div class="col">
                        <div class="card mb-3">
                            <div class="dropdown" style="position: absolute; top: 10px; right: 10px;">
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="./edit_album.php?id=' . $row['id'] . '">Edit</a></li>
                                    <li><a class="dropdown-item" href="./proses/proses_hapus_album.php?id=' . $row['id'] . '" onclick="return confirm(\'Anda yakin ingin menghapus album ini?\');">Hapus</a></li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">' . htmlspecialchars($row['nama_album']) . '</h5>
                                <p class="album-description">' . htmlspecialchars($row['deskripsi']) . '</p>
                                <button class="btn btn-primary" onclick="window.location.href=\'album_foto.php?id=' . $row['id'] . '\'">Lihat Album</button>
                            </div>
                            <div class="card-footer">
                                <div class="album-info">
                                    <div class="album-user">Di-upload oleh: ' . htmlspecialchars($row['nama_lengkap']) . '</div>
                                    <div class="album-date">Tanggal dibuat: ' . date('d F Y', strtotime($row['tanggal_dibuat'])) . '</div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo "<p>Belum ada album yang dibuat.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        // Menetapkan tanggal saat ini ke input tanggal saat modal dibuka
        document.getElementById('addAlbumModal').addEventListener('show.bs.modal', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('albumDate').value = today;
        });
    </script>

    <script src="./js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
$conn->close();
?>
