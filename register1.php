<?php
session_start();
include "connection.php";

$error = "";
$success = "";

if (isset($_POST['register'])) {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email        = strtolower(trim(mysqli_real_escape_string($conn, $_POST['email'])));
    $password     = $_POST['password'];
    $role         = "user"; // default role user

    // cek email sudah terdaftar atau belum
    $cek = mysqli_query($conn, "SELECT * FROM tb_users WHERE email='$email' LIMIT 1");
    if (mysqli_num_rows($cek) > 0) {
        $error = "❌ Email sudah terdaftar!";
    } else {
        // hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // simpan ke database
        $query = "INSERT INTO tb_users (nama_lengkap, email, password, role) 
                  VALUES ('$nama_lengkap', '$email', '$hashedPassword', '$role')";
        if (mysqli_query($conn, $query)) {
            $success = "✅ Registrasi berhasil, silakan login!";
        } else {
            $error = "❌ Registrasi gagal: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi</title>
</head>
<body>
    <h2>Form Registrasi</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <form method="POST">
        <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="register">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login</a></p>
</body>
</html>
