<?php
// 1. Inisialisasi Auth & Koneksi Database PDO
require_once 'src/db.php';
require_once 'src/auth.php';

// 2. Ambil data ringkasan untuk Widget & Tabel dari database uas_db
try {
    // Memperbaiki query dari 'produk' menjadi 'products' agar tidak error base table not found
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id ASC");
    $list_produk = $stmt->fetchAll();

    // Menghitung total varian produk hijab
    $total_varian = count($list_produk);

    // Menghitung akumulasi total seluruh stok barang di gudang
    $total_stok = 0;
    foreach ($list_produk as $p) {
        $total_stok += ($p['stok'] ?? 0);
    }
} catch (PDOException $e) {
    die("Gagal memuat sistem dashboard: " . $e->getMessage());
}

// 3. Mengambil informasi akun login aktif untuk Profile Sidebar
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
        
        /* Sidebar Menu Navigation */
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

        /* Main Workspace Layout */
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px 50px; }
        .header-title h2 { font-size: 1.8rem; font-weight: 700; }
        .header-title p { color: var(--text-muted); font-size: 0.95rem; margin-top: 4px; }
        
        /* Dashboard Info Cards / Widgets */
        .widget-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin: 30px 0; }
        .widget-card { background: var(--bg-card); border: 1px solid var(--border-color); padding: 25px; border-radius: 12px; display: flex; align-items: center; justify-content: space-between; }
        .widget-info h5 { font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .widget-info h3 { font-size: 1.8rem; font-weight: 700; margin-top: 5px; }
        .widget-icon { width: 48px; height: 48px; background: #faf8f5; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--accent-brown); font-size: 1.3rem; }

        /* Table Container Sheet */
        .data-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 35px; }
        .section-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        
        /* Table Design */
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; padding-bottom: 15px; border-bottom: 1px solid var(--border-color); }
        td { padding: 18px 0; border-bottom: 1px solid var(--border-color); font-size: 0.95rem; }
        
        .badge-kategori { background: #f3f4f6; color: #4b5563; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 500; }
        .actions-cell a { color: var(--text-muted); text-decoration: none; font-size: 1rem; }
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
        <div class="header-title">
            <h2>Selamat Datang Kembali</h2>
            <p>Berikut adalah ringkasan performa toko hijab dan manajemen hak akses pengguna.</p>
        </div>

        <div class="widget-grid">
            <div class="widget-card">
                <div class="widget-info">
                    <h5>Total Produk Hijab</h5>
                    <h3><?= var_format = $total_varian ?> Varian</h3>
                </div>
                <div class="widget-icon"><i class="fa-solid fa-shirt"></i></div>
            </div>

            <div class="widget-card">
                <div class="widget-info">
                    <h5>Total Stok Gudang</h5>
                    <h3><?= number_format($total_stok, 0, ',', '.') ?> Pcs</h3>
                </div>
                <div class="widget-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
            </div>

            <div class="widget-card">
                <div class="widget-info">
                    <h5>Petugas Terdaftar</h5>
                    <h3>2 Akun</h3>
                </div>
                <div class="widget-icon"><i class="fa-solid fa-user-gear"></i></div>
            </div>
        </div>

        <div class="data-section">
            <div class="section-title">
                <i class="fa-solid fa-list" style="color: var(--accent-brown);"></i>
                <span>Ringkasan Cepat Etalase Produk</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode</th>
                        <th>Nama Model Hijab</th>
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
                                <a href="edit_produk.php?id=<?= $p['id'] ?>" title="Lihat/Ubah Detail">
                                    <i class="fa-regular fa-pen-to-square"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 20px 0;">Belum ada data barang di database.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>