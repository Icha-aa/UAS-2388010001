<?php
session_start();
include 'src/db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Sesuaikan: jika di DB belum di-hash, pakai $password == $user['password']
    // Jika sudah di-hash, pakai password_verify($password, $user['password'])
    if ($user && ($password == $user['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['user'] = $user['nama_petugas'];
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CoreSystem</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="justify-content: center; align-items: center; height: 100vh; display: flex;">

    <div class="login-card" style="background: white; padding: 40px; border-radius: 16px; border: 1px solid #ededeb; width: 100%; max-width: 400px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="margin-bottom: 25px; color: #8e7355; text-align: center;">Login CoreSystem</h2>
        
        <?php if(isset($error)) echo "<p style='color: #c94a4a; font-size: 0.85rem; margin-bottom: 15px; text-align: center;'>$error</p>"; ?>
        
        <form method="POST">
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Username</label>
                <input type="text" name="username" required style="width: 100%; padding: 12px; border: 1px solid #ededeb; border-radius: 8px;">
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid #ededeb; border-radius: 8px;">
            </div>
            <button type="submit" name="login" style="width: 100%; background: #8e7355; color: white; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Masuk Sistem</button>
        </form>
    </div>

</body>
</html>