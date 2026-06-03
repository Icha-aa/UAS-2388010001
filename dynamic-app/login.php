<?php
require_once 'src/config.php';
require_once 'src/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Siti Hijab</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-box { max-width: 400px; margin: 100px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn-submit { background: #ff69b4; color: white; border: none; width: 100%; padding: 10px; border-radius: 5px; cursor: pointer; font-weight: bold;}
    </style>
</head>
<body>
<div class="login-box">
    <h3 style="text-align: center; color: #ff69b4;">Admin Login</h3>
    <?php if ($error): ?><p style="color: red; text-align: center;"><?= $error ?></p><?php endif; ?>
    <form action="" method="POST">
        <div class="input-group">
            <label>Username (Gunakan: admin)</label>
            <input type="text" name="username" required>
        </div>
        <div class="input-group">
            <label>Password (Gunakan: admin123)</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn-submit">Masuk</button>
    </form>
</div>
</body>
</html>