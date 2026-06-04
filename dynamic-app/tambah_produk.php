<?php
// Hubungkan ke database
include 'src/db.php';

// Proses Simpan Data
if (isset($_POST['simpan'])) {
    $kode = $_POST['kode_produk'];
    $nama = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga_jual'];
    $stok = $_POST['stok'];

    $sql = "INSERT INTO products (kode_produk, nama_produk, kategori, harga_jual, stok) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$kode, $nama, $kategori, $harga, $stok])) {
        echo "<script>alert('Produk berhasil ditambahkan!'); window.location.href='produk.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah produk.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - CoreSystem</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-card { background: var(--bg-card); padding: 30px; border-radius: 12px; border: 1px solid var(--border-color); max-width: 600px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 0.9rem; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; }
        .btn-submit { background: var(--accent-brown); color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; margin-top: 10px; }
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
        <h2>Tambah Produk Baru</h2>
        <div class="form-card">
            <form method="POST">
                <div class="form-group"><label>Kode Produk</label><input type="text" name="kode_produk" required></div>
                <div class="form-group"><label>Nama Produk</label><input type="text" name="nama_produk" required></div>
                <div class="form-group"><label>Kategori</label><input type="text" name="kategori" required></div>
                <div class="form-group"><label>Harga Jual</label><input type="number" name="harga_jual" required></div>
                <div class="form-group"><label>Stok</label><input type="number" name="stok" required></div>
                <button type="submit" name="simpan" class="btn-submit">Simpan Produk</button>
            </form>
        </div>
    </div>
</body>
</html>