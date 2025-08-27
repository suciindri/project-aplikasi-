<?php
include "connection.php";

$nama_lengkap = "suci sirait";
$email = "sucisirait10@gmail.com";
$password = password_hash("suci10", PASSWORD_DEFAULT); 
$role = "admin";

// cek apakah admin sudah ada
$cek = mysqli_query($conn, "SELECT * FROM tb_users WHERE email='$email'");
if(mysqli_num_rows($cek) > 0){
    echo "Admin sudah ada!";
} else {
    $query = "INSERT INTO tb_users (nama_lengkap, email, password, role) 
              VALUES ('$nama_lengkap', '$email', '$password', '$role')";
    if(mysqli_query($conn, $query)){
        echo "Admin berhasil dibuat!";
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }
}
?>
