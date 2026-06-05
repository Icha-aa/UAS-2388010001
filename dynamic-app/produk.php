<?php
// 1. Memanggil sistem deteksi login dan database
require_once 'src/db.php';
require_once 'src/auth.php';

// 2. Mengambil data produk untuk ringkasan di dashboard
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id ASC");
    $list_produk = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Gagal memuat sistem dashboard: " . $e->getMessage());
}

// 3. Mengambil data akun yang sedang login untuk diletakkan di sidebar
$nama_user = $_SESSION['nama_petugas'] ?? $_SESSION['nama'] ?? 'Siti Admin';
$role_user = $_SESSION['role'] ?? 'Administrator';
$inisial = strtoupper(substr($nama_user, 0, 2));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utama - CoreSystem Hijab</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-workspace: #f9f8f6;
            --bg-card: #ffffff;
            --accent-brown: #8e7355;
            --accent-brown-light: #b59f85;
            --text-main: #2c2520;
            --text-muted: #8a8073;
            --border-color: #ededeb;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-workspace); color: var(--text-main); display: flex; }
        
        /* Sidebar Navigation Menu */
        .sidebar { width: 260px; height: 100vh; background: #ffffff; border-right: 1px solid var(--border-color); position: fixed; padding: 30px 20px; display: flex; flex-direction: column; justify-content: space-between; }
        .sidebar-brand { font-size: 1.25rem; font-weight: 700; margin-bottom: 40px; padding-left: 10px; }
        .sidebar-brand span { color: var(--accent-brown); }
        .menu-group { list-style: none; }
        .menu-item { margin-bottom: 8px; }
        .menu-item a { display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: var(--text-muted); text-decoration: none; font-weight: 500; font-size: 0.95rem; border-radius: 8px; }
        .menu-item.active a, .menu-item a:hover { background: rgba(142, 115, 85, 0.05); color: var(--accent-brown); }
        .user-profile { display: flex; align-items: center; gap: 12px; padding-top: 20px; border-top: 1px solid var(--border-color); }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--accent-brown-light); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; }
        .logout-btn { color: var(--text-muted); text-decoration: none; font-size: 1.1rem; margin-left: auto; }

        /* Main Panel Layout */
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px 50px; }
        .header-area { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-title h2 { font-size: 1.8rem; font-weight: 700; }
        .header-title p { color: var(--text-muted); font-size: 0.95rem; margin-top: 4px; }
        
        .btn-tambah { background: var(--accent-brown); color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; }
        .btn-tambah:hover { background: var(--accent-brown-light); }

        /* Data Container Sheet */
        .data-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 35px; }
        
        /* Table Elements UI */
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; padding-bottom: 15px; border-bottom: 1px solid var(--border-color); }
        td { padding: 18px 0; border-bottom: 1px solid var(--border-color); font-size: 0.95rem; }
        
        .badge-kategori { background: #f3f4f6; color: #4b5563; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 500; }
        .actions-cell a { color: var(--text-muted); text-decoration: none; margin-right: 15px; font-size: 1rem; }
        .actions-cell a:hover { color: var(--accent-brown); }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="sidebar-brand">Core<span>System</span></div>
            <ul class="menu-group">
                <li class="menu-item active"><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                <li class="menu-item"><a href="tambah_user.php"><i class="fa-solid fa-users"></i> Manajemen User</a></li>
                <li class="menu-item"><a href="produk.php"><i class="fa-solid fa-box"></i> Daftar Produk</a></li>
                <li class="menu-item"><a href="settin.php"><i class="fa-solid fa-gear"></i> Pengaturan</a></li>
            </ul>
        </div>
        <div class="user-profile">
            <div class="user-avatar"><?= htmlspecialchars($inisial) ?></div>
            <div>
                <h4 style="font-size: 0.9rem;"><?= htmlspecialchars($nama_user) ?></h4>
                <span style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($role_user) ?></span>
            </div>
            <a href="logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </div>

    <div class="main-content">
        <div class="header-area">
            <div class="header-title">
                <h2>Selamat Datang di CoreSystem</h2>
                <p>Berikut adalah ringkasan inventaris etalase jilbab saat ini.</p>
            </div>
            <a href="tambah_produk.php" class="btn-tambah"><i class="fa-solid fa-plus"></i> Tambah Produk</a>
        </div>

        <div class="data-section">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;"><i class="fa-solid fa-boxes-stacked" style="color: var(--accent-brown);"></i> Ringkasan Stok Gudang</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode</th>
                        <th>Nama Varian Hijab</th>
                        <th>Kategori</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th style="text-align: right; padding-right: 15px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($list_produk) > 0): ?>
                        <?php foreach ($list_produk as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><code><?= htmlspecialchars($p['kode_produk'] ?? '') ?></code></td>
                            <td><strong><?= htmlspecialchars($p['nama_produk'] ?? '') ?></strong></td>
                            <td><span class="badge-kategori"><?= htmlspecialchars($p['kategori'] ?? 'Hijab') ?></span></td>
                            <td>Rp <?= number_format($p['harga_jual'] ?? 0, 0, ',', '.') ?></td>
                            <td><strong><?= number_format($p['stok'] ?? 0, 0, ',', '.') ?></strong> Pcs</td>
                            <td class="actions-cell" style="text-align: right; padding-right: 15px;">
                                <a href="edit_produk.php?id=<?= $p['id'] ?>" title="Edit Barang"><i class="fa-regular fa-pen-to-square"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 20px 0;">Belum ada data barang masuk.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>