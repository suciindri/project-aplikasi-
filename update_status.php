<?php
session_start();
include "connection.php";

if (!isset($_SESSION['email'])) die("❌ Harus login dulu");
$email = $_SESSION['email'];
$user = $conn->query("SELECT * FROM tb_users WHERE email='$email'")->fetch_assoc();
if ($user['role'] !== 'admin') die("❌ Hanya admin!");

$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
$status = isset($_POST['status']) ? trim($_POST['status']) : '';

$allowed_status = ["sedang di proses","sedang di cuci","selesai"];

if($order_id>0 && in_array($status, $allowed_status)){
    $stmt = $conn->prepare("UPDATE tb_orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit;
}else{
    echo "❌ Data tidak valid.<br>";
    echo "POST DITERIMA: "; print_r($_POST);
}
?>
