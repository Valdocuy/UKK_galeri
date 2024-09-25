<?php
session_start();
include 'koneksi.php';

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

// Mengambil user_id dari session
$user_id = $_SESSION['user_id'];

// Memproses data form ketika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $alamat = $_POST['alamat'];

    // SQL untuk memperbarui data pengguna
    $sql = "UPDATE user SET username = ?, email = ?, nama_lengkap = ?, alamat = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $email, $nama_lengkap, $alamat, $user_id);

    if ($stmt->execute()) {
        echo "
        <script>
            alert('Profil berhasil diperbarui!');
            window.location.href = '../profil.php'; // Redirect ke halaman profil
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Terjadi kesalahan saat memperbarui profil: " . $stmt->error . "');
            window.history.back(); // Kembali ke halaman sebelumnya
        </script>
        ";
    }

    // Menutup statement dan koneksi
    $stmt->close();
    $conn->close();
}
?>
