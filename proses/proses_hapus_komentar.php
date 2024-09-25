<?php
session_start();
include './koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    echo "
        <script>
            alert('Anda harus login terlebih dahulu!');
            window.location.href = '../login.php';
        </script>
    ";
    exit();
}

$komentar_id = $_POST['komentar_id'];
$foto_id = $_POST['foto_id'];
$user_id = $_SESSION['user_id'];

// Pastikan komentar ini milik user yang login
$sql_check = "SELECT * FROM komenfoto WHERE id = ? AND user_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $komentar_id, $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // Jika komentar milik user, hapus dari database
    $sql_delete = "DELETE FROM komenfoto WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $komentar_id);
    if ($stmt_delete->execute()) {
        echo "
            <script>
                // alert('Komentar berhasil dihapus!');
                window.location.href = '../komen.php?foto_id=$foto_id';
            </script>
        ";
    } else {
        echo "
            <script>
                // alert('Gagal menghapus komentar.');
               window.location.href = '../komen.php?foto_id=$foto_id';
            </script>
        ";
    }
} else {
    echo "
        <script>
            // alert('Anda tidak memiliki izin untuk menghapus komentar ini.');
             window.location.href = '../komen.php?foto_id=$foto_id';
        </script>
    ";
}
?>
