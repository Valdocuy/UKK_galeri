<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $album_id = $_POST['album_id'];
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $caption = $_POST['caption'];
    $tanggal_unggah = $_POST['tanggal_unggah'];

    // Proses upload foto
    $target_dir = "../image/"; // Folder penyimpanan foto
    $target_file = $target_dir . basename($_FILES["foto"]["name"]);
    move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);

    // Ambil user_id dari session atau sumber lain
    session_start();
    $user_id = $_SESSION['user_id']; // Pastikan user_id sudah ada dalam session

    // Query untuk menyimpan data ke tabel foto
    $stmt = $conn->prepare("INSERT INTO foto (album_id, user_id, judul, deskripsi, caption, tanggal_unggah, lokasi_file) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssss", $album_id, $user_id, $judul, $deskripsi, $caption, $tanggal_unggah, $target_file);

    if ($stmt->execute()) {
        echo "<script>alert('Foto berhasil disimpan.'); window.location.href='../postingan.php';</script>"; // Ganti halaman_anda.php sesuai kebutuhan
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='../postingan.php';</script>"; // Ganti halaman_anda.php sesuai kebutuhan
    }

    $stmt->close();
}
$conn->close();
?>
