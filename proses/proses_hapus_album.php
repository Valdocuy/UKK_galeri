<?php
session_start(); // Memastikan session aktif

include 'koneksi.php'; // Koneksi ke database

if (isset($_GET['id'])) {
    $album_id = $_GET['id'];

    // Pertama, ambil semua foto yang terkait dengan album
    $sql = "SELECT * FROM foto WHERE album_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $album_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Pindahkan foto ke tabel foto_history
    while ($foto = $result->fetch_assoc()) {
        // Masukkan foto ke tabel foto_history
        $stmt_history = $conn->prepare("INSERT INTO foto_history (album_id, user_id, judul, deskripsi, caption, tanggal_hapus, lokasi_file) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_history->bind_param("iisssss", $foto['album_id'], $foto['user_id'], $foto['judul'], $foto['deskripsi'], $foto['caption'], date('Y-m-d H:i:s'), $foto['lokasi_file']);
        $stmt_history->execute();
        $stmt_history->close();
    }

    // Tutup statement untuk foto
    $stmt->close();

    // Hapus foto dari tabel foto
    $sql_delete_foto = "DELETE FROM foto WHERE album_id = ?";
    $stmt_delete_foto = $conn->prepare($sql_delete_foto);
    $stmt_delete_foto->bind_param("i", $album_id);
    $stmt_delete_foto->execute();
    $stmt_delete_foto->close();

    // Hapus album dari tabel album
    $sql_delete_album = "DELETE FROM album WHERE id = ?";
    $stmt_delete_album = $conn->prepare($sql_delete_album);
    $stmt_delete_album->bind_param("i", $album_id);

    if ($stmt_delete_album->execute()) {
        // Jika berhasil, tampilkan alert sukses
        echo "<script>alert('Album dan foto terkait berhasil dihapus!'); window.location.href = '../album.php';</script>";
    } else {
        // Jika ada error, tampilkan alert error
        echo "<script>alert('Error: " . $stmt_delete_album->error . "'); window.location.href = '../album.php';</script>";
    }

    // Tutup statement untuk album
    $stmt_delete_album->close();
} else {
    // Jika ID album tidak ditemukan
    echo "<script>alert('ID album tidak ditemukan!'); window.location.href = '../album.php';</script>";
}

// Tutup koneksi
$conn->close();
?>
