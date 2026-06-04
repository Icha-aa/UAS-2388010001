<?php
session_start();
include 'src/db.php';

// Inisialisasi variabel supaya tidak error "Undefined variable"
$list_produk = []; 

try {
    $stmt = $pdo->query("SELECT * FROM products");
    $list_produk = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css"> <!-- PASTIKAN style.css ada di folder yang sama -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Daftar Produk</title>
</head>
<body>
    <!-- Pastikan struktur sidebar sudah ada di sini -->
    <div class="main-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2>Daftar Produk</h2>
            <a href="tambah_produk.php" class="btn-tambah"><i class="fa-solid fa-plus"></i> Tambah Produk</a>
        </div>

        <div class="card-table">
            <table class="data-table">
                <thead>
                    <tr><th>ID</th><th>KODE</th><th>NAMA</th><th>KATEGORI</th><th>HARGA</th><th>STOK</th><th>AKSI</th></tr>
                </thead>
                <tbody>
                    <?php if (!empty($list_produk)): ?>
                        <?php foreach($list_produk as $p): ?>
                        <tr>
                            <td><?php echo $p['id']; ?></td>
                            <td><?php echo $p['kode_produk']; ?></td>
                            <td><?php echo $p['nama_produk']; ?></td>
                            <td><?php echo $p['kategori']; ?></td>
                            <td>Rp <?php echo number_format($p['harga_jual'], 0, ',', '.'); ?></td>
                            <td><?php echo $p['stok']; ?></td>
                            <td><a href="edit_produk.php?id=<?php echo $p['id']; ?>" class="action-icon"><i class="fa-solid fa-pen-to-square"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">Data tidak ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>