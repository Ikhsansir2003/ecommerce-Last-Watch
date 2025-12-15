<?php
session_start();
include '../php/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
$p = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Produk</title>
<style>
body {
    font-family: Arial;
    background:#f4f6f8;
}

.container {
    max-width:600px;
    margin:60px auto;
    background:#fff;
    padding:30px;
    border-radius:12px;
}

input, textarea {
    width:100%;
    padding:12px;
    margin-top:10px;
}

button {
    margin-top:15px;
    padding:12px;
    background:#0d6efd;
    color:white;
    border:none;
    border-radius:6px;
}
</style>
</head>

<body>
<div class="container">
<h2>Edit Produk</h2>

<form action="proses_produk.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="aksi" value="edit">
    <input type="hidden" name="id" value="<?= $p['id']; ?>">
    <input type="hidden" name="gambar_lama" value="<?= $p['gambar']; ?>">

    <label>Nama Produk</label>
    <input type="text" name="nama_produk" value="<?= $p['nama_produk']; ?>">

    <label>Harga</label>
    <input type="number" name="harga" value="<?= $p['harga_produk']; ?>">


    <label>Deskripsi</label>
    <textarea name="deskripsi"><?= $p['deskripsi']; ?></textarea>

    <label>Ganti Gambar (Opsional)</label>
    <input type="file" name="gambar">

    <button>Update Produk</button>
</form>
</div>
</body>
</html>
