<?php
include "connection.php";

$id = $_GET['id'];
$status = $_GET['status'];

$stmt = $conn->prepare("UPDATE tb_laundry SET status=? WHERE id=?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();

header("Location: pesanan.php?status=" . $status);
exit;
?>
