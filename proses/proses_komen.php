<?php
session_start();
include 'koneksi.php';

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

$user_id = $_SESSION['user_id'];
$foto_id = $_POST['foto_id'];
$isi_komentar = $_POST['isi_komentar'];

// Siapkan dan jalankan query untuk menyimpan komentar
$sql = "INSERT INTO komenfoto (foto_id, user_id, isi_komentar) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("iis", $foto_id, $user_id, $isi_komentar);
    if ($stmt->execute()) {
        header("Location: ../komen.php?foto_id=" . $foto_id); // Kembali ke halaman komentar
    } else {
        echo "Error: " . $stmt->error; // Tampilkan error jika eksekusi gagal
    }
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error; // Tampilkan error jika persiapan query gagal
}

$conn->close();
?>
