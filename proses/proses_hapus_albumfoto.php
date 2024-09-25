<?php
session_start(); // Memastikan session aktif
include 'koneksi.php'; // Koneksi ke database

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data foto berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM foto WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $foto = $result->fetch_assoc();
    $stmt->close();

    if ($foto) {
        // Debugging: Tampilkan album_id yang diambil

        // Hapus semua likes dan comments terkait foto
        $stmt_delete_likes = $conn->prepare("DELETE FROM likefoto WHERE foto_id = ?");
        $stmt_delete_likes->bind_param("i", $id);
        $stmt_delete_likes->execute();
        $stmt_delete_likes->close();

        $stmt_delete_comments = $conn->prepare("DELETE FROM komenfoto WHERE foto_id = ?");
        $stmt_delete_comments->bind_param("i", $id);
        $stmt_delete_comments->execute();
        $stmt_delete_comments->close();

        // Masukkan foto ke tabel foto_history
        $stmt_history = $conn->prepare("INSERT INTO foto_history (album_id, user_id, judul, deskripsi, caption, tanggal_hapus, lokasi_file) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $tanggal_hapus = date('Y-m-d H:i:s');
        $stmt_history->bind_param("iisssss", $foto['album_id'], $foto['user_id'], $foto['judul'], $foto['deskripsi'], $foto['caption'], $tanggal_hapus, $foto['lokasi_file']);
        
        if ($stmt_history->execute()) {
            // Hapus foto dari tabel foto
            $stmt_delete = $conn->prepare("DELETE FROM foto WHERE id = ?");
            $stmt_delete->bind_param("i", $id);
            $stmt_delete->execute();
            $stmt_delete->close();

            echo "<script>alert('Foto berhasil dipindahkan ke history!'); window.location.href = '../album.php';</script>";
        } else {
            echo "<script>alert('Error saat memasukkan ke history: " . $stmt_history->error . "'); window.location.href = '../album.php';</script>";
        }
        $stmt_history->close();
    } else {
        echo "<script>alert('Foto tidak ditemukan!'); window.location.href = '../album.php';</script>";
    }
} else {
    echo "<script>alert('ID foto tidak ditemukan!'); window.location.href = '../album.php';</script>";
}

// Tutup koneksi
$conn->close();

?>
