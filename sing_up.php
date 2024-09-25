<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 15px;
        }
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-title {
            margin-bottom: 20px;
            font-size: 1.75rem;
            font-weight: bold;
            text-align: center;
            color: #007bff;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-title">Daftar Akun</div>
    <form id="signUpForm" action="./proses/proses_singup.php" method="POST">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
    </div>
    <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Daftar</button>
</form>

    <div class="mt-3 text-center">
        <small>Sudah punya akun? <a href="login.php">Masuk</a></small>
    </div>
</div>

<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>
