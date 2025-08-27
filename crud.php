<?php
session_start();
include "connection.php";

// Cek role admin
if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak");
}

// READ data
$result = mysqli_query($conn, "SELECT * FROM tb_users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Halaman Admin</title>
</head>
<body>
    <h2>Manajemen User</h2>
    <a href="tambah_user.php">Tambah User</a>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['nama_lengkap']; ?></td>
            <td><?= $row['email']; ?></td>
            <td><?= $row['role']; ?></td>
            <td>
                <a href="edit_user.php?id=<?= $row['id']; ?>">Edit</a> | 
                <a href="hapus_user.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
