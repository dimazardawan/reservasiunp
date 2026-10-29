<?php
session_start();
require '../config/db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        if ($data['role'] == 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../mahasiswa/dashboard.php");
        }
        exit;
    } else {
        $error = "Email atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login UNP</title>
    <link rel="stylesheet" href="../css/auth-style.css">
</head>
<body>

<div class="auth-card">
    <h2>Login</h2>

    <?php if (!empty($error)) echo "<p style='color:#ffdddd;text-align:center;'>$error</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email..." required>
        <input type="password" name="password" placeholder="Password..." required>

        <button type="submit" name="login">Masuk</button>
    </form>

    <a class="small-link" href="register.php">Belum punya akun?</a>
</div>

</body>
</html>
