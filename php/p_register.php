<?php
include 'koneksi.php'; // Menghubungkan ke file koneksi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Password tanpa hashing

    // Query untuk memeriksa apakah email sudah ada
    $checkEmailSql = "SELECT * FROM user WHERE email='$email'";
    $result = $conn->query($checkEmailSql);

    if ($result->num_rows > 0) {
        // Jika email sudah ada, tampilkan pesan error dalam bentuk pop-up dan refresh halaman
        echo "<script>
            window.location.href = 'register.php'; // Ganti dengan nama file form registrasi Anda
            alert('Email sudah terdaftar. Silakan gunakan email lain.');

        </script>";
    } else {
        // Jika email belum terdaftar, masukkan data ke tabel users
        $sql = "INSERT INTO user (nama, email, password) VALUES ('$nama', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            // Redirect ke halaman login setelah registrasi berhasil
            header("Location: ../login.php");
            exit(); // Menghentikan eksekusi script setelah redirect
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close(); // Menutup koneksi
}
?>
