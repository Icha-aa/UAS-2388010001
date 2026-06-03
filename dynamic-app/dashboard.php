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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Malikha Fashion Store - Elegansi Lembut Setiap Hari</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">Malikha Fashion Store</div>
    <nav>
        <a href="index.php">Beranda</a>
        <a href="login.php">Admin Panel</a>
    </nav>
</header>

<div class="banner-grid-container">
    <div class="banner-left">
        <img src="https://images.unsplash.com/photo-1609357605129-26f69add5d6e?q=80&w=800&auto=format&fit=crop" alt="Hijab Premium">
        <div class="banner-text">
            <h2>SILK PREMIUM SERIES</h2>
            <p>Sentuhan Mewah & Berkilau Setiap Hari</p>
        </div>
    </div>
    <div class="banner-right-top">
        <img src="https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?q=80&w=800&auto=format&fit=crop" alt="Voal Pastel">
        <div class="banner-text">
            <h2>SOFT VOAL PASTEL</h2>
            <p>Nyamannya Seharian Penuh</p>
        </div>
    </div>
    <div class="banner-right-bottom">
        <img src="https://images.unsplash.com/photo-1618220179428-22790b461013?q=80&w=800&auto=format&fit=crop" alt="Pashmina Instant">
        <div class="banner-text">
            <h2>INSTANT PASHMINA</h2>
            <p>Tampil Rapi Hanya dalam 5 Detik</p>
        </div>
    </div>
</div>

<section class="about-section">
    <h2>Welcome to Malikha Fashion Store</h2>
    <p>Kami percaya bahwa menutup aurat adalah bentuk keindahan tertinggi seorang wanita Muslimah. Malikha Fashion Store hadir dengan konsep modern yang menyajikan koleksi hijab premium pilihan...</p>
</section>

<div class="container">
    <h2 class="section-title">Semua Koleksi Hijab</h2>
    <div class="grid-produk">
        <?php foreach ($products as $p): ?>
            <div class="card">
                <div class="card-img-wrapper">
                    <img src="https://picsum.photos/300/300?random=<?php echo $p['id']; ?>" alt="Hijab">
                </div>
                <div class="card-body">
                    <h3 class="card-title"><?php echo htmlspecialchars($p['nama'] ?? 'Produk'); ?></h3>
                    <p><?php echo htmlspecialchars($p['deskripsi'] ?? 'Deskripsi singkat'); ?></p>
                    <div class="price"><?php echo formatRupiah($p['harga'] ?? 0); ?></div>
                    <a href="https://wa.me/628123456789?text=Halo%20Malikha%20Fashion,%20saya%20mau%20pesan%20<?php echo urlencode($p['nama'] ?? 'Hijab'); ?>" class="btn-beli" target="_blank">Pesan via WhatsApp</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer>
    <p>&copy; 2026 Malikha Fashion Store. All Rights Reserved.</p>
</footer>

</body>
</html>