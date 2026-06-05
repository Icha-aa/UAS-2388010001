<?php
session_start();
include 'src/db.php';

// 1. Ambil ID dari URL
$id = $_GET['id'];

// 2. Proses simpan (Update) jika tombol ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga_jual'];
    $stok = $_POST['stok'];

    $stmt = $pdo->prepare("UPDATE products SET nama_produk = ?, harga_jual = ?, stok = ? WHERE id = ?");
    $stmt->execute([$nama, $harga, $stok, $id]);
    
    header("Location: produk.php"); // Kembali ke halaman daftar produk
    exit;
}

// 3. Ambil data lama untuk ditampilkan di form
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - CoreSystem</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --bg-workspace: #f9f8f6; --accent-brown: #8e7355; --border-color: #ededeb; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-workspace); padding: 50px; }
        .form-section { background: #fff; padding: 30px; border-radius: 12px; border: 1px solid var(--border-color); max-width: 600px; margin: auto; }
        .form-group { margin-bottom: 15px; }
        .form-control { width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; }
        .btn-submit { background: var(--accent-brown); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="form-section">
        <h2>Edit Data Produk</h2>
        <br>
        <form method="POST">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" class="form-control" value="<?php echo $p['nama_produk']; ?>" required>
            </div>
            <div class="form-group">
                <label>Harga Jual</label>
                <input type="number" name="harga_jual" class="form-control" value="<?php echo $p['harga_jual']; ?>" required>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" value="<?php echo $p['stok']; ?>" required>
            </div>
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
            <a href="produk.php" style="margin-left: 15px; color: #888;">Batal</a>
        </form>
    </div>
</body>
</html>