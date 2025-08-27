<?php
session_start();
include "connection.php";

// pastikan user sudah login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$user_query = mysqli_query($conn, "SELECT id, nama_lengkap FROM tb_users WHERE email='$email'");
if (!$user_query || mysqli_num_rows($user_query) === 0) {
    die("User tidak ditemukan.");
}
$user = mysqli_fetch_assoc($user_query);
$user_id = (int)$user['id'];

// ambil hanya pesanan yang sudah selesai beserta total_harga
$query = "
    SELECT jenis_laundry, status, total_harga
    FROM tb_orders
    WHERE user_id = $user_id AND status='selesai'
    ORDER BY id DESC
";
$result = mysqli_query($conn, $query);
if (!$result) {
    die('Query error: ' . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Pesanan Selesai</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f0f2f5; margin:0; padding:0; display:flex; justify-content:center; align-items:flex-start; min-height:100vh; padding-top:50px; }
.container { width:80%; background:#fff; padding:30px; border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,0.15); }
h2 { text-align:center; margin-bottom:25px; color:#333; text-transform:uppercase; }
.table { width:100%; border-collapse:collapse; margin-bottom:30px; }
.table th, .table td { padding:12px; text-align:center; border-bottom:1px solid #ddd; }
.table th { background:#6c5ce7; color:#fff; }
.table tr:nth-child(even){ background:#f9f9f9; }
.table tr:hover{ background:#f1f1f1; }

.status { padding:6px 14px; border-radius:20px; font-weight:600; min-width:100px; display:inline-block; background:#28a745; color:#fff; } /* Selesai */

a { display:inline-block; margin-top:20px; text-decoration:none; padding:12px 20px; background:#6c5ce7; color:#fff; border-radius:30px; font-weight:bold; transition:0.3s; }
a:hover { background:#341f97; transform:translateY(-2px);}
</style>
</head>
<body>
<div class="container">
    <h2>Riwayat Pesanan Selesai</h2>
    <table class="table">
        <tr>
            <th>Jenis Laundry</th>
            <th>Total Harga</th>
            <th>Status</th>
        </tr>
        <?php 
        if(mysqli_num_rows($result) > 0){
            while ($row = mysqli_fetch_assoc($result)) { 
                $total_formatted = "Rp " . number_format($row['total_harga'], 0, ',', '.');
        ?>
        <tr>
            <td><?= htmlspecialchars($row['jenis_laundry'] ?? '-') ?></td>
            <td><?= $total_formatted ?></td>
            <td><span class="status"><?= htmlspecialchars($row['status']) ?></span></td>
        </tr>
        <?php 
            } 
        } else { 
        ?>
        <tr><td colspan="3">Belum ada pesanan selesai</td></tr>
        <?php } ?>
    </table>
    <a href="dashboard.php">⬅ Kembali ke Dashboard</a>
</div>
</body>
</html>
