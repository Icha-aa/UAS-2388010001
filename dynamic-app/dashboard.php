<?php include 'src/db.php'; 
$query_varian = $pdo->query("SELECT COUNT(id) AS total_varian FROM products")->fetch();
$total_stok = $pdo->query("SELECT SUM(stok) AS total_stok FROM products")->fetch()['total_stok'] ?? 0;
$total_user = $pdo->query("SELECT COUNT(id) AS total_user FROM users")->fetch()['total_user'] ?? 0;
$list_users = $pdo->query("SELECT * FROM users ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - CoreSystem</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        </div>
    <div class="main-content">
        </div>
</body>
</html>