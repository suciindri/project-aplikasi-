<?php
session_start();
include "connection.php";

// hanya admin yang boleh akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$HARGA_PER_KG = 5000;

// ambil semua pesanan + join ke tb_users
$sql = "
    SELECT 
        o.id,
        u.nama_lengkap,
        o.tanggal,
        o.jenis_laundry,
        o.berat,
        o.status     
    FROM tb_orders o
    JOIN tb_users u ON o.user_id = u.id
    ORDER BY o.id DESC
";
$query = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>
<style>
body { font-family: Arial; background:#f4f4f4; margin:0; padding:20px; }
h2 { text-align:center; }
table { border-collapse: collapse; width: 100%; margin-top:20px; background:#fff; }
th, td { border:1px solid #ccc; padding:10px; text-align:center; }
th { background:#28a745; color:#fff; }
button, select { padding:5px 10px; margin:2px; }
form { display:inline; }
</style>
</head>
<body>

<h2>Dashboard Admin - Kelola Pesanan</h2>

<table>
<tr>
<th>ID</th>
<th>Nama User</th>
<th>Tanggal</th>
<th>Jenis Laundry</th>
<th>Berat (Kg)</th>
<th>Total Harga</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($query)) { 
    $total = $row['berat'] * $HARGA_PER_KG;
?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
<td><?= $row['tanggal'] ?></td>
<td><?= htmlspecialchars($row['jenis_laundry']) ?></td>
<td><?= $row['berat'] ?> Kg</td>
<td>Rp <?= number_format($total,0,',','.') ?></td>
<td><?= htmlspecialchars($row['status']) ?></td>
<td>
    <!-- Form update status -->
    <form method="POST" action="update_status.php">
        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
        <select name="status">
            <option value="baru" <?= $row['status']=='baru'?'selected':'' ?>>Baru</option>
            <option value="sedang di proses" <?= $row['status']=='sedang di proses'?'selected':'' ?>>Sedang diproses</option>
            <option value="sedang di cuci" <?= $row['status']=='sedang di cuci'?'selected':'' ?>>Sedang dicuci</option>
            <option value="siap di kirim" <?= $row['status']=='siap di kirim'?'selected':'' ?>>Siap dikirim</option>
            <option value="sudah di kirim" <?= $row['status']=='sudah di kirim'?'selected':'' ?>>Sudah dikirim</option>
            <option value="selesai" <?= $row['status']=='selesai'?'selected':'' ?>>Selesai</option>
            <option value="sudah diambil" <?= $row['status']=='sudah diambil'?'selected':'' ?>>Sudah diambil</option>
        </select>
        <button type="submit">Ubah</button>
    </form>
    <!-- Form delete -->
    <form method="POST" action="delete_order.php" onsubmit="return confirm('Yakin hapus pesanan ini?');">
        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
        <button type="submit">Hapus</button>
    </form>
</td>
</tr>
<?php } ?>
</table>

</body>
</html>
