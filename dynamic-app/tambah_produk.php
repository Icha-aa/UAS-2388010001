<?php
session_start();
include 'src/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "INSERT INTO products (kode_produk, nama_produk, kategori, harga_jual, stok) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['kode'], $_POST['nama'], $_POST['kategori'], $_POST['harga'], $_POST['stok']]);
    header("Location: produk.php");
}
?>
<!-- Pastikan name inputnya adalah harga_jual -->
<form method="POST">
    <input type="text" name="kode" placeholder="Kode Produk" required>
    <input type="text" name="nama" placeholder="Nama Produk" required>
    <input type="text" name="kategori" placeholder="Kategori" required>
    <input type="number" name="harga" placeholder="Harga Jual" required>
    <input type="number" name="stok" placeholder="Stok" required>
    <button type="submit">Simpan</button>
</form>