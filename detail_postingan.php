<?php 
session_start();
include './proses/koneksi.php';

// Ambil ID foto dari query string
$foto_id = isset($_GET['foto_id']) ? (int)$_GET['foto_id'] : 0;

// Ambil detail foto dari database
$sql = "
    SELECT f.*, u.nama_lengkap 
    FROM foto f
    LEFT JOIN user u ON f.user_id = u.id
    WHERE f.id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $foto_id);
$stmt->execute();
$result = $stmt->get_result();
$foto = $result->fetch_assoc();

if (!$foto) {
    echo "Foto tidak ditemukan.";
    exit();
}

// Ambil jumlah like dan pengguna yang menyukai foto
$likeSql = "
    SELECT u.nama_lengkap 
    FROM likefoto l
    LEFT JOIN user u ON l.user_id = u.id
    WHERE l.foto_id = ?
";
$likeStmt = $conn->prepare($likeSql);
$likeStmt->bind_param("i", $foto_id);
$likeStmt->execute();
$likeResult = $likeStmt->get_result();
$likes = [];
while ($like = $likeResult->fetch_assoc()) {
    $likes[] = $like['nama_lengkap'];
}
$likeCount = count($likes);

// Ambil komentar dari database
$komenSql = "
    SELECT c.isi_komentar, c.tanggal_komen, u.nama_lengkap 
    FROM komenfoto c
    LEFT JOIN user u ON c.user_id = u.id
    WHERE c.foto_id = ?
    ORDER BY c.tanggal_komen DESC
";
$komenStmt = $conn->prepare($komenSql);
$komenStmt->bind_param("i", $foto_id);
$komenStmt->execute();
$komenResult = $komenStmt->get_result();
$komentar = [];
while ($komen = $komenResult->fetch_assoc()) {
    $komentar[] = $komen;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $foto['judul']; ?></title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="./beranda_publik.php">Album Galeri Foto</a>
  </div>
</nav>

<div class="container mt-5">
    <h1><?php echo $foto['judul']; ?></h1>
    <img src="./image/<?php echo $foto['lokasi_file']; ?>" alt="<?php echo $foto['caption']; ?>" class="img-fluid" onerror="this.onerror=null; this.src='./img/default-image.jpg';">
    <p><strong>Deskripsi:</strong> <?php echo $foto['deskripsi']; ?></p>
    <p><strong>Caption:</strong> <?php echo $foto['caption']; ?></p>
    <p><strong>Uploaded by:</strong> <?php echo $foto['nama_lengkap']; ?></p>
    <p><strong>Tanggal Unggah:</strong> <?php echo date('d M Y', strtotime($foto['tanggal_unggah'])); ?></p>

    <h5>Jumlah Like: <?php echo $likeCount; ?></h5>
    <h6>Yang menyukai:</h6>
    <ul>
        <?php foreach ($likes as $liker): ?>
            <li><?php echo $liker; ?></li>
        <?php endforeach; ?>
    </ul>

    <h5>Komentar:</h5>
    <ul>
        <?php foreach ($komentar as $komen): ?>
            <li><strong><?php echo $komen['nama_lengkap']; ?>:</strong> <?php echo $komen['isi_komentar']; ?> <em>(<?php echo date('d M Y H:i', strtotime($komen['tanggal_komen'])); ?>)</em></li>
        <?php endforeach; ?>
    </ul>


</div>

<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>
