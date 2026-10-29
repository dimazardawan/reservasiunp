<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require '../config/db.php';

// Pastikan ada ID peminjaman
if (!isset($_GET['id'])) {
    header("Location: reservasi.php");
    exit;
}

$id = $_GET['id'];

// Update status menjadi APPROVED
mysqli_query($conn, "UPDATE reservasi SET status='approved' WHERE id='$id'");

// Kembali ke halaman pending
header("Location: reservasi.php");
exit;
?>
