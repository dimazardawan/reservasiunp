<?php
require '../config/db.php';

if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Email sudah terdaftar!";
    } else {
        mysqli_query($conn, "INSERT INTO users (nama, email, password, role)
                             VALUES ('$nama', '$email', '$password', 'mahasiswa')");
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register UNP</title>
    <link rel="stylesheet" href="../css/auth-style.css">
</head>
<body>

<div class="auth-card">
    <h2>Register</h2>

    <?php if (!empty($error)) echo "<p style='color:#ffdddd;text-align:center;'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="nama" placeholder="Nama lengkap..." required>
        <input type="email" name="email" placeholder="Email..." required>
        <input type="password" name="password" placeholder="Password..." required>

        <button type="submit" name="register">Daftar</button>
    </form>

    <a class="small-link" href="login.php">Sudah punya akun?</a>
</div>

</body>
</html>
