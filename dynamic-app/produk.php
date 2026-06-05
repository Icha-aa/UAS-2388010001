<?php
session_start();
include 'src/db.php'; 

try {
    $stmt = $pdo->query("SELECT * FROM products");
    $list_produk = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error mengambil data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk - Malikha House</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --bg-workspace: #f9f8f6; --bg-card: #ffffff; --accent-brown: #8e7355; --text-main: #2c2520; --text-muted: #8a8073; --border-color: #ededeb; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-workspace); display: flex; }
        
        .sidebar { width: 260px; height: 100vh; background: #ffffff; border-right: 1px solid var(--border-color); position: fixed; padding: 30px 20px; }
        .sidebar-brand { font-size: 1.25rem; font-weight: 700; margin-bottom: 40px; }
        .menu-item a { display: block; padding: 12px; color: var(--text-muted); text-decoration: none; }
        .menu-item.active a { color: var(--accent-brown); font-weight: bold; }

        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px 50px; }
        .data-section { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 30px; }
        .btn-tambah { background: var(--accent-brown); color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; float: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; padding: 15px; border-bottom: 2px solid var(--border-color); }
        td { padding: 15px; border-bottom: 1px solid var(--border-color); }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="sidebar-brand">MALIKHA<span>HOUSE</span></div>
            <ul class="menu-group">
                <li class="menu-item active"><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                <li class="menu-item"><a href="manajemen_user.php"><i class="fa-solid fa-users"></i> Manajemen User</a></li>
                <li class="menu-item"><a href="produk.php"><i class="fa-solid fa-box"></i> Daftar Produk</a></li>
            </ul>
        </div>
        <div class="user-profile">
            <div class="user-avatar">SM</div>
            <div><h4 style="font-size: 0.9rem;">Siti Admin</h4><span style="font-size: 0.75rem; color: var(--text-muted);">Administrator</span></div>
            <a href="login.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </div>

    <div class="main-content">
        <div class="data-section">
            <a href="tambah_produk.php" class="btn-tambah">+ Tambah Produk</a>
            <h2>Manajemen Daftar Produk</h2>
            <table>
                <thead>
                    <tr><th>ID</th><th>Kode</th><th>Nama Produk</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($list_produk as $p): ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td><?php echo $p['kode_produk']; ?></td>
                        <td><?php echo $p['nama_produk']; ?></td>
                        <td>Rp <?php echo number_format($p['harga_jual'], 0, ',', '.'); ?></td>
                        <td><?php echo $p['stok']; ?></td>
                        <td>
                            <a href="edit_produk.php?id=<?php echo $p['id']; ?>" style="color: var(--accent-brown);">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>