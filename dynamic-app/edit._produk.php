<?php
// Hubungkan ke database
include 'src/db.php';

// 1. Ambil ID dari URL
$id = $_GET['id'];

// 2. Ambil data produk berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$produk = $stmt->fetch();

// 3. Proses Update Data
if (isset($_POST['update'])) {
    $kode = $_POST['kode_produk'];
    $nama = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga_jual'];
    $stok = $_POST['stok'];

    $sql = "UPDATE products SET kode_produk=?, nama_produk=?, kategori=?, harga_jual=?, stok=? WHERE id=?";
    $stmt_update = $pdo->prepare($sql);
    
    if ($stmt_update->execute([$kode, $nama, $kategori, $harga, $stok, $id])) {
        echo "<script>alert('Data produk berhasil diperbarui!'); window.location.href='produk.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - CoreSystem</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-card { background: var(--bg-card); padding: 30px; border-radius: 12px; border: 1px solid var(--border-color); max-width: 600px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 0.9rem; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; }
        .btn-submit { background: var(--accent-brown); color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="sidebar">
        <ul class="menu-group">
            <li class="menu-item"><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li class="menu-item"><a href="produk.php"><i class="fa-solid fa-box"></i> Daftar Produk</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h2>Edit Produk</h2>
        <div class="form-card">
            <form method="POST">
                <div class="form-group"><label>Kode Produk</label><input type="text" name="kode_produk" value="<?php echo $produk['kode_produk']; ?>" required></div>
                <div class="form-group"><label>Nama Produk</label><input type="text" name="nama_produk" value="<?php echo $produk['nama_produk']; ?>" required></div>
                <div class="form-group"><label>Kategori</label><input type="text" name="kategori" value="<?php echo $produk['kategori']; ?>" required></div>
                <div class="form-group"><label>Harga Jual</label><input type="number" name="harga_jual" value="<?php echo $produk['harga_jual']; ?>" required></div>
                <div class="form-group"><label>Stok</label><input type="number" name="stok" value="<?php echo $produk['stok']; ?>" required></div>
                <button type="submit" name="update" class="btn-submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>
</html>