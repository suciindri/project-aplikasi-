<?php
session_start();
include "connection.php";

if (!isset($_SESSION['email'])) {
    die("Silakan login dulu.");
}

$email = $_SESSION['email'];
$user_query = $conn->query("SELECT id, nama_lengkap FROM tb_users WHERE email='$email'");
$user = $user_query->fetch_assoc();
$user_id = $user['id'];
$customer_name = $user['nama_lengkap'];

$error = "";

if (isset($_POST['send'])) {
    $berat      = $_POST['berat'];              // dari hidden input
    $service_id = $_POST['service_id'];         // dropdown
    $tanggal    = date("Y-m-d H:i:s");          // datetime valid
    $status     = "baru";

    $harga_perkg = 5000; 
    $total_harga = $berat * $harga_perkg;

    $sql = "INSERT INTO tb_orders 
            (customer_name, user_id, service_id, tanggal, berat, total_harga, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siisiis", 
        $customer_name, 
        $user_id, 
        $service_id, 
        $tanggal, 
        $berat, 
        $total_harga, 
        $status
    );

    if ($stmt->execute()) {
        echo "<p style='color:green'>Pesanan berhasil disimpan!</p>";
    } else {
        echo "<p style='color:red'>Error: " . $stmt->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pesan Laundry</title>
<style>
body { font-family: Arial; text-align: center; background: #f7f7f7; margin:0; padding:0; }
.box { background: #fff; width: 50%; margin: 80px auto; border: 2px solid #333; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
h2 { margin-bottom: 20px; }
button { font-size: 20px; padding: 5px 15px; margin: 0 5px; cursor: pointer; }
.jumlah { font-size: 20px; margin: 0 10px; font-weight: bold; }
input[type="submit"] { margin-top: 20px; padding: 10px 20px; font-size: 18px; border: none; border-radius: 8px; cursor: pointer; background: #28a745; color: #fff; transition: 0.3s; }
input[type="submit"]:hover { background: #218838; }
.error { color: red; }
</style>
</head>
<body>

<div class="box">
    <h2>Pesan Laundry</h2>

    <form method="POST" action="">
        <p>Jenis Laundry:</p>
        <select name="service_id" required>
            <option value="1">Cuci dan Setrika</option>
            <option value="2">Cuci Saja</option>
            <option value="3">Setrika Saja</option>
        </select>

        <p>
            Jumlah (kg):
            <button type="button" onclick="kurang()">-</button>
            <span class="jumlah" id="jumlahKg">0</span>
            <button type="button" onclick="tambah()">+</button>
        </p>

        <p>Total harga: Rp <span id="totalHarga">0</span></p>

        <!-- hidden input untuk dikirim ke PHP -->
        <input type="hidden" name="berat" id="jumlahKgInput" required>
        <input type="hidden" name="total_harga" id="totalHargaInput" required>

        <input type="submit" name="send" value="Kirim">
    </form>
</div>

<script>
let jumlah = 0;
let hargaPerKg = 5000;

function tambah() { jumlah++; updateDisplay(); }
function kurang() { if(jumlah > 0){ jumlah--; updateDisplay(); } }

function updateDisplay() {
    document.getElementById("jumlahKg").innerText = jumlah;
    document.getElementById("totalHarga").innerText = jumlah * hargaPerKg;

    document.getElementById("jumlahKgInput").value = jumlah;
    document.getElementById("totalHargaInput").value = jumlah * hargaPerKg;
}

// Sync awal
updateDisplay();
</script>

</body>
</html>
