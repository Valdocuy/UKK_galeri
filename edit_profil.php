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
$sql = "SELECT username, email, alamat, nama_lengkap FROM user WHERE id = '$user_id'";
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
    <title>Edit Profil</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 15px;
        }
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        .form-title {
            margin-bottom: 20px;
            font-size: 1.75rem;
            font-weight: bold;
            text-align: center;
            color: #007bff;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-title">Edit Profil</div>
    <form id="editProfileForm" action="./proses/proses_edit_profil.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($nama_lengkap); ?>" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo htmlspecialchars($alamat); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
    <div class="mt-3 text-center">
        <small><a href="profil.php">Kembali ke Profil</a></small>
    </div>
</div>

<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>

