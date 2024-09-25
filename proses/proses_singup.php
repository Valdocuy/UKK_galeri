<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password untuk keamanan
    $email = $_POST['email'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $alamat = $_POST['alamat'];

    // SQL untuk memasukkan data ke tabel user
    $sql = "INSERT INTO user (username, password, email, nama_lengkap, alamat) 
            VALUES ('$username', '$password', '$email', '$nama_lengkap', '$alamat')";

    if ($conn->query($sql) === TRUE) {
        // Menampilkan alert sukses dan mengarahkan ke login.php
        echo "
        <script>
            alert('Pendaftaran Berhasil! Akun Anda telah berhasil dibuat.');
            window.location.href = '../login.php'; // Redirect ke halaman login
        </script>
        ";
    } else {
        // Menampilkan alert gagal
        echo "
        <script>
            alert('Pendaftaran Gagal! Terjadi kesalahan saat mendaftarkan akun. Coba lagi.');
            window.history.back(); // Mengembalikan ke halaman sebelumnya
        </script>
        ";
    }

    // Menutup koneksi
    $conn->close();
}
?>
