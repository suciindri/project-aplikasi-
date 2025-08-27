<?php
session_start();
include "connection.php";

if(!isset($_SESSION['email'])) die("Silakan login dulu.");

$email = $_SESSION['email'];
$user = $conn->query("SELECT id, nama_lengkap FROM tb_users WHERE email='$email'")->fetch_assoc();
$user_id = $user['id'];
$customer_name = $user['nama_lengkap'];

// Ambil data service untuk dropdown
$services = $conn->query("SELECT * FROM tb_service");

// Proses submit pesanan
if (isset($_POST['send'])) {
    $berat         = $_POST['berat'];
    $jenis_laundry = trim($_POST['jenis_laundry']);
    $tanggal       = date("Y-m-d H:i:s");
    $status        = "baru";

    $harga_perkg   = 8000;
    $total_harga   = $berat * $harga_perkg;

    // Cek apakah jenis laundry sudah ada di tb_service
    $check = $conn->prepare("SELECT id FROM tb_service WHERE jenis_laundry = ?");
    $check->bind_param("s", $jenis_laundry);
    $check->execute();
    $check->store_result();

    if ($check->num_rows == 0) {
        // Insert ke tb_service jika belum ada
        $insert_service = $conn->prepare("INSERT INTO tb_service (jenis_laundry) VALUES (?)");
        $insert_service->bind_param("s", $jenis_laundry);
        $insert_service->execute();
    }

    // Insert ke tb_orders
    $sql = "INSERT INTO tb_orders (customer_name, user_id, jenis_laundry, tanggal, berat, total_harga, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissiis", $customer_name, $user_id, $jenis_laundry, $tanggal, $berat, $total_harga, $status);

    if ($stmt->execute()) {
        header("Location: pesanan.php");
        exit;
    } else {
        echo "<p style='color:red'>Error: " . $stmt->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<head>
    <style>
        /* Global reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url("Fashion Instagram story template and iPhone background fashion bloggers Instagram.jpeg") no-repeat center center/cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        form {
            background-image: url("strika.jpeg");
            padding: 25px 30px;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        p {
            margin: 15px 0 8px;
            font-weight: bold;
            color: #444;
        }

        select, input[type=text] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 10px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 14px;
        }

        select:focus, input[type=text]:focus {
            border-color: #5a67d8;
            box-shadow: 0 0 6px rgba(90,103,216,0.5);
        }

        .jumlah-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }

        .jumlah-wrapper button {
            width: 35px;
            height: 35px;
            border: none;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            background: #5a67d8;
            color: white;
            transition: 0.2s;
        }

        .jumlah-wrapper button:hover {
            background: #434190;
        }

        .jumlah {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .harga {
            margin: 10px 0 20px;
            font-weight: bold;
            color: #e53e3e;
            font-size: 16px;
        }

        input[type=submit] {
            width: 100%;
            padding: 12px;
            background: #48bb78;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        input[type=submit]:hover {
            background: #2f855a;
        }
    </style>
<body> 
<form method="POST" action="">
    <p>Jenis Laundry:</p>
    <select name="jenis_laundry" required>
        <option value="">-- Pilih Jenis Laundry --</option>
        <?php while($s = $services->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($s['jenis_laundry']) ?>">
                <?= htmlspecialchars($s['jenis_laundry']) ?>
            </option>
        <?php endwhile; ?>
        <option value="baru">+ Tambah Jenis Baru</option>
    </select>

    <!-- Input manual muncul kalau pilih 'baru' -->
    <div id="inputBaru" style="display:none; margin-top:10px;">
        <input type="text" name="jenis_laundry_baru" placeholder="Masukkan jenis laundry baru">
    </div>

    <p>
    Jumlah (kg):
    <button type="button" onclick="kurang()">-</button>
    <span class="jumlah" id="jumlahKg">0</span>
    <button type="button" onclick="tambah()">+</button>
    </p>

    <p>Total harga: Rp <span id="totalHarga">0</span></p>

    <input type="hidden" name="berat" id="jumlahKgInput" required>
    <input type="hidden" name="total_harga" id="totalHargaInput" required>

    <input type="submit" name="send" value="Kirim">
</form>

<script>
let jumlah = 0;
let hargaPerKg = 8000;

function tambah() { jumlah++; updateDisplay(); }
function kurang() { if(jumlah>0){ jumlah--; updateDisplay(); } }

function updateDisplay() {
    document.getElementById("jumlahKg").innerText = jumlah;
    document.getElementById("totalHarga").innerText = jumlah * hargaPerKg;
    document.getElementById("jumlahKgInput").value = jumlah;
    document.getElementById("totalHargaInput").value = jumlah * hargaPerKg;
}

updateDisplay();

// Toggle input baru
document.querySelector("select[name=jenis_laundry]").addEventListener("change", function() {
    if (this.value === "baru") {
        document.getElementById("inputBaru").style.display = "block";
        this.removeAttribute("name");
        document.querySelector("input[name=jenis_laundry_baru]").setAttribute("name","jenis_laundry");
    } else {
        document.getElementById("inputBaru").style.display = "none";
        this.setAttribute("name","jenis_laundry");
        document.querySelector("input[name=jenis_laundry]").setAttribute("name","jenis_laundry_baru");
    }
});
</script>
</body>
</head>
</html>
