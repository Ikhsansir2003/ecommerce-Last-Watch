<?php
session_start();
include '../php/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Produk</title>
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
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

input, textarea {
    width:100%;
    padding:12px;
    margin-top:10px;
    border-radius:6px;
    border:1px solid #ccc;
}

button {
    margin-top:15px;
    padding:12px;
    background:#198754;
    color:white;
    border:none;
    border-radius:6px;
    font-size:16px;
    cursor:pointer;
}

button:hover {
    background:#157347;
}
</style>
</head>

<body>
<div class="container">
<h2>Tambah Produk</h2>

<form action="proses_produk.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="aksi" value="tambah">

    <label>Nama Produk</label>
    <input type="text" name="nama_produk" required>

    <label>Harga</label>
    <input type="number" name="harga" required>

    <label>Deskripsi</label>
    <textarea name="deskripsi" rows="4"></textarea>

    <label>Gambar</label>
    <input type="file" name="gambar" required>

    <button>Simpan Produk</button>
</form>
</div>
</body>
</html>
