<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require '../config/db.php';

// Hitung total peminjaman
$pending   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS jml FROM reservasi WHERE status='pending'"))['jml'];
$approved  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS jml FROM reservasi WHERE status='approved'"))['jml'];
$rejected  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS jml FROM reservasi WHERE status='rejected'"))['jml'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin - Reservasi Aula UNP</title>
    <link rel="stylesheet" href="../css/style-modern-level2.css">



    <style>
        .admin-container {
            width: 900px;
            background: white;
            margin: 40px auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 12px #95b8e0;
        }

        h2 {
            text-align: center;
            color: #2b4c7f;
            margin-bottom: 25px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-top: 25px;
        }

        .box {
            background: #eaf3ff;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 0px 8px #bcd0e7;
        }

        .box h3 {
            margin: 0;
            font-size: 22px;
            color: #2b4c7f;
        }

        .box p {
            font-size: 30px;
            margin-top: 10px;
            font-weight: bold;
            color: #1e335c;
        }

        .btn-menu {
            display: block;
            width: 260px;
            margin: 30px auto;
            background: #2b4c7f;
            color: white;
            padding: 14px;
            font-size: 18px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
        }

        .btn-menu:hover {
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

<div class="admin-container">
    <h2>📊 Dashboard Admin — Sistem Reservasi Aula UNP</h2>

    <div class="grid">

        <div class="box">
            <h3>Pending</h3>
            <p><?php echo $pending; ?></p>
        </div>

        <div class="box">
            <h3>Disetujui</h3>
            <p><?php echo $approved; ?></p>
        </div>

        <div class="box">
            <h3>Ditolak</h3>
            <p><?php echo $rejected; ?></p>
        </div>

    </div>

    <a class="btn-menu" href="reservasi.php">📑 Lihat Pengajuan Peminjaman</a>

    <a class="btn-menu logout" href="../auth/logout.php">🚪 Logout</a>
</div>

</body>
</html>
