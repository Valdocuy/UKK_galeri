<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data foto dari tabel foto_history
    $stmt = $conn->prepare("SELECT * FROM foto_history WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $foto = $result->fetch_assoc();
    $stmt->close();

    if ($foto) {
        // Masukkan foto ke tabel foto, pastikan semua kolom yang diperlukan terisi
        $stmt = $conn->prepare("INSERT INTO foto (album_id, user_id, judul, deskripsi, caption, tanggal_unggah, lokasi_file) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $foto['album_id'], $foto['user_id'], $foto['judul'], $foto['deskripsi'], $foto['caption'], $foto['tanggal_hapus'], $foto['lokasi_file']);
        
        // Cek jika ada error saat eksekusi
        if ($stmt->execute()) {
            // Hapus dari tabel foto_history
            $stmt->close();
            $stmt = $conn->prepare("DELETE FROM foto_history WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            // Menampilkan alert berhasil
            echo "<script>alert('Foto berhasil dipulihkan!'); window.location.href = '../histori.php';</script>";
        } else {
            echo "<script>alert('Error saat memulihkan foto: " . $stmt->error . "'); window.location.href = '../histori.php';</script>";
        }
    } else {
        echo "<script>alert('Foto tidak ditemukan!'); window.location.href = '../histori.php';</script>";
    }
} else {
    echo "<script>alert('ID foto tidak ditemukan!'); window.location.href = '../histori.php';</script>";
}

// Tutup koneksi
$conn->close();
?>
