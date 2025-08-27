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

// ambil daftar pesanan yang sudah selesai atau sudah diambil
$query = "
    SELECT jenis_laundry, status
    FROM tb_orders
    WHERE user_id = $user_id
    AND status IN ('selesai', 'sudah diambil')
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
<title>Pesanan Selesai</title>
<style>
/* Background cantik */
body { 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
    background-image: url("Fashion\ Instagram\ story\ template\ and\ iPhone\ background\ fashion\ bloggers\ Instagram.jpeg");
    margin: 0; 
    padding: 0; 
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* Container elegan */
.container { 
    width: 60%; 
    background: rgba(255, 255, 255, 0.95);
    padding: 25px 30px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    animation: fadeIn 1s ease-in-out;
}

/* Judul */
h2 { 
    text-align: center; 
    margin-bottom: 20px; 
    font-size: 26px;
    color: #4a148c;
}

/* Tabel */
.table { 
    width: 100%; 
    border-collapse: collapse; 
    background:#fff; 
    border-radius: 10px;
    overflow: hidden;
}

.table th, .table td { 
    border: 1px solid #ddd; 
    padding: 12px; 
    text-align: center; 
    font-size: 15px;
}

.table th { 
    background: #6a1b9a; 
    color: #fff; 
    font-size: 16px;
}

.table tr:nth-child(even) {
    background: #f3e5f5;
}

.table tr:hover {
    background: #ede7f6;
    transition: 0.3s;
}

/* Badge status */
.status { 
    padding: 6px 12px; 
    border-radius: 20px; 
    color: #fff; 
    display:inline-block; 
    font-weight: bold;
    font-size: 14px;
    min-width: 90px;
}

.status.selesai { 
    background: linear-gradient(135deg, #00c853, #b2ff59); 
    color: #000;
}

.status.diambil { 
    background: linear-gradient(135deg, #757575, #bdbdbd);
    color: #fff;
}

/* Tombol kembali */
a {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 18px;
    background: #6a1b9a;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: 0.3s;
}

a:hover {
    background: #4a148c;
}

/* Animasi */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>
<div class="container">
    <h2>✨ Pesanan Laundry Selesai ✨</h2>
    <table class="table">
        <tr>
            <th>Jenis Laundry</th>
            <th>Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { 
            $status = $row['status'];
            $class = ($status == "selesai") ? "selesai" : "diambil";
        ?>
        <tr>
            <td><?= htmlspecialchars($row['jenis_laundry'] ?? '-') ?></td>
            <td><span class="status <?= $class ?>"><?= htmlspecialchars($status) ?></span></td>
        </tr>
        <?php } ?>
        <?php if(mysqli_num_rows($result) === 0): ?>
            <tr><td colspan="2">Belum ada pesanan selesai</td></tr>
        <?php endif; ?>
    </table>
    <a href="dashboard.php">⬅ Kembali ke Dashboard</a>
</div>
</body>
</html>
