<?php
$host = getenv('DB_HOST') ?: 'db-webdinamis';
$db   = getenv('DB_NAME') ?: 'kerudung_db';
$user = getenv('DB_USER') ?: 'sitimalikha19';
$pass = getenv('DB_PASSWORD') ?: 'secretpass';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
