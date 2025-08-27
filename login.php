<?php
session_start();
include "connection.php";

$error = "";

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validasi input
    if (!$email || !$password) {
        $error = "❌ Email dan Password wajib diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ Format email tidak valid!";
    } else {
        // Cek user di database
        $stmt = $conn->prepare("SELECT id, nama_lengkap, email, password, role FROM tb_users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            // Verifikasi password (hashed)
            if (password_verify($password, $row['password'])) {
                // Set session
                $_SESSION['id']           = $row['id'];
                $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
                $_SESSION['email']        = $row['email'];
                $_SESSION['role']         = $row['role'];

                // Redirect sesuai role
                if ($row['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                    exit;
                } else {
                    header("Location: dashboard.php");
                    exit;
                }
            } else {
                $error = "❌ Email atau Password salah!";
            }
        } else {
            $error = "❌ Email atau Password salah!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LOGIN</title>
<link rel="stylesheet" href="stylelogin.css">
</head>
<body>
<h1>LOGIN</h1>
<div class="login">
    <form action="" method="post" autocomplete="off">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" name="login" value="Login" class="kirim">
    </form>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
</div>
<p class="p">Belum memiliki akun? <a href="registrasi.php">Registrasi</a></p>
</body>
</html>
