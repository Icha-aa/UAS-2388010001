<?php
session_start();
include 'src/db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek user di database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Verifikasi password (asumsi password di DB tidak di-hash atau menggunakan md5)
    // Jika menggunakan password_hash, gunakan password_verify()
    if ($user && ($password == $user['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['user'] = $user['nama_petugas'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - CoreSystem</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { justify-content: center; align-items: center; height: 100vh; background: #f9f8f6; }
        .login-card { background: white; padding: 40px; border-radius: 16px; border: 1px solid #ededeb; width: 100%; max-width: 400px; text-align: center; }
        .login-card h2 { margin-bottom: 20px; color: var(--accent-brown); }
        .form-group { text-align: left; margin-bottom: 15px; }
        .btn-login { width: 100%; background: var(--accent-brown); color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .error { color: #c94a4a; font-size: 0.85rem; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Login CoreSystem</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
            </div>
            <button type="submit" name="login" class="btn-login">Masuk Sistem</button>
        </form>
    </div>
</body>
</html>