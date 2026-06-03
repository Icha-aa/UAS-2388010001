<?php
$host = 'db';
$db   = 'kerudung_db';
$user = 'sitimalikha19';
$pass = 'secretpass';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    // Buat Tabel Users
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    )");

    // Buat Tabel Produk (Kerudung)
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id SERIAL PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        harga NUMERIC(10,2) NOT NULL,
        deskripsi TEXT,
        gambar VARCHAR(255)
    )");

    // Cek apakah user admin sudah ada, jika belum buat default admin
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO users (username, password) VALUES ('admin', '$hashedPassword')");
    }

    // Isikan data contoh kerudung jika kosong
    $stmtProd = $pdo->query("SELECT COUNT(*) FROM products");
    if ($stmtProd->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO products (nama, harga, deskripsi, gambar) VALUES 
            ('Pashmina Silk Premium', 75000, 'Bahan silk mewah, jatuh, lembut dan berkilau elegan.', 'pashmina.jpg'),
            ('Segiempat Voal Ultrafine', 55000, 'Voal premium tegak di dahi, tidak mendengung, adem seharian.', 'voal.jpg'),
            ('Khimar Ceruty Double Layer', 120000, 'Anggun dengan potongan double layer, syari dan tetap ringan.', 'khimar.jpg')");
    }

    echo "Database & Data Awal Berhasil Dibuat!";
} catch (PDOException $e) {
    echo "Gagal setup database: " . $e->getMessage();
}