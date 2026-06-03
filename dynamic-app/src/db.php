<?php
$host = 'db';
$db   = 'kerudung_db';
$user = 'sitimalikha19';
$pass = 'secretpass';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>