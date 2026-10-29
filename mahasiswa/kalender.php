<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../auth/login.php");
    exit;
}

require '../config/db.php';

// Ambil bulan & tahun saat ini
$bulan = date("m");
$tahun = date("Y");

// Hari pertama & jumlah hari
$firstDay = mktime(0, 0, 0, $bulan, 1, $tahun);
$namaBulan = date("F", $firstDay);
$jumlahHari = date("t", $firstDay);
$startDay = date("w", $firstDay); // 0 = Minggu

?>
<!DOCTYPE html>
<html>
<head>
    <title>Kalender Reservasi Aula UNP</title>
    <link rel="stylesheet" href="../css/style-modern-level2.css">



    <style>
        .calendar-container {
            width: 750px;
            background: white;
            margin: 40px auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #95b8e0;
        }

        h2 {
            text-align: center;
            color: #2b4c7f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th {
            background: #2b4c7f;
            color: white;
            padding: 10px;
        }

        td {
            width: 14%;
            height: 80px;
            text-align: center;
            vertical-align: top;
            border: 1px solid #d0d8e6;
            padding-top: 6px;
            cursor: pointer;
        }

        td:hover {
            background: #e0ebff;
        }

        .today {
            background: #bcd0f7;
            font-weight: bold;
        }

        .jadwal-box {
            margin-top: 30px;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px #95b8e0;
        }

        .jam-item {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .kosong {
            background: #b9e6c9;
            color: #1a4d2e;
        }

        .penuh {
            background: #ffb4b4;
            color: #6d0808;
        }

        .btn-book {
            display: inline-block;
            padding: 7px 14px;
            background: #2b4c7f;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }

        .btn-book:hover {
            background: #1d355d;
        }

        .back-btn {
            display: block;
            width: 160px;
            margin: 25px auto;
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

<div class="calendar-container">
    <h2>📅 Kalender Reservasi Aula UNP — <?php echo "$namaBulan $tahun"; ?></h2>

    <table>
        <tr>
            <th>Minggu</th>
            <th>Senin</th>
            <th>Selasa</th>
            <th>Rabu</th>
            <th>Kamis</th>
            <th>Jumat</th>
            <th>Sabtu</th>
        </tr>

        <tr>
        <?php
        // Cetak sel kosong sebelum tanggal 1
        for ($i = 0; $i < $startDay; $i++) {
            echo "<td></td>";
        }

        // Cetak tanggal 1 - jumlah hari
        for ($hari = 1; $hari <= $jumlahHari; $hari++) {

            $tanggalSekarang = "$tahun-$bulan-" . str_pad($hari, 2, '0', STR_PAD_LEFT);
            $classToday = ($tanggalSekarang == date("Y-m-d")) ? "today" : "";

            echo "<td class='$classToday' onclick=\"window.location='kalender.php?tanggal=$tanggalSekarang'\">$hari</td>";

            // Pindah baris tiap hari Minggu
            if (($hari + $startDay) % 7 == 0) {
                echo "</tr><tr>";
            }
        }

        echo "</tr>";
        ?>
    </table>

    <a class="back-btn" href="dashboard.php">⬅ Kembali ke Dashboard</a>
</div>

<?php
// Jika user memilih tanggal
if (isset($_GET['tanggal'])) {
    $tgl = $_GET['tanggal'];

    echo "<div class='calendar-container jadwal-box'>";
    echo "<h2>Jadwal Tanggal: $tgl</h2>";

    // Loop jam 07.00 - 17.00
    for ($jam = 7; $jam <= 17; $jam++) {

        $jam_mulai = str_pad($jam, 2, "0", STR_PAD_LEFT) . ":00:00";
        $jam_selesai = str_pad($jam+1, 2, "0", STR_PAD_LEFT) . ":00:00";

        // cek apakah slot jam ini sudah dibooking
        $cek = mysqli_query($conn,
            "SELECT * FROM reservasi 
             WHERE tanggal='$tgl'
             AND (
                    (jam_mulai <= '$jam_mulai' AND jam_selesai > '$jam_mulai') OR
                    (jam_mulai < '$jam_selesai' AND jam_selesai >= '$jam_selesai')
                 )"
        );

        $ada = mysqli_num_rows($cek);

        if ($ada > 0) {
            echo "<div class='jam-item penuh'>
                    <b>$jam:00 - " . ($jam+1) . ":00</b> — Sudah dibooking
                  </div>";
        } else {
            echo "<div class='jam-item kosong'>
                    <b>$jam:00 - " . ($jam+1) . ":00</b> — Kosong 
                    <a class='btn-book' href='booking.php?tanggal=$tgl&jam=$jam_mulai'>
                        Booking
                    </a>
                  </div>";
        }

    }

    echo "</div>";
}
?>

</body>
</html>
