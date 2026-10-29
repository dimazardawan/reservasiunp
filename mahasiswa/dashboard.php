<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../auth/login.php");
    exit;
}

$nama = $_SESSION['nama'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Mahasiswa - Reservasi Aula UNP</title>
    <link rel="stylesheet" href="../css/style-modern-level2.css">


    <style>
        .menu-container {
            width: 500px;
            margin: 60px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #95b8e0;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2b4c7f;
        }

        .menu-btn {
            display: block;
            width: 100%;
            padding: 14px;
            text-align: center;
            background: #2b4c7f;
            color: white;
            text-decoration: none;
            font-size: 17px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .menu-btn:hover {
            background: #1d355d;
        }

        .logout {
            background: #d9534f;
        }

        .logout:hover {
            background: #b52b27;
        }
    </style>
</head>
<body>

<div class="menu-container">
    <h2>Halo, <?php echo $nama; ?> 👋</h2>

    <a class="menu-btn" href="kalender.php">📅 Lihat Kalender Ketersediaan Aula</a>

    <a class="menu-btn" href="booking.php">📝 Ajukan Peminjaman Aula</a>

    <a class="menu-btn" href="riwayat.php">📑 Riwayat Pengajuan</a>

    <a class="menu-btn logout" href="../auth/logout.php">🚪 Logout</a>
</div>

</body>
</html>
