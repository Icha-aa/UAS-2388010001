<?php
require_once 'src/config.php';
require_once 'src/db.php';
require_once 'src/helpers.php';
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Malikha Fashion Store</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">MALIKHA</div>
    <nav>
        <a href="#home">HOME</a>
        <a href="#katalog">KATALOG</a>
        <a href="#tentang">TENTANG</a>
        <a href="login.php">ADMIN</a>
    </nav>
</header>

<div class="hero-grid" id="home">
    <div class="hero-main"></div>
    <div class="hero-sub1"></div>
    <div class="hero-sub2"></div>
</div>

<section class="about" id="tentang">
    <div class="container">
        <h2 class="title">TENTANG KAMI</h2>
        <p>Malikha Fashion menyediakan koleksi hijab premium dengan bahan pilihan yang nyaman, lembut, dan elegan untuk setiap momen spesialmu.</p>
    </div>
</section>

<div class="container" id="katalog">
    <h2 class="title">KATALOG PRODUK</h2>
    <div class="grid-produk">
        <?php foreach ($products as $p): ?>
            <div class="card">
                <div class="card-img"><img src="https://picsum.photos/400/400?random=<?php echo $p['id']; ?>"></div>
                <div class="card-info">
                    <h3><?php echo htmlspecialchars($p['nama'] ?? 'Hijab'); ?></h3>
                    <p class="price"><?php echo formatRupiah($p['harga'] ?? 0); ?></p>
                    <a href="#" class="btn-pink">PESAN SEKARANG</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer id="kontak">
    <div class="footer-content">
        <h3>MALIKHA FASHION STORE</h3>
        <p>Email: hi@malikha.com | Instagram: @malikha.store</p>
        <p>&copy; 2026 Malikha Fashion Store. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>