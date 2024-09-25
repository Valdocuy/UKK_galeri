<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password Baru</title>
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
    <div class="form-title">Ganti Password Baru</div>
    <form action="./proses/proses_update_pw.php" method="POST">
        <input type="hidden" name="username" value="<?php echo htmlspecialchars($_GET['username']); ?>">
        <div class="mb-3">
            <label for="new_password" class="form-label">Password Baru</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Ganti Password</button>
    </form>
</div>

<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>
