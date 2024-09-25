<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus foto dari tabel foto_history secara permanen
    $stmt = $conn->prepare("DELETE FROM foto_history WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    // Eksekusi query
    if ($stmt->execute()) {
        // Jika berhasil, tampilkan alert sukses
        echo "<script>alert('Foto berhasil dihapus secara permanen!'); window.location.href = '../histori.php';</script>";
    } else {
        // Jika ada error, tampilkan alert error
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = '../histori.php';</script>";
    }

    // Tutup statement
    $stmt->close();
} else {
    // Jika ID foto tidak ditemukan
    echo "<script>alert('ID foto tidak ditemukan!'); window.location.href = '../histori.php';</script>";
}

// Tutup koneksi
$conn->close();
?>
