<?php
session_start();
include "connection.php";

$error = "";

if (isset($_POST['login'])) {
    $email          = strtolower(trim(mysqli_real_escape_string($conn, $_POST['email'])));
    $password_input = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM tb_users WHERE email='$email' LIMIT 1");
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password_input, $row['password'])) {
        // simpan session
        $_SESSION['id']           = $row['id'];
        $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
        $_SESSION['email']        = $row['email'];
        $_SESSION['role']         = $row['role'];

        // arahkan sesuai role
        if ($row['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit;
    } else {
        $error = "❌ Email atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Form Login</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
    <p>Belum punya akun? <a href="register.php">Daftar</a></p>
</body>
</html>
