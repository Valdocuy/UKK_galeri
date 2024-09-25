<?php
session_start(); // Pastikan session aktif
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Jika belum login, redirect ke login
    exit();
}

// Jika tombol keluar ditekan
if (isset($_POST['keluar'])) {
    session_destroy(); // Hapus session
    header("Location: index.php"); // Redirect ke halaman login
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keluar</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script>
        window.onload = function() {
            var modal = new bootstrap.Modal(document.getElementById('keluarModal'));
            modal.show();
        };
    </script>
</head>
<body>

    <!-- Modal -->
    <div class="modal fade" id="keluarModal" tabindex="-1" aria-labelledby="keluarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="keluarModalLabel">Konfirmasi Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin keluar dari akun Anda?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='./beranda_publik.php'">Batal</button>
                    <form id="keluarForm" method="POST" action="">
                        <button type="submit" name="keluar" class="btn btn-danger">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
