<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Ambil user berdasarkan email
    $query = mysqli_query($conn, "
        SELECT * FROM user 
        WHERE email = '$email'
        LIMIT 1
    ");

    if (mysqli_num_rows($query) == 1) {

        $user = mysqli_fetch_assoc($query);

        // Cocokkan password (TANPA hashing sesuai kode kamu)
        if ($password === $user['password']) {

            // Set session
            $_SESSION['id_user'] = $user['id'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role'];

            // Redirect berdasarkan role
            if ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../index.php");
            }
            exit();

        } else {
            header("Location: ../login.php?error=password_salah");
            exit();
        }

    } else {
        header("Location: ../login.php?error=email_tidak_ditemukan");
        exit();
    }
}
?>
