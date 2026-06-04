<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'src/db.php'; 

$query_varian = $pdo->query("SELECT COUNT(id) AS total_varian FROM products")->fetch();
$total_stok = $pdo->query("SELECT SUM(stok) AS total_stok FROM products")->fetch()['total_stok'] ?? 0;
$total_user = $pdo->query("SELECT COUNT(id) AS total_user FROM users")->fetch()['total_user'] ?? 0;
$list_users = $pdo->query("SELECT * FROM users ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - CoreSystem</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">Core<span>System</span></div>
        <ul class="menu-group">
            <li class="menu-item active"><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li class="menu-item"><a href="produk.php"><i class="fa-solid fa-box"></i> Daftar Produk</a></li>
        </ul>
        <div class="user-profile">
            <div class="user-avatar">AD</div>
            <span><?php echo $_SESSION['user']; ?></span>
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </div>

    <div class="main-content">
        <h2>Dashboard</h2>
        <div style="display: flex; gap: 20px; margin-bottom: 30px;">
            <div class="card-table"><h3>Varian Produk</h3><p><?php echo $query_varian['total_varian']; ?></p></div>
            <div class="card-table"><h3>Total Stok</h3><p><?php echo $total_stok; ?></p></div>
            <div class="card-table"><h3>Total User</h3><p><?php echo $total_user; ?></p></div>
        </div>
        
        <div class="card-table">
            <h3>Daftar User</h3>
            <table class="data-table">
                <thead><tr><th>Nama</th><th>Username</th><th>Role</th></tr></thead>
                <tbody>
                    <?php foreach($list_users as $u): ?>
                    <tr>
                        <td><?php echo $u['nama_petugas']; ?></td>
                        <td><?php echo $u['username']; ?></td>
                        <td><span class="badge-role badge-<?php echo $u['role']; ?>"><?php echo $u['role']; ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>