<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require '../config/db.php';

$data = mysqli_query($conn,
    "SELECT r.*, u.nama, u.email, a.nama_aula 
     FROM reservasi r
     JOIN users u ON r.user_id = u.id
     JOIN aula a ON r.aula_id = a.id
     WHERE r.status = 'pending'
     ORDER BY r.tanggal ASC, r.jam_mulai ASC"
);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pending Reservasi Aula UNP</title>
    <link rel="stylesheet" href="../css/style-modern-level3.css">
</head>
<body>

<div class="navbar">Admin Panel — Reservasi Aula UNP</div>

<div class="super-card">

    <h2>📑 Daftar Pengajuan Pending Peminjaman Aula UNP</h2>

    <div class="table-wrapper">
        <table>
            <tr>
                <th>Nama Mahasiswa</th>
                <th>Email</th>
                <th>Aula</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Dokumen</th>
                <th>Aksi</th>
            </tr>

            <?php while($d = mysqli_fetch_assoc($data)) { ?>
            <tr>
                <td><?= $d['nama']; ?></td>
                <td><?= $d['email']; ?></td>
                <td><?= $d['nama_aula']; ?></td>
                <td><?= $d['tanggal']; ?></td>
                <td><?= substr($d['jam_mulai'],0,5)." - ".substr($d['jam_selesai'],0,5); ?></td>

                <td>
                    <a class="download-link"
                       target="_blank"
                       href="../uploads/dokumen/<?= $d['dokumen']; ?>">
                       Download
                    </a>
                </td>

                <td>
                    <div class="btn-action-box">
                        <a class="btn-approve" href="approve.php?id=<?= $d['id']; ?>">Approve</a>
                        <a class="btn-reject" href="reject.php?id=<?= $d['id']; ?>">Reject</a>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <a class="back-btn" href="dashboard.php">← Kembali ke Dashboard</a>

</div>

<div class="footer">© 2025 Sistem Reservasi Aula — Universitas Negeri Padang</div>

</body>
</html>
