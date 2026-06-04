<?php include 'src/db.php'; 
if (isset($_GET['hapus_id'])) {
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$_GET['hapus_id']]);
    header("Location: produk.php");
}
$list_produk = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk - CoreSystem</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        </div>
    <div class="main-content">
        <div class="header-section">
            <h2>Daftar Produk</h2>
            <a href="tambah_produk.php" class="btn-tambah">Tambah Produk</a>
        </div>
        <div class="card-table">
            <table class="data-table">
                </table>
        </div>
    </div>
</body>
</html>