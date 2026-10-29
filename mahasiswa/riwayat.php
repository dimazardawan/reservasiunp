<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../auth/login.php");
    exit;
}

require '../config/db.php';

$user_id = $_SESSION['user_id'];

// Ambil semua riwayat booking mahasiswa ini
$data = mysqli_query($conn,
    "SELECT r.*, a.nama_aula 
     FROM reservasi r
     JOIN aula a ON r.aula_id = a.id
     WHERE r.user_id='$user_id'
     ORDER BY r.tanggal DESC, r.jam_mulai DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Reservasi Aula UNP</title>
    <link rel="stylesheet" href="../css/style-modern-level2.css">


    <style>
        .riwayat-container {
            width: 900px;
            background: white;
            margin: 40px auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #95b8e0;
        }

        h2 {
            text-align: center;
            color: #2b4c7f;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #2b4c7f;
            color: white;
            padding: 10px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #d4dcea;
            text-align: center;
        }

        .status {
            padding: 6px 12px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }

        .pending {
            background: #ffb84f;
        }

        .approved {
            background: #4CAF50;
        }

        .rejected {
            background: #d9534f;
        }

        .download-link {
            padding: 7px 12px;
            background: #2b4c7f;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }

        .download-link:hover {
            background: #1d355d;
        }

        .back-btn {
            display: block;
            width: 180px;
            margin: 30px auto 0;
            padding: 12px;
            text-align: center;
            background: #2b4c7f;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }

        .back-btn:hover {
            background: #1d355d;
        }
    </style>
</head>
<body>

<div class="riwayat-container">
    <h2>📑 Riwayat Pengajuan Peminjaman Aula</h2>

    <table>
        <tr>
            <th>Aula</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Dokumen</th>
            <th>Status</th>
        </tr>

        <?php while ($d = mysqli_fetch_assoc($data)) { ?>
            <tr>
                <td><?php echo $d['nama_aula']; ?></td>
                <td><?php echo $d['tanggal']; ?></td>
                <td>
                    <?php echo substr($d['jam_mulai'],0,5) . " - " . substr($d['jam_selesai'],0,5); ?>
                </td>
                <td>
                    <?php if ($d['dokumen'] != "") { ?>
                        <a class="download-link" 
                           href="../uploads/dokumen/<?php echo $d['dokumen']; ?>" 
                           target="_blank">
                            Download
                        </a>
                    <?php } else { echo "-"; } ?>
                </td>

                <td>
                    <?php
                        $status = $d['status'];
                        $class = $status == "approved" ? "approved" : ($status == "rejected" ? "rejected" : "pending");
                        echo "<span class='status $class'>" . ucfirst($status) . "</span>";
                    ?>
                </td>
            </tr>
        <?php } ?>
    </table>

    <a class="back-btn" href="dashboard.php">⬅ Kembali ke Dashboard</a>
</div>

</body>
</html>
