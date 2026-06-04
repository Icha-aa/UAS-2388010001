<?php
session_start();
include 'src/db.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Ambil data user lama
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

// Proses simpan perubahan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET nama = ?, username = ?, role = ?, password = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama, $username, $role, $password, $id]);
    } else {
        $sql = "UPDATE users SET nama = ?, username = ?, role = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama, $username, $role, $id]);
    }
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User - MALIKHA HOUSE</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-content">
        <div class="form-section">
            <form method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label>ID Pengguna</label>
                        <input type="text" class="form-control" value="<?php echo $user['id']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Hak Akses</label>
                        <select name="role" class="form-control">
                            <option value="kasir" <?php if($user['role']=='kasir') echo 'selected'; ?>>Kasir</option>
                            <option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label>Nama Petugas</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo $user['nama']; ?>" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Isi jika ingin ganti">
                    </div>
                </div>
                <div class="btn-group-form">
                    <a href="dashboard.php" class="btn-cancel">Kembali</a>
                    <button type="submit" class="btn-update">Perbarui produk</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>