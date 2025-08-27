<?php
session_start();
include "connection.php";

// cek login admin
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$user = $conn->query("SELECT * FROM tb_users WHERE email='$email'")->fetch_assoc();
if ($user['role'] !== 'admin') die("❌ Hanya admin yang bisa mengakses halaman ini!");

// ambil semua pesanan
$orders = $conn->query("SELECT * FROM tb_orders ORDER BY id DESC");

// ambil semua user (kecuali admin)
$users = $conn->query("SELECT * FROM tb_users WHERE role!='admin' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin:20px; background:#f0f2f5; }
h2,h3 { color:#2c3e50; margin-bottom:10px; }
table { width:100%; border-collapse: collapse; margin-bottom:30px; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
th, td { padding:12px 15px; text-align:left; }
th { background:#3498db; color:#fff; font-weight:600; text-transform:uppercase; }
tr:nth-child(even){background:#f4f6f9;}
tr:hover{background:#e8f1f8;}
button { padding:6px 14px; border:none; border-radius:6px; cursor:pointer; font-weight:500; transition:0.2s; }
button:hover{opacity:0.85;}
.btn-delete { background:#e74c3c; color:white; }
.btn-update { background:#3498db; color:white; }
select { padding:6px 10px; border-radius:6px; border:1px solid #ccc; outline:none; }
.status-badge { padding:4px 10px; border-radius:12px; color:white; font-weight:500; font-size:13px; display:inline-block; margin-top:4px; }
.status-proses { background:#f1c40f; }
.status-cuci { background:#3498db; }
.status-selesai { background:#2ecc71; }
form { display:inline-block; margin:0; }
</style>
</head>
<body>

<h2>Dashboard Admin</h2>

<h3>Daftar Pesanan</h3>
<table>
<tr>
    <th>ID</th>
    <th>Customer</th>
    <th>Jenis Laundry</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
<?php while($row = $orders->fetch_assoc()): 
    $status_class = '';
    if($row['status']=='sedang di proses') $status_class='status-proses';
    if($row['status']=='sedang di cuci') $status_class='status-cuci';
    if($row['status']=='selesai') $status_class='status-selesai';
?>
<tr>
    <td><?= $row['id']; ?></td>
    <td><?= htmlspecialchars($row['customer_name']); ?></td>
    <td><?= htmlspecialchars($row['jenis_laundry']); ?></td>
    <td>
        <form method="POST" action="update_status.php">
            <select name="status" required>
                <option value="sedang di proses" <?= $row['status']=="sedang di proses" ? "selected":""; ?>>sedang di proses</option>
                <option value="sedang di cuci" <?= $row['status']=="sedang di cuci" ? "selected":""; ?>>sedang di cuci</option>
                <option value="selesai" <?= $row['status']=="selesai" ? "selected":""; ?>>selesai</option>
            </select>
            <input type="hidden" name="order_id" value="<?= $row['id']; ?>">
            <button type="submit" class="btn-update">Ubah</button>
        </form>
        <div class="status-badge <?= $status_class ?>"><?= $row['status'] ?></div>
    </td>
    <td>
        <form method="POST" action="delete_order.php" onsubmit="return confirm('Yakin mau hapus pesanan ini?');">
            <input type="hidden" name="order_id" value="<?= $row['id']; ?>">
            <button type="submit" class="btn-delete">Hapus</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</table>

<h3>Daftar User</h3>
<table>
<tr>
    <th>ID</th>
    <th>Nama</th>
    <th>Email</th>
    <th>Role</th>
    <th>Aksi</th>
</tr>
<?php while($u = $users->fetch_assoc()): ?>
<tr>
    <td><?= $u['id']; ?></td>
    <td><?= htmlspecialchars($u['nama_lengkap']); ?></td>
    <td><?= htmlspecialchars($u['email']); ?></td>
    <td><?= htmlspecialchars($u['role']); ?></td>
    <td>
        <form method="POST" action="delete_user.php" onsubmit="return confirm('Yakin mau hapus user ini?');">
            <input type="hidden" name="user_id" value="<?= $u['id']; ?>">
            <button type="submit" class="btn-delete">Hapus</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</table>

<h3>Laporan Pesanan Selesai Pelanggan</h3>
<table>
<tr>
    <th>Nama Pelanggan</th>
    <th>Jenis Laundry</th>
    <th>Total Harga</th>
    <th>Status</th>
    <th>Tanggal Pesanan</th>
</tr>
<?php
$laporan = $conn->query("
    SELECT o.jenis_laundry, o.total_harga, o.status, o.created_at, u.nama_lengkap
    FROM tb_orders o
    JOIN tb_users u ON o.user_id = u.id
    WHERE o.status='selesai'
    ORDER BY o.created_at DESC
");

if ($laporan && $laporan->num_rows > 0) {
    while ($row = $laporan->fetch_assoc()) {
        $total_formatted = "Rp " . number_format($row['total_harga'], 0, ',', '.');
?>
<tr>
    <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
    <td><?= htmlspecialchars($row['jenis_laundry']) ?></td>
    <td><?= $total_formatted ?></td>
    <td><?= htmlspecialchars($row['status']) ?></td>
    <td><?= date("d-m-Y H:i", strtotime($row['created_at'])) ?></td>
</tr>
<?php
    }
} else {
?>
<tr><td colspan="5">Belum ada pesanan selesai</td></tr>
<?php } ?>
</table>

</body>
</html>
