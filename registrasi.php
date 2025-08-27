<?php
session_start();
include "connection.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Ambil dan validasi input
    $nama_lengkap = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';
    $email        = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
    $password     = isset($_POST['password']) ? $_POST['password'] : '';
    $gender       = isset($_POST['gender']) ? $_POST['gender'] : '';
    $alamat       = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
    $role         = "user"; // default user

    // Validasi
    if (!$nama_lengkap || !$email || !$password || !$gender || !$alamat) {
        $error = "❌ Semua data wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ Format email tidak valid!";
    } elseif (!in_array($gender, ["laki-laki", "perempuan"])) {
        $error = "❌ Gender tidak valid!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // cek email sudah ada atau belum
        $check = $conn->prepare("SELECT id FROM tb_users WHERE email=? LIMIT 1");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "❌ Email sudah terdaftar!";
        } else {
            $stmt = $conn->prepare("INSERT INTO tb_users (nama_lengkap, email, password, role, gender, alamat) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $nama_lengkap, $email, $hashedPassword, $role, $gender, $alamat);

            if ($stmt->execute()) {
                // Ambil ID user yang baru didaftarkan
                $user_id = $stmt->insert_id;

                // Set session langsung
                $_SESSION['id'] = $user_id;
                $_SESSION['nama_lengkap'] = $nama_lengkap;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;

                // Redirect langsung ke dashboard user
                header("Location: dashboard.php");
                exit;

            } else {
                $error = "❌ Gagal registrasi!";
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTRASI</title>
    <link rel="stylesheet" href="style regis.css">
</head>
<body>
    <h1>REGISTRASI</h1>
    <div class="register">
        <form action="" method="post" autocomplete="off">
            <div class="registrasi">
                <label for="nama_lengkap">Nama lengkap</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" placeholder="masukkan nama anda" required>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="masukkan email anda" autocomplete="off" required>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="silakan isi password anda" autocomplete="off" required>
                
                <label>Gender</label>
                <div class="gender-op">
                    <label><input type="radio" name="gender" value="laki-laki" required> Laki-laki</label>
                    <label><input type="radio" name="gender" value="perempuan" required> Perempuan</label>  
                </div>
                
                <label for="alamat">Alamat</label>
                <input type="text" name="alamat" id="alamat" placeholder="masukkan alamat anda" required>
            </div>
            <input type="submit" name="register" value="Registrasi">
        </form>
        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
