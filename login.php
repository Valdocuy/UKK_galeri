<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    <div class="form-title">Masuk Akun</div>
    <form action="./proses/proses_login.php" method="POST" id="loginForm">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Masuk</button>
    </form>
    <div class="mt-3 text-center">
        <small>Belum punya akun? <a href="sing_up.php">Daftar</a></small>
    </div>
    <div class="mt-2 text-center">
        <small><a href="ganti_pw.php">Lupa Kata Sandi?</a></small>
    </div>
</div>

<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>
