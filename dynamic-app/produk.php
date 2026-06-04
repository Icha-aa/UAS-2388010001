<?php
session_start();
include 'src/db.php';

// Proses Hapus (Jika ada parameter hapus di URL)
if (isset($_GET['hapus'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['hapus']]);
    header("Location: produk.php");
}

// Proses Tambah Produk
if (isset($_POST['tambah'])) {
    $stmt = $pdo->prepare("INSERT INTO products (kode_produk, nama_produk, kategori, harga_jual, stok) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['kode'], $_POST['nama'], $_POST['kategori'], $_POST['harga'], $_POST['stok']]);
    header("Location: produk.php");
}

// Ambil Data Produk
$produk = $pdo->query("SELECT * FROM products")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk - MALIKHA HOUSE</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="main-content">
        <div class="header-section">
            <div>
                <h2>Manajemen Produk</h2>
                <p>Kelola stok dan harga produk hijab.</p>
            </div>
            <button class="btn-tambah" onclick="openModal('modalTambah')"><i class="fa-solid fa-plus"></i> Tambah Produk</button>
        </div>
        
        <div class="card-table">
            <table class="data-table">
                <thead>
                    <tr><th>ID</th><th>KODE</th><th>NAMA</th><th>KATEGORI</th><th>HARGA</th><th>STOK</th><th>AKSI</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($produk as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= $p['kode_produk'] ?></td>
                        <td><?= $p['nama_produk'] ?></td>
                        <td><span class="badge-kategori"><?= $p['kategori'] ?></span></td>
                        <td>Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?></td>
                        <td><?= $p['stok'] ?> pcs</td>
                        <td class="action-links">
                            <button onclick="window.location.href='edit_produk.php?id=<?= $p['id'] ?>'"><i class="fa-regular fa-pen-to-square"></i></button>
                            <button onclick="if(confirm('Hapus produk?')) window.location.href='?hapus=<?= $p['id'] ?>'" style="color: #c94a4a;"><i class="fa-regular fa-trash-can"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalTambah" class="modal">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">Tambah Produk</div>
                <div class="form-group"><label>Kode</label><input type="text" name="kode" class="form-control" required></div>
                <div class="form-group"><label>Nama</label><input type="text" name="nama" class="form-control" required></div>
                <div class="form-group"><label>Kategori</label><input type="text" name="kategori" class="form-control" required></div>
                <div class="form-group"><label>Harga</label><input type="number" name="harga" class="form-control" required></div>
                <div class="form-group"><label>Stok</label><input type="number" name="stok" class="form-control" required></div>
                <div class="modal-footer">
                    <button type="button" class="btn-close" onclick="closeModal('modalTambah')">Batal</button>
                    <button type="submit" name="tambah" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).style.display = 'flex'; }
        function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    </script>
</body>
</html>