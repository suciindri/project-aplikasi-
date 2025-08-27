<?php
session_start();

// cek apakah sudah login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$nama = $_SESSION['nama_lengkap'];
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LUCY LAUNDRY</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
    }
    header {
        background-image: url("dashboard.jpeg");
        color: black;
        padding: 200px;
        text-align: center;

    }
    .header2{
        position: absolute;
        top: 4%;
        left: 40%;

    }
    .menu-container {
        display: flex;
        overflow-x: auto;
        padding: 10px;
        background: #ece6e6ff;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        cursor: grab;
    }

   .menu-item {
            display: inline-block;
            flex: 0 0 auto;
            width: 20%;
            text-align: center;
            background-color: #eee;
            padding: 10px 20px;
            margin-right: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            scroll-snap-align: start;
            white-space: nowrap;
            text-decoration: none; /* hilangkan underline */
            color: black;
            transition: transform 0.2s ease;
    }
    .menu-item:hover {
        transform: scale(1.05);
        background-color: #f9f9f9;
    }
    main {
        background-image: url("cream.jpeg");
        padding: 20px;
    }
    h1{
        text-align: center;
    }
    .menu2-container {
        background-color: #ece6e6ff;
        display: flex;
        overflow-x: auto;
        padding: 10px;
        scroll-snap-type:proximity;
        -webkit-overflow-scrolling: touch;
        cursor: pointer;
    }

    .menu-product{
        margin-bottom: 0%;
        margin-right: 10px;
        flex: 0 0 auto;
        padding: 10px 20px;
        transition: all;
        border-radius: 10px;
        box-shadow: 2px 2px 2px 2px;
        white-space: nowrap;
        transition: transform 0.4s ease;
        text-decoration: none;
    }
    .menu-product{
        width: 20%;
    }
    .menu-product:hover{
        transform: scale(1.05);
        background-color: white;
    }
</style>
<body>
<header>
    <p class="header2">Halo, <?php echo htmlspecialchars($nama); ?> | 
    <a href="logout.php" style="color:cadeblue;" class="logout">Logout</a></p>
</header>

<div class="menu-container">
    <a href="cuci baju.php?service_id=1" class="menu-item">Hanya Mencuci</a>
    <a href="cuci dan strika.php" class="menu-item">Cuci Dan Strika</a>
    <a href="strika saja.php" class="menu-item">Strika Saja</a>
    <a href="cuci lipat.php" class="menu-item"> Cuci Dan Lipat</a>
</div>

<main>
    <h1>LUCY LAUNDRY</h1>
    <div class="menu2-container">
        <a href="pesanan.php" class="menu-product">pesanan Baru</a>
        <a href="selesai.php" class="menu-product">Selesai</a>
        <a href="riwayat.php" class="menu-product">Riwayat</a>
        <a href="settings.php" class="menu-product">Pengaturan</a>
    </div>
</main>
</body>
</html>
