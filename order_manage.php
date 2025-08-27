<?php
session_start();
include "connection.php";

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM orders WHERE id=$id");
$order = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id=$id");
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Pesanan</title>
</head>
<body>
    <h2>Kelola Pesanan ID: <?= $order['id'] ?></h2>
    <form method="post">
        <label>Status</label>
        <select name="status">
            <option value="baru" <?= $order['status']=='baru'?'selected':'' ?>>Baru</option>
            <option value="proses" <?= $order['status']=='proses'?'selected':'' ?>>Proses</option>
            <option value="selesai" <?= $order['status']=='selesai'?'selected':'' ?>>Selesai</option>
            <option value="diambil" <?= $order['status']=='diambil'?'selected':'' ?>>Diambil</option>
        </select>
        <br><br>
        <button type="submit" name="update">Update</button>
    </form>
    <br>
    <a href="admin_dashboard.php">Kembali</a>
</body>
</html>
