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
        </tbody>
    </table>
</div>