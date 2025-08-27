<?php
session_start();
include "connection.php";

if (!isset($_SESSION['email'])) die("❌ Harus login dulu");
$email = $_SESSION['email'];
$user = $conn->query("SELECT * FROM tb_users WHERE email='$email'")->fetch_assoc();
if ($user['role'] !== 'admin') die("❌ Hanya admin!");

$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;

if($order_id>0){
    $stmt = $conn->prepare("DELETE FROM tb_orders WHERE id=?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit;
}else{
    echo "❌ Pesanan tidak valid.";
}
?>
