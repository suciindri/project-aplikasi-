<?php
session_start();
include "connection.php";

// cek admin
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$user_query = mysqli_query($conn, "SELECT role FROM tb_users WHERE email='$email'");
$user = mysqli_fetch_assoc($user_query);

if ($user['role'] != "admin") {
    die("Akses ditolak!");
}

// ambil semua pesanan
$result = mysqli_query($conn, "SELECT tb_orders.*, tb_users.nama_lengkap 
                               FROM tb_orders 
                               JOIN tb_users ON tb_orders.user_id = tb_users.id
                               ORDER BY tb_orders.id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin - Pesanan</title>
</head>
<body>
<h2>Daftar Pesanan (Admin)</h2>
<table border="1" cellpadding="5" cellspacing="0">
<tr>
<th>No</th>
<th>User</th>
<th>Tanggal</th>
<th>Jenis Laundry</th>
<th>Berat (Kg)</th>
<th>Status</th>
<th>Aksi</th>
</tr>
<?php $no=1; while($row=mysqli_fetch_assoc($result)): ?>
<tr>
<td><?= $no++ ?></td>
<td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
<td><?= htmlspecialchars($row['tanggal']) ?></td>
<td><?= htmlspecialchars($row['jenis_laundry']) ?></td>
<td><?= $row['berat'] ?></td>
<td><?= htmlspecialchars($row['status']) ?></td>
<td>
<form action="update_status.php" method="post">
<input type="hidden" name="order_id" value="<?= $row['id'] ?>">
<select name="status">
    <option value="sedang di proses">Sedang di proses</option>
    <option value="sedang di cuci">Sedang di cuci</option>
    <option value="siap di kirim">Siap di kirim</option>
    <option value="sudah di kirim">Sudah di kirim</option>
    <option value="selesai">Selesai</option>
    <option value="sudah diambil">Sudah diambil</option>
</select>
<button type="submit">Update</button>
</form>
</td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
