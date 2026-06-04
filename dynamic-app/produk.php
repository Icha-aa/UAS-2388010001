<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
include 'src/db.php';

$stmt = $pdo->query("SELECT * FROM products");
$list_produk = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk - Malikha House</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">Malikha<span>House</span></div>
        <ul class="menu-group">
            <li class="menu-item"><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li class="menu-item active"><a href="produk.php"><i class="fa-solid fa-box"></i> Daftar Produk</a></li>
        </ul>
        <div class="user-profile">
            <span><?php echo $_SESSION['user']; ?></span>
        </div>
    </div>
    <div class="main-content">
        <h2>Manajemen Daftar Produk</h2>
        <a href="tambah_produk.php" class="btn-tambah">+ Tambah Produk</a>
        <table class="data-table">
            <thead>
                <tr><th>ID</th><th>Kode Produk</th><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach($list_produk as $p): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo $p['kode_produk']; ?></td>
                    <td><?php echo $p['nama_produk']; ?></td>
                    <td><?php echo $p['kategori']; ?></td>
                    <td>Rp <?php echo number_format($p['harga_jual'], 0, ',', '.'); ?></td>
                    <td><?php echo $p['stok']; ?></td>
                    <td><a href="edit_produk.php?id=<?php echo $p['id']; ?>">Edit</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>