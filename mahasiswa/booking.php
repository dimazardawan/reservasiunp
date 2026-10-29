<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../auth/login.php");
    exit;
}

require '../config/db.php';

$user_id = $_SESSION['user_id'];

// Ambil tanggal & jam dari URL (kalender)
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date("Y-m-d");
$jam_mulai = isset($_GET['jam']) ? $_GET['jam'] : "07:00:00";
$jam_selesai = date("H:i:s", strtotime($jam_mulai) + 3600);

// Ambil daftar aula
$aula = mysqli_query($conn, "SELECT * FROM aula");

// Jika submit booking
if (isset($_POST['submit'])) {

    $aula_id    = $_POST['aula_id'];
    $tanggal    = $_POST['tanggal'];
    $jam_mulai  = $_POST['jam_mulai'];
    $jam_selesai= $_POST['jam_selesai'];

    // Upload dokumen
    $file_name = null;

    if ($_FILES["dokumen"]["name"] != "") {
        $ext  = pathinfo($_FILES["dokumen"]["name"], PATHINFO_EXTENSION);
        $file_name = "DOC-" . time() . "." . $ext;
        $upload_path = "../uploads/dokumen/" . $file_name;
        move_uploaded_file($_FILES["dokumen"]["tmp_name"], $upload_path);
    }

    // Cek bentrok jadwal
    $cek = mysqli_query($conn,
        "SELECT * FROM reservasi 
         WHERE aula_id='$aula_id'
         AND tanggal='$tanggal'
         AND (
                (jam_mulai <= '$jam_mulai' AND jam_selesai > '$jam_mulai') OR
                (jam_mulai < '$jam_selesai' AND jam_selesai >= '$jam_selesai')
             )"
    );

    if (mysqli_num_rows($cek) > 0) {
        $error = "⚠ Jadwal bentrok! Aula sudah dibooking pada jam tersebut.";
    } else {
        // Insert data
        mysqli_query($conn,
            "INSERT INTO reservasi (user_id, aula_id, tanggal, jam_mulai, jam_selesai, dokumen)
             VALUES ('$user_id', '$aula_id', '$tanggal', '$jam_mulai', '$jam_selesai', '$file_name')"
        );

        header("Location: riwayat.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Aula UNP</title>
    <link rel="stylesheet" href="../css/style-modern-level2.css">



    <style>
        .form-container {
            width: 550px;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #95b8e0;
        }

        h2 {
            text-align: center;
            color: #2b4c7f;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 18px;
            border-radius: 6px;
            border: 1px solid #bcd0e7;
        }

        button {
            width: 100%;
            background: #2b4c7f;
            padding: 12px;
            border: none;
            color: white;
            font-size: 17px;
            border-radius: 6px;
        }

        button:hover {
            background: #1d355d;
        }

        .error {
            background: #ffb4b4;
            padding: 12px;
            border-radius: 6px;
            color: #6d0808;
            margin-bottom: 20px;
            text-align: center;
        }

        .back-btn {
            display: block;
            width: 160px;
            margin: 0 auto;
            padding: 12px;
            background: #2b4c7f;
            color: white;
            text-align: center;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 25px;
        }

        .back-btn:hover {
            background: #1d355d;
        }
    </style>

</head>
<body>

<div class="form-container">
    <h2>📝 Form Pengajuan Peminjaman Aula UNP</h2>

    <?php
    if (!empty($error)) {
        echo "<div class='error'>$error</div>";
    }
    ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Pilih Aula</label>
        <select name="aula_id" required>
            <option value="">-- Pilih Aula --</option>
            <?php while ($d = mysqli_fetch_assoc($aula)) { ?>
                <option value="<?= $d['id']; ?>">
                    <?= $d['nama_aula']; ?>
                </option>
            <?php } ?>
        </select>

        <label>Tanggal</label>
        <input type="date" name="tanggal" value="<?php echo $tanggal; ?>" required>

        <label>Jam Mulai</label>
        <input type="time" name="jam_mulai" value="<?php echo $jam_mulai; ?>" required>

        <label>Jam Selesai</label>
        <input type="time" name="jam_selesai" value="<?php echo $jam_selesai; ?>" required>

        <label>Upload Dokumen (Surat Permohonan)</label>
        <input type="file" name="dokumen" accept=".pdf,.jpg,.png">

        <button type="submit" name="submit">Ajukan Peminjaman</button>
    </form>

    <a class="back-btn" href="dashboard.php">⬅ Kembali ke Dashboard</a>

</div>

</body>
</html>
