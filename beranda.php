<?php 
session_start();
include './proses/koneksi.php'; // Koneksi ke database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php"); // Arahkan ke halaman login jika belum login
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Ambil semua foto dari database yang diupload oleh pengguna yang sedang login
$sql = "
    SELECT f.*, u.nama_lengkap, 
           (SELECT COUNT(*) FROM likefoto l WHERE l.foto_id = f.id) AS total_likes, 
           (SELECT COUNT(*) FROM komenfoto c WHERE c.foto_id = f.id) AS total_comments
    FROM foto f
    LEFT JOIN user u ON f.user_id = u.id
    WHERE f.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Mengikat parameter user_id
$stmt->execute();
$result = $stmt->get_result();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Foto</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: bold;
        }
        body {
            background-color: #f5f5f5;
        }
        .gallery-container {
            padding: 20px 0;
        }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        @media (max-width: 992px) {
            .gallery-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 576px) {
            .gallery-grid {
                grid-template-columns: 1fr;
            }
        }
        .gallery-item {
            position: relative;
            overflow: hidden;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .gallery-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .gallery-item img {
            width: 100%;
            height: 150px; 
            object-fit: cover;
        }
        .gallery-info {
            padding: 10px;
        }
        .gallery-info h5 {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .gallery-info p {
            font-size: 0.85rem;
            color: #666;
        }
        .gallery-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-top: 1px solid #eee;
            background-color: #fafafa;
        }
        .gallery-footer .upload-info {
            font-size: 0.8rem;
            color: #333;
        }
        .user-info {
            font-size: 0.75rem;
            color: #333;
            margin-top: 5px;
        }
        .gallery-footer .icon {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .gallery-footer i {
            font-size: 1.2rem;
            cursor: pointer;
            transition: color 0.2s;
        }
        .gallery-footer i:hover {
            color: #ff6666;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="./beranda_publik.php">Album Galeri Foto</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="./beranda.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="./album.php">Album</a></li>
        <li class="nav-item"><a class="nav-link" href="./postingan.php">Up Foto</a></li>
        <li class="nav-item"><a class="nav-link" href="./profil.php">Profil</a></li>
        <li class="nav-item"><a class="nav-link" href="./histori.php">History</a></li>
        <li class="nav-item"><a class="nav-link" href="./keluar.php">Keluar</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container gallery-container">
    <input type="text" id="searchInput" placeholder="Cari caption..." class="form-control mb-4" oninput="filterGallery()">

    <div class="gallery-grid" id="galleryGrid">
        <?php while ($foto = $result->fetch_assoc()): ?>
        <div class="gallery-item" data-caption="<?php echo strtolower($foto['caption']); ?>">
        <img src="./image/<?php echo $foto['lokasi_file']; ?>" alt="Gambar" onclick="openImageModal(this.src)" onerror="this.onerror=null; this.src='./img/default-image.jpg';">
            <div class="gallery-info">
                <h5><?php echo $foto['judul']; ?></h5>
                <p><?php echo $foto['caption']; ?></p>
                <div class="user-info">Uploaded by: <?php echo $foto['nama_lengkap']; ?></div>
            </div>
            <div class="gallery-footer">
                <div class="upload-info"><?php echo date('d M Y', strtotime($foto['tanggal_unggah'])); ?></div>
                <div class="icon">
                    <i class="fas fa-heart" onclick="likeFoto(<?php echo $foto['id']; ?>)"></i> <?php echo $foto['total_likes']; ?>
                    <i class="fas fa-comment" onclick="window.location.href='./komen.php?foto_id=<?php echo $foto['id']; ?>'"></i> <?php echo $foto['total_comments']; ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Modal untuk Zoom Gambar -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img id="modalImage" src="" alt="Zoomed Image" class="img-fluid" style="border-radius: 10px;">
      </div>
    </div>
  </div>
</div>

<script src="./js/bootstrap.bundle.min.js"></script>
<script>
function filterGallery() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const galleryItems = document.querySelectorAll('.gallery-item');

    galleryItems.forEach(item => {
        const caption = item.getAttribute('data-caption');
        item.style.display = caption.includes(filter) ? "" : "none";
    });
}

function likeFoto(fotoId) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./proses/proses_like.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            location.reload(); // Reload halaman untuk memperbarui jumlah like
        }
    };
    xhr.send("foto_id=" + fotoId);
}


function openImageModal(src) {
    const modalImage = document.getElementById('modalImage');
    modalImage.src = src;
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}
</script>
</body>
</html>