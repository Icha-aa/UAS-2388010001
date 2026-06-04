<?php
include 'src/db.php';
// Ganti 'admin' dengan username yang kamu temukan di langkah 1
$username = 'admin'; 
$password_baru = password_hash('12345', PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
$stmt->execute([$password_baru, $username]);
echo "Password untuk '$username' sudah diubah jadi: 12345";
?>