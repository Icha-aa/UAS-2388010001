<?php
require_once 'src/config.php';
require_once 'src/db.php';
require_once 'src/helpers.php';

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Siti Hijab - Elegansi Lembut Setiap Hari</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">Siti Hijab</div>
    <nav>
        <a href="index.php">Beranda</a>
        <a href="login.php">Admin Panel</a>
    </nav>
</header>

<section class="hero">
    <h1>Temukan Kenyamanan Hijabmu</h1>
    <p>Koleksi Kerudung Premium dengan Warna Cantik, Lembut, dan Desain Elegan.</p>
</section>

<div class="container">
    <h2 style="text-align: center; margin-bottom: 40px;">Koleksi Terbaru</h2>
    <div class="grid-produk">
        <?php foreach ($products as $p): ?>
            <div class="card">
                <div style="background: #ffe4e1; height: 200px; display: flex; align-items: center; justify-content: center; color: #cc7a8a;">
                    [ Gambar <?php echo htmlspecialchars($p['nama']); ?> ]
                </div>
                <div class="card-body">
                    <h3 class="card-title"><?php echo htmlspecialchars($p['nama']); ?></h3>
                    <p style="font-size: 14px; color: #7f8c8d;"><?php echo htmlspecialchars($p['deskripsi']); ?></p>
                    <div class="price"><?php echo formatRupiah($p['harga']); ?></div>
                    <a href="https://wa.me/628123456789?text=Halo%20Siti%20Hijab,%20saya%20mau%20pesan%20<?php echo urlencode($p['nama']); ?>" class="btn-beli" target="_blank">Pesan via WhatsApp</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer>
    <p>&copy; 2026 Siti Hijab Store. All Rights Reserved.</p>
</footer>

</body>
</html>