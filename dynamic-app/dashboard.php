<?php
session_start();
include 'src/db.php'; // Pastikan path ke db.php benar

// Mengambil data statistik secara dinamis
$total_produk = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_stok = $pdo->query("SELECT SUM(stok) FROM products")->fetchColumn();
$total_user = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Mengambil data user
$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Malikha House</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="sidebar-brand">Malikha<span>House</span></div>
            <ul class="menu-group">
                <li class="menu-item active"><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                <li class="menu-item"><a href="users.php"><i class="fa-solid fa-users"></i> Manajemen User</a></li>
                <li class="menu-item"><a href="produk.php"><i class="fa-solid fa-box"></i> Daftar Produk</a></li>
            </ul>
        </div>
        <div class="user-profile">
            <div class="user-avatar"><?php echo substr($_SESSION['user'], 0, 2); ?></div>
            <div><h4><?php echo $_SESSION['user']; ?></h4></div>
            <a href="logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </div>

    <div class="main-content">
        <div class="welcome-header">
            <h2>Selamat Datang Kembali</h2>
            <p>Berikut adalah ringkasan performa Malikha House.</p>
        </div>

        <div class="cards-grid">
            <div class="card">
                <div class="card-info"><span>Total Produk</span><h3><?php echo $total_produk; ?> Varian</h3></div>
                <div class="card-icon"><i class="fa-solid fa-shirt"></i></div>
            </div>
            <div class="card">
                <div class="card-info"><span>Total Stok</span><h3><?php echo (int)$total_stok; ?> Pcs</h3></div>
                <div class="card-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
            </div>
            <div class="card">
                <div class="card-info"><span>Petugas</span><h3><?php echo $total_user; ?> Akun</h3></div>
                <div class="card-icon"><i class="fa-solid fa-users-gear"></i></div>
            </div>
        </div>

        <div class="data-section">
            <div class="section-header">
                <h3>Daftar Pengguna Sistem</h3>
                <a href="create.php" class="btn-tambah"><i class="fa-solid fa-plus"></i> Tambah User</a>
            </div>
            <table>
                <thead>
                    <tr><th>ID</th><th>Nama</th><th>Username</th><th>Role</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo $u['id']; ?></td>
                        <td><strong><?php echo $u['nama']; ?></strong></td>
                        <td><?php echo $u['username']; ?></td>
                        <td><span class="badge badge-<?php echo $u['role']; ?>"><?php echo $u['role']; ?></span></td>
                        <td class="actions-cell">
                            <a href="edit.php?id=<?php echo $u['id']; ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                            <a href="hapus.php?id=<?php echo $u['id']; ?>"><i class="fa-regular fa-trash-can"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>