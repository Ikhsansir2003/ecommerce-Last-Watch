<?php
session_start();
include 'php/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_order = isset($_GET['id_order']) ? intval($_GET['id_order']) : 0;

// Ambil data order
$q = mysqli_query($conn, "SELECT * FROM `order` WHERE id = $id_order LIMIT 1");
$order = mysqli_fetch_assoc($q);

// Validasi
if (!$order || $order['status'] != 'Pending') {
    echo "Pesanan tidak valid atau sudah dibayar.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Upload Bukti Pembayaran</title>

<style>
body {
    font-family: Arial;
    background: #f6f6f6;
}

.box {
    max-width: 400px;
    margin: 100px auto;
    background: #fff;
    padding: 25px;
    border-radius: 10px;
}

button {
    width: 100%;
    padding: 12px;
    background: #198754;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
</style>
</head>
<body>

<div class="box">
    <h3>Upload Bukti Pembayaran</h3>

    <form action="php/p_upload_bukti.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_order" value="<?= $order['id']; ?>">

        <label>File Bukti Bayar</label><br><br>
        <input type="file" name="bukti_bayar" required><br><br>

        <button type="submit">Upload</button>
    </form>
</div>

</body>
</html>
