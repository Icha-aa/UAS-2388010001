<?php
session_start();
include 'src/db.php';
// ... (tambahkan logika query data produk di sini) ...
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Produk - Malikha House</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">Malikha<span>House</span></div>
        <ul class="menu-group">
            <li class="menu-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="menu-item active"><a href="produk.php">Daftar Produk</a></li>
        </ul>
        <div class="user-profile">
            <div class="user-avatar"><?php echo substr($_SESSION['user'], 0, 2); ?></div>
            <span><?php echo $_SESSION['user']; ?></span>
        </div>
    </div>

    <div class="main-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Manajemen Daftar Produk</h2>
            <a href="tambah_produk.php" class="btn-tambah">+ Tambah Produk</a>
        </div>
        <div class="card-table">
            </div>
    </div>
</body>
</html>