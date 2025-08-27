<?php
session_start();
include "connection.php";

// pastikan user login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// ambil data user saat ini
$email = $_SESSION['email'];
$user_query = $conn->query("SELECT * FROM tb_users WHERE email='$email'");
$user = $user_query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Akun</title>
    <style>
        /* ===== BODY ===== */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url("Fashion Instagram story template and iPhone background fashion bloggers Instagram.jpeg");
            background-size: cover;
            background-position: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        /* ===== FORM ===== */
        form {
            background: rgba(255, 255, 255, 0.92);
            padding: 35px 40px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.25);
            width: 420px;
            backdrop-filter: blur(6px);
            animation: fadeIn 0.8s ease-in-out;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-size: 22px;
            font-weight: 600;
        }

        /* ===== FORM GROUP ===== */
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
        }

        .form-group label {
            width: 130px;
            font-weight: 600;
            font-size: 14px;
            color: #444;
        }

        .form-group input,
        .form-group textarea {
            flex-grow: 1;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 6px rgba(76, 175, 80, 0.4);
            outline: none;
        }

        textarea {
            height: 45px;
            resize: none;
        }

        /* ===== BUTTON ===== */
        button {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: transform 0.2s, background 0.3s;
        }

        button:hover {
            background: linear-gradient(135deg, #45a049, #3d8c40);
            transform: scale(1.03);
        }

        /* ===== RESPONSIVE ===== */
        @media screen and (max-width: 500px) {
            form {
                width: 90%;
                padding: 20px;
            }

            .form-group {
                flex-direction: column;
                align-items: stretch;
            }

            .form-group label {
                width: 100%;
                margin-bottom: 6px;
            }
        }

        /* Fade In Animasi */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <form method="post" action="update_settings.php">
        <h2>Pengaturan Akun</h2>
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama_lengkap" value="<?= $user['nama_lengkap'] ?>" required>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="<?= $user['email'] ?>" required>
        </div>

        <div class="form-group">
            <label>Alamat:</label>
            <textarea name="alamat" required><?= $user['alamat'] ?></textarea>
        </div>

        <div class="form-group">
            <label>Password Baru:</label>
            <input type="password" name="password">
        </div>

        <button type="submit" name="update">Simpan Perubahan</button>
    </form>
</body>
</html>
