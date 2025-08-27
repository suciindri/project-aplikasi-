<?php
session_start();
include "connection.php";

if (!isset($_SESSION['email'])) die("❌ Harus login dulu");
$email = $_SESSION['email'];
$user = $conn->query("SELECT * FROM tb_users WHERE email='$email'")->fetch_assoc();
if ($user['role'] !== 'admin') die("❌ Hanya admin!");

$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

if($user_id>0){
    $stmt = $conn->prepare("DELETE FROM tb_users WHERE id=? AND role!='admin'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit;
}else{
    echo "❌ User tidak valid.";
}
?>
