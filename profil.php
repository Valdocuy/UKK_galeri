<?php
session_start();
include './proses/koneksi.php';

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

// Mengambil data user berdasarkan user_id dari session
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email, alamat, nama_lengkap FROM user WHERE id = '$user_id'"; // Ganti 'id' sesuai nama kolom ID yang tepat
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $email = $row['email'];
    $alamat = $row['alamat'];
    $nama_lengkap = $row['nama_lengkap'];
} else {
    echo "Pengguna tidak ditemukan!";
    exit();
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <style>
        /* Mengatur warna background navbar menjadi abu-abu */
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
        }
        /* Mengatur tampilan profil */
        .profile-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .profile-header {
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .profile-header h2 {
            margin: 0;
            font-size: 2rem;
        }
        .profile-info {
            font-size: 1rem;
            color: #555;
        }
        .profile-info p {
            margin: 10px 0;
        }
        .btn-edit-profile {
            font-size: 1rem;
            padding: 10px 20px;
            margin-top: 20px;
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

<!-- Tampilan Profil -->
<div class="container">
    <div class="profile-container">
        <div class="profile-header">
            <div>
                <h2> <?php echo htmlspecialchars($nama_lengkap); ?></h2>
                <div class="profile-info">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p><strong>Alamat:</strong> <?php echo htmlspecialchars($alamat); ?></p>
                    <p><strong>Nama Lengkap:</strong><?php echo htmlspecialchars($username); ?></p>
                </div>
            </div>
        </div>
        <button class="btn btn-primary btn-edit-profile" onclick="window.location.href='edit_profil.php'">Edit Profil</button>
    </div>
</div>

<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>
