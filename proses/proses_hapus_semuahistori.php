<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include 'koneksi.php';

// Hapus semua foto dari history untuk user yang sedang login
$stmt = $conn->prepare("DELETE FROM foto_history WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->close();

// Redirect kembali ke halaman history
header("Location: ../histori.php");
exit();
?>
