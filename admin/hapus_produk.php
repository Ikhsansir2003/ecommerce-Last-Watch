<?php
session_start();
include '../php/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = intval($_GET['id']);

$q = mysqli_query($conn, "SELECT gambar FROM produk WHERE id=$id");
$data = mysqli_fetch_assoc($q);

if ($data['gambar'] != "") {
    unlink("../img/".$data['gambar']);
}

mysqli_query($conn, "DELETE FROM produk WHERE id=$id");

header("Location: produk.php");
exit();
