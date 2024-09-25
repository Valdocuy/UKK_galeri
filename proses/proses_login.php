<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $sql = "SELECT id, username, password FROM user WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

     
        if (password_verify($password, $row['password'])) {
            
            $_SESSION['user_id'] = $row['id']; 
            echo "
            <script>
                alert('Login berhasil!');
                window.location.href = '../beranda_publik.php'; // Redirect ke dashboard atau halaman utama
            </script>
            ";
        } else {
            // Jika password salah
            echo "
            <script>
                alert('Password salah! Coba lagi.');
                window.history.back(); // Kembali ke halaman login
            </script>
            ";
        }
    } else {
        // Jika username tidak ditemukan
        echo "
        <script>
            alert('Username tidak ditemukan!');
            window.history.back(); // Kembali ke halaman login
        </script>
        ";
    }

    // Menutup koneksi
    $conn->close();
}
?>
