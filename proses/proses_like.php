<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Anda harus login terlebih dahulu!"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$foto_id = $_POST['foto_id'];

// Cek apakah sudah like foto ini
$sql = "SELECT * FROM likefoto WHERE foto_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $foto_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Jika belum, tambahkan like
    $sql = "INSERT INTO likefoto (foto_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $foto_id, $user_id);
    $stmt->execute();

    // Tambahkan 1 ke jumlah like di tabel foto
    $sql = "UPDATE foto SET jumlah_like = jumlah_like + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $foto_id);
    $stmt->execute();

    echo json_encode(["action" => "liked"]);
} else {
    // Jika sudah, hapus like (dislike)
    $sql = "DELETE FROM likefoto WHERE foto_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $foto_id, $user_id);
    $stmt->execute();

    // Kurangi 1 dari jumlah like di tabel foto
    $sql = "UPDATE foto SET jumlah_like = jumlah_like - 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $foto_id);
    $stmt->execute();

    echo json_encode(["action" => "disliked"]);
}

$stmt->close();
$conn->close();
?>
