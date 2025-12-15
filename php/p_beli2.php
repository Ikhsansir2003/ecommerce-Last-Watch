<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu'); 
          window.location='../login.php';</script>";
    exit;
}

if (!isset($_POST['produk_id']) || !isset($_POST['jumlah'])) {
    echo "<script>alert('Data tidak lengkap'); 
          window.location='../index.php';</script>";
    exit;
}

$produk_id = intval($_POST['produk_id']);
$jumlah = intval($_POST['jumlah']);

$query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $produk_id LIMIT 1");

if (mysqli_num_rows($query) == 0) {
    echo "<script>alert('Produk tidak ditemukan'); 
          window.location='../index.php';</script>";
    exit;
}

$produk = mysqli_fetch_assoc($query);
$total = $produk['harga_produk'] * $jumlah;

// SIMPAN KE SESSION
$_SESSION['checkout'] = [
    "produk_id"   => $produk['id'],
    "nama"        => $produk['nama_produk'],
    "harga"       => $produk['harga_produk'],
    "jumlah"      => $jumlah,
    "total"       => $total
];

// Redirect
header("Location: ../beli.php");
exit;
?>
