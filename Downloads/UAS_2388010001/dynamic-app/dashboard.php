<?php
require_once 'src/auth.php';
require_once 'src/db.php';
require_once 'src/helpers.php';
urusLogin();

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 20px;}
        table, th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #ffb6c1; }
        .btn { padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 14px; }
        .btn-add { background: #ff69b4; color: white; padding: 10px 15px; display: inline-block; margin-bottom: 10px; border-radius: 5px;}
        .btn-edit { background: #f39c12; color: white; }
        .btn-del { background: #e74c3c; color: white; }
    </style>
</head>
<body>
<header>
    <div class="logo">Siti Hijab Admin</div>
    <nav>
        <a href="index.php" target="_blank">Lihat Toko</a>
        <a href="logout.php" style="color: red;">Logout</a>
    </nav>
</header>
<div class="container">
    <h2>Manajemen Produk Kerudung</h2>
    <a href="create.php" class="btn-add">Tambah Kerudung Baru</a>
    <table>
        <tr>
            <th>Nama</th>
            <th>Harga</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['nama']) ?></td>
            <td><?= formatRupiah($p['harga']) ?></td>
            <td><?= htmlspecialchars($p['deskripsi']) ?></td>
            <td>
                <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-edit">Edit</a>
                <a href="dashboard.php?delete=<?= $p['id'] ?>" class="btn btn-del" onclick="return confirm('Hapus produk ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
<?php
// Fitur Hapus Langsung
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: dashboard.php");
}
?>