<?php
session_start(); // Memastikan session aktif

include 'koneksi.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $album_id = $_POST['album_id'];
    $nama_album = $_POST['albumName'];
    $deskripsi = $_POST['albumDescription'];
    $tanggal_dibuat = $_POST['albumDate'];

    // Pastikan semua input terisi
    if (!empty($nama_album) && !empty($deskripsi) && !empty($tanggal_dibuat)) {
        // Query untuk memperbarui data album
        $sql = "UPDATE album SET nama_album = ?, deskripsi = ?, tanggal_dibuat = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error pada prepare statement: " . $conn->error);
        }

        // Bind parameter ke query
        $stmt->bind_param("sssi", $nama_album, $deskripsi, $tanggal_dibuat, $album_id);

        // Eksekusi query
        if ($stmt->execute()) {
            // Jika berhasil, tampilkan alert sukses
            echo "<script>alert('Album berhasil diperbarui!'); window.location.href = '../album.php';</script>";
        } else {
            // Jika ada error, tampilkan alert error
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = '../album.php';</script>";
        }

        // Tutup statement
        $stmt->close();
    } else {
        // Jika ada input yang kosong, tampilkan alert bahwa semua kolom harus diisi
        echo "<script>alert('Semua kolom harus diisi!'); window.location.href = '../album.php';</script>";
    }

    // Tutup koneksi
    $conn->close();
} else {
    // Invalid request
    echo "<script>alert('Invalid request method!'); window.location.href = '../album.php';</script>";
    exit();
}
?>
