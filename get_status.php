<?php
session_start();
include "connection.php";

if (!isset($_SESSION['email'])) {
    die(json_encode([]));
}

$email = $_SESSION['email'];
$user = $conn->query("SELECT id FROM tb_users WHERE email='$email'")->fetch_assoc();
$user_id = $user['id'];

$result = $conn->query("SELECT id, tanggal, jenis_laundry, berat, status FROM tb_orders WHERE user_id=$user_id ORDER BY id DESC");

$orders = [];
while($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);
?>
