<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $caption = $_POST['caption'];
    $lokasi_file = $_FILES['lokasi_file']['name'];

    // Cek jika file diupload
    if ($lokasi_file) {
        $target_dir = "../image/";
        $target_file = $target_dir . basename($lokasi_file);
        move_uploaded_file($_FILES['lokasi_file']['tmp_name'], $target_file);
        $stmt = $conn->prepare("UPDATE foto SET judul = ?, deskripsi = ?, caption = ?, lokasi_file = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $judul, $deskripsi, $caption, $lokasi_file, $id);
    } else {
        $stmt = $conn->prepare("UPDATE foto SET judul = ?, deskripsi = ?, caption = ? WHERE id = ?");
        $stmt->bind_param("sssi", $judul, $deskripsi, $caption, $id);
    }

    // Eksekusi dan tutup
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Tambahkan alert dan redirect
    echo "
        <script>
            alert('Data berhasil diperbarui!');
            window.location.href = '../album.php';
        </script>
    ";
    exit();
}
?>
