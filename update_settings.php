<?php
session_start();
include "connection.php";

// pastikan user login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$user_id = $_POST['id'] ?? null;
$nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
$email_baru = trim($_POST['email'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$password = $_POST['password'] ?? '';

// validasi sederhana
if (!$user_id || !$nama_lengkap || !$email_baru || !$alamat) {
    die("Data tidak lengkap!");
}

// validasi format email
if (!filter_var($email_baru, FILTER_VALIDATE_EMAIL)) {
    die("Format email tidak valid!");
}

// pastikan user_id milik user yang login
$stmt_check = $conn->prepare("SELECT id FROM tb_users WHERE id=? AND email=?");
$stmt_check->bind_param("is", $user_id, $email);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
if ($result_check->num_rows === 0) {
    die("ID user tidak valid!");
}
$stmt_check->close();

// update query
if (!empty($password)) {
    // jika password diisi, enkripsi dulu
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE tb_users SET nama_lengkap=?, email=?, alamat=?, password=? WHERE id=? AND email=?");
    $stmt->bind_param("ssssis", $nama_lengkap, $email_baru, $alamat, $password_hash, $user_id, $email);
} else {
    // password kosong, jangan update password
    $stmt = $conn->prepare("UPDATE tb_users SET nama_lengkap=?, email=?, alamat=? WHERE id=? AND email=?");
    $stmt->bind_param("sssis", $nama_lengkap, $email_baru, $alamat, $user_id, $email);
}

// eksekusi
if ($stmt->execute()) {
    // jika email user diubah, update session juga
    $_SESSION['email'] = $email_baru;
    echo "<script>alert('Data berhasil diperbarui'); window.location='pengaturan.php';</script>";
} else {
    echo "Gagal update: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
