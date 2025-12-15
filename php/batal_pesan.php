<?php
session_start();
include 'koneksi.php';

if (!isset($_POST['id_order'])) {
    header("Location: ../pesanan.php");
    exit();
}

$id = $_POST['id_order'];

mysqli_query($conn, "UPDATE `order` SET status='Cancel' WHERE id=$id");

echo "<script>alert('Pesanan berhasil dibatalkan'); window.location='../pesanan.php';</script>";
?>
