<?php
session_start();
// Pastikan file db.php sudah benar lokasinya
include 'src/db.php'; 

// Mengambil data dinamis dari database
// Asumsi nama tabel adalah 'products' dan 'users'
$total_produk = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_stok = $pdo->query("SELECT SUM(stok) FROM products")->fetchColumn();
$total_user = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CoreSystem Hijab</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS tetap sama, bisa dipindahkan ke file style.css jika ingin lebih rapi */
        :root { --bg-workspace: #f9f8f6; --bg-card: #ffffff; --accent-brown: #8e7355; --accent-brown-light: #b59f85; --text-main: #2c2520; --text-muted: #8a8073; --border-color: #ededeb; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-workspace); color: var(--text-main); display: flex; }
        .sidebar { width: 260px; height: 100vh; background: #ffffff; border-right: 1px solid var(--border-color); position: fixed; padding: 30px 20px; display: flex; flex-direction: column; justify-content: space-between; }
        .sidebar-brand { font-size: 1.25rem; font-weight: 700; margin-bottom: 40px; padding-left: 10px; }
        .sidebar-brand span { color: var(--accent-brown); }
        .menu-group { list-style: none; }
        .menu-item { margin-bottom: 8px; }
        .menu-item a { display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: var(--text-muted); text-decoration: none; font-weight: 500; font-size: 0.95rem; border-radius: 8px; }
        .menu-item.active a, .menu-item a:hover { background: rgba(142, 115, 85, 0.05); color: var(--accent-brown); }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px 50px; }
        .cards-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin: 30px 0; }
        .card { background: var(--bg-card); border: 1px solid var(--border-color); padding: 25px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; }
        .data-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; border-bottom: 1px solid var(--border-color); color: var(--text-muted); text-transform: uppercase; font-size: 0.8rem; }
        td { padding: 18px 15px; border-bottom: 1px solid var(--border-color); }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; }
        .badge-admin { background: #fef3c7; color: #d97706; }
        .badge-kasir { background: #dbeafe; color: #2563eb; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="sidebar-brand">Core<span>System</span></div>
            <ul class="menu-group">
                <li class="menu-item active"><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                <li class="menu-item"><a href="create.php"><i class="fa-solid fa-users"></i> Manajemen User</a></li>
                <li class="menu-item"><a href="produk.php"><i class="fa-solid fa-box"></i> Daftar Produk</a></li>
                <li class="menu-item"><a href="setting.php"><i class="fa-solid fa-gear"></i> Pengaturan</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <h2>Selamat Datang Kembali</h2>
        <div class="cards-grid">
            <div class="card">
                <div><span>Total Produk</span><h3><?php echo $total_produk; ?> Varian</h3></div>
            </div>
            <div class="card">
                <div><span>Total Stok</span><h3><?php echo (int)$total_stok; ?> Pcs</h3></div>
            </div>
            <div class="card">
                <div><span>Total User</span><h3><?php echo $total_user; ?> Akun</h3></div>
            </div>
        </div>

        <div class="data-section">
            <h3>Daftar Pengguna Sistem</h3>
            <table>
                <thead>
                    <tr><th>ID</th><th>Nama Petugas</th><th>Username</th><th>Role</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['nama']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><span class="badge badge-<?php echo $user['role']; ?>"><?php echo $user['role']; ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>