<?php
session_start();
include 'koneksi.php'; // Sesuaikan dengan path koneksi database Anda

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi bahwa password baru dan konfirmasi password sama
    if ($new_password !== $confirm_password) {
        echo "
        <script>
            alert('Password baru dan konfirmasi password tidak sama!');
            window.history.back(); // Kembali ke halaman sebelumnya
        </script>
        ";
        exit();
    }

    // Hash password baru
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password di database
    $sql = "UPDATE user SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $username);

    if ($stmt->execute()) {
        echo "
        <script>
            alert('Password berhasil diubah!');
            window.location.href = '../login.php'; // Redirect ke halaman login
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Gagal mengubah password!');
            window.history.back(); // Kembali ke halaman sebelumnya
        </script>
        ";
    }

    // Menutup koneksi
    $stmt->close();
    $conn->close();
} else {
    echo "
    <script>
        alert('Metode tidak valid!');
        window.location.href = '../ganti_pw.php'; // Redirect kembali jika metode tidak valid
    </script>
    ";
}
?>
