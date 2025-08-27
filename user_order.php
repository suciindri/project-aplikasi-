<?php
session_start();
include "connection.php";

// cek login user
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// ambil data user dari session
$email = $_SESSION['email'];
$user = $conn->query("SELECT * FROM tb_users WHERE email='$email'")->fetch_assoc();
$user_id = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['customer_name'];
    $jenis_laundry = $_POST['jenis_laundry'];
    $status = "sedang di proses";

    $stmt = $conn->prepare("INSERT INTO tb_orders (user_id, customer_name, jenis_laundry, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $customer_name, $jenis_laundry, $status);
    if($stmt->execute()){
        echo "✅ Pesanan berhasil dibuat!";
    } else {
        echo "❌ Gagal membuat pesanan: ".$conn->error;
    }
}
?>

<h2>Buat Pesanan Laundry</h2>
<form method="POST">
    Nama Customer: <input type="text" name="customer_name" required><br><br>
    Jenis Laundry: <input type="text" name="jenis_laundry" required><br><br>
    <button type="submit">Pesan</button>
</form>
