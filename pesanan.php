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

$HARGA_PER_KG = 7000;

// ambil daftar pesanan user langsung dari tb_orders
$query = "
    SELECT 
        id, tanggal, jenis_laundry, berat, status
    FROM tb_orders
    WHERE user_id = $user_id
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
<title>Pesanan Saya</title>
<style>
    body { 
    font-family: Arial, sans-serif; 
    background-image: url("Fashion%20Instagram%20story%20template%20and%20iPhone%20background%20fashion%20bloggers%20Instagram.jpeg");
    background-size: cover; 
    background-position: center; 
    margin: 0; 
    padding: 0; 
}

.container { 
    width: 80%; 
    margin: 30px auto; 
    background: rgba(255,255,255,0.9); 
    padding: 20px; 
    border-radius: 15px; 
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    position: relative; /* untuk posisi tombol beranda */
}

h2 { 
    text-align: center; 
    margin-bottom: 20px; 
    color: #333; 
}

.table { 
    width: 100%; 
    border-collapse: collapse; 
    background: #fff; 
    border-radius: 10px;
    overflow: hidden;
}

.table th, .table td { 
    border: 1px solid #ccc; 
    padding: 10px; 
    text-align: center; 
}

.table th { 
    background: #333; 
    color: #fff; 
}

.status { 
    padding: 5px 10px; 
    border-radius: 5px; 
    color: #fff; 
    display: inline-block; 
    font-size: 14px;
}

.status.baru     { background: #007bff; }
.status.proses   { background: #ffc107; color: #000; }
.status.cuci     { background: #17a2b8; }
.status.siap     { background: #6f42c1; }
.status.kirim    { background: #20c997; }
.status.selesai  { background: #28a745; }
.status.diambil  { background: #898a8b; }

.beranda {
    display: inline-block;
    margin-top: 15px;
    padding: 8px 15px;
    background: #6b6e71;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    position: absolute; 
    top: 15px;
    left: 15px;
    transition: 0.3s;
}

.beranda:hover {
    background: #0056b3;
}

</style>
</head>
<body>

<div class="container">
    <h2>Pesanan Laundry Saya</h2>
    <table class="table">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jenis Laundry</th>
            <th>Berat (Kg)</th>
            <th>Total Harga</th>
            <th>Status</th>
        </tr>
        <?php 
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) { 
            $status = $row['status'];

            // map status ke class badge
            $class = "baru";
            if ($status == "sedang di proses") $class = "proses";
            elseif ($status == "sedang di cuci") $class = "cuci";
            elseif ($status == "siap di kirim") $class = "siap";
            elseif ($status == "sudah di kirim") $class = "kirim";
            elseif ($status == "selesai") $class = "selesai";
            elseif ($status == "sudah diambil") $class = "diambil";

            $total_harga = (int)$row['berat'] * $HARGA_PER_KG;
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['tanggal']) ?></td>
            <td><?= htmlspecialchars($row['jenis_laundry'] ?? '-') ?></td>
            <td><?= (int)$row['berat'] ?> Kg</td>
            <td>Rp <?= number_format($total_harga, 0, ',', '.') ?></td>
            <td><span class="status <?= $class ?>"><?= htmlspecialchars($status) ?></span></td>
        </tr>
        <?php } ?>
    </table>
    <a href="dashboard.php" class="beranda">Beranda</a>
</div>
</body>
</html>
