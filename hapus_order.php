<?php
session_start();
include "connection.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM orders WHERE id=$id");
header("Location: admin.php");
exit;
?>
