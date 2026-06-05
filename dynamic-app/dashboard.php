<?php
// Memanggil file konfigurasi dan koneksi
require_once 'src/db.php';
require_once 'src/auth.php';

// Proteksi halaman: Cek apakah user sudah login
// checkLogin(); // Contoh fungsi dari auth.php

// 1. Ambil Data Summary
$total_produk = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk")->fetch_assoc()['total'];
$total_stok = mysqli_query($conn, "SELECT SUM(stok) as total FROM produk")->fetch_assoc()['total'];
$total_petugas = mysqli_query($conn, "SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];

// 2. Ambil Data User untuk Tabel
$query_users = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");

// 3. Data User Login (diambil dari session)
$nama_user = $_SESSION['nama'] ?? 'Siti Admin';
$role_user = $_SESSION['role'] ?? 'Administrator';
$inisial = strtoupper(substr($nama_user, 0, 2));
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
        
        /* Sidebar */
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

        /* Main Content */
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px 50px; }
        .welcome-header h2 { font-size: 1.8rem; font-weight: 700; margin-bottom: 4px; }
        .welcome-header p { color: var(--text-muted); font-size: 0.95rem; }

        /* Summary Cards */
        .cards-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin: 30px 0; }
        .card { background: var(--bg-card); border: 1px solid var(--border-color); padding: 25px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; }
        .card-info span { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .card-info h3 { font-size: 1.75rem; font-weight: 700; margin-top: 5px; }
        .card-icon { width: 45px; height: 45px; border-radius: 8px; background: #fdfcfb; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--accent-brown); font-size: 1.1rem; }

        /* Data Section */
        .data-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 30px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .section-header h3 { font-size: 1.15rem; font-weight: 700; }
        .btn-tambah { background: var(--accent-brown); color: white; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; }
        
        /* Table */
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; padding-bottom: 15px; border-bottom: 1px solid var(--border-color); }
        td { padding: 18px 0; border-bottom: 1px solid var(--border-color); font-size: 0.95rem; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; }
        .badge-admin { background: #fef3c7; color: #d97706; }
        .badge-kasir { background: #dbeafe; color: #2563eb; }
        .actions-cell a { color: var(--text-muted); text-decoration: none; margin-right: 12px; font-size: 0.95rem; }
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
            <div class="user-avatar"><?= $inisial ?></div>
            <div>
                <h4 style="font-size: 0.9rem;"><?= htmlspecialchars($nama_user) ?></h4>
                <span style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($role_user) ?></span>
            </div>
            <a href="logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </div>

    <div class="main-content">
        <div class="welcome-header">
            <h2>Selamat Datang Kembali</h2>
            <p>Berikut adalah ringkasan performa toko hijab dan manajemen hak akses pengguna.</p>
        </div>

        <div class="cards-grid">
            <div class="card">
                <div class="card-info"><span>Total Produk Hijab</span><h3><?= $total_produk ?> Varian</h3></div>
                <div class="card-icon"><i class="fa-solid fa-shirt"></i></div>
            </div>
            <div class="card">
                <div class="card-info"><span>Total Stok Gudang</span><h3><?= number_format($total_stok) ?> Pcs</h3></div>
                <div class="card-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
            </div>
            <div class="card">
                <div class="card-info"><span>Petugas Terdaftar</span><h3><?= $total_petugas ?> Akun</h3></div>
                <div class="card-icon"><i class="fa-solid fa-users-gear"></i></div>
            </div>
        </div>

        <div class="data-section">
            <div class="section-header">
                <h3>Daftar Pengguna Sistem</h3>
                <a href="tambah_user.php" class="btn-tambah"><i class="fa-solid fa-plus"></i> Tambah User</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Petugas</th>
                        <th>Username</th>
                        <th>Hak Akses (Role)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($query_users)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><strong><?= htmlspecialchars($row['nama_petugas'] ?? $row['nama']) ?></strong></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td>
                            <span class="badge <?= $row['role'] == 'admin' ? 'badge-admin' : 'badge-kasir' ?>">
                                <?= $row['role'] ?>
                            </span>
                        </td>
                        <td class="actions-cell">
                            <a href="edit_user.php?id=<?= $row['id'] ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                            <a href="hapus_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus user ini?')">
                                <i class="fa-regular fa-trash-can"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>