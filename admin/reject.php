<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require '../config/db.php';

// Pastikan ada ID yang dikirim
if (!isset($_GET['id'])) {
    header("Location: reservasi.php");
    exit;
}

$id = $_GET['id'];

// Update status menjadi REJECTED
mysqli_query($conn, "UPDATE reservasi SET status='rejected' WHERE id='$id'");

// Kembali ke halaman pending
header("Location: reservasi.php");
exit;
?>
