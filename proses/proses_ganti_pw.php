<?php
session_start();
include 'koneksi.php'; // Pastikan untuk mengubah path ini sesuai dengan struktur folder Anda

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Cek apakah username dan email ada di database
    $sql = "SELECT * FROM user WHERE username = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika ada, redirect ke halaman untuk mengganti password
        header("Location: ../ganti_pw1.php?username=" . urlencode($username));
        exit();
    } else {
        echo "
        <script>
            alert('Username atau Email tidak ditemukan!');
            window.history.back(); // Kembali ke halaman sebelumnya
        </script>
        ";
    }

    // Menutup koneksi
    $stmt->close();
    $conn->close();
}
?>
