<?php
require_once 'src/auth.php';
require_once 'src/db.php';
urusLogin();

$id = $_GET['id'] ?? null;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) { header("Location: dashboard.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    $stmt = $pdo->prepare("UPDATE products SET nama = ?, harga = ?, deskripsi = ? WHERE id = ?");
    $stmt->execute([$nama, $harga, $deskripsi, $id]);
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><title>Edit Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container" style="max-width: 600px; background: white; padding: 30px; border-radius: 10px; margin-top: 50px;">
    <h2>Edit Data Kerudung</h2>
    <form action="" method="POST">
        <p><label>Nama Kerudung</label><br><input type="text" name="nama" value="<?= htmlspecialchars($p['nama']) ?>" style="width:100%; padding:8px;" required></p>
        <p><label>Harga (IDR)</label><br><input type="number" name="harga" value="<?= htmlspecialchars($p['harga']) ?>" style="width:100%; padding:8px;" required></p>
        <p><label>Deskripsi Kain</label><br><textarea name="deskripsi" style="width:100%; padding:8px;" rows="4" required><?= htmlspecialchars($p['deskripsi']) ?></textarea></p>
        <button type="submit" style="background:#ff69b4; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">Perbarui</button>
        <a href="dashboard.php" style="margin-left: 10px; color: #7f8c8d;">Batal</a>
    </form>
</div>
</body>
</html>