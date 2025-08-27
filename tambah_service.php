<?php
session_start();
include "connection.php";

// Pastikan user login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Cek apakah ada parameter jenis laundry dari link
if (!isset($_GET['jenis'])) {
    die("Jenis laundry tidak ditentukan.");
}

$jenisLaundry = $_GET['jenis'];

// Masukkan ke database tb_service
$query = "INSERT INTO tb_service (jenis_laundry) VALUES (?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $jenisLaundry);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: dashboard.php?success=Service+berhasil+ditambahkan");
    exit;
} else {
    die("Gagal menambahkan service: " . $conn->error);
}
