<?php
$host = 'db'; // Sesuaikan dengan nama service di docker-compose
$db   = 'kerudung_db';
$user = 'sitimalikha19';
$pass = 'secretpass';
$charset = 'utf8mb4';

// PASTIKAN hulu koneksinya tertulis "mysql:host=..." bukan "pgsql:host=..."
$dsn = "mysql:host=$host;dbname=$db;charset=$charset"; 
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}