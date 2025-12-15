<?php
session_start();
include '../php/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$qProduk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin | Produk</title>

<style>
body {
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background:#f4f6f8;
}

/* NAVBAR */
.navbar {
    background:#bf9455;
    padding:15px 30px;
    color:#fff;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.navbar a {
    color:white;
    text-decoration:none;
    margin-left:20px;
    font-weight:bold;
}

/* CONTAINER */
.container {
    padding:30px;
}

/* HEADER */
.header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.header h2 {
    margin:0;
}

.add-btn {
    padding:10px 18px;
    background:#198754;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
}

.add-btn:hover {
    background:#157347;
}

/* TABLE */
.table-box {
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    overflow-x:auto;
}

table {
    width:100%;
    border-collapse:collapse;
}

th, td {
    padding:14px;
    text-align:left;
}

th {
    background:#f0f0f0;
    font-size:14px;
}

tr {
    border-bottom:1px solid #eee;
}

tr:hover {
    background:#fafafa;
}

.product-img {
    width:60px;
    height:60px;
    object-fit:cover;
    border-radius:8px;
}

/* BUTTON */
.action-btn {
    padding:6px 12px;
    border:none;
    border-radius:6px;
    font-size:14px;
    cursor:pointer;
}

.edit-btn {
    background:#0d6efd;
    color:white;
}

.delete-btn {
    background:#dc3545;
    color:white;
}

.edit-btn:hover {
    background:#0b5ed7;
}

.delete-btn:hover {
    background:#bb2d3b;
}

.price {
    font-weight:bold;
    color:#bf9455;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <h3>Admin Panel</h3>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="produk.php">Produk</a>
        <a href="pesanan.php">Pesanan</a>
         <?php if (!isset($_SESSION['id_user'])): ?>
        <!-- JIKA BELUM LOGIN -->
        <a href="login.php" class="btn-login">Login</a>
    <?php else: ?>
        <!-- JIKA SUDAH LOGIN -->
        <a href="../logout.php" class="btn-logout">Logout</a>
    <?php endif; ?>
    </div>
</div>

<div class="container">

    <div class="header">
        <h2>Manajemen Produk</h2>
        <a href="tambah_produk.php">
            <button class="add-btn">+ Tambah Produk</button>
        </a>
    </div>

    <div class="table-box">
        <table>
            <tr>
                <th>#</th>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>

            <?php if (mysqli_num_rows($qProduk) == 0): ?>
                <tr>
                    <td colspan="6" style="text-align:center;">Belum ada produk</td>
                </tr>
            <?php endif; ?>

            <?php $no=1; while ($p = mysqli_fetch_assoc($qProduk)): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>
                    <img src="../img/<?= $p['gambar']; ?>" class="product-img">
                </td>
                <td><?= $p['nama_produk']; ?></td>
                <td class="price">Rp <?= number_format($p['harga_produk'],0,',','.'); ?></td>
                <td>
                    <a href="edit_produk.php?id=<?= $p['id']; ?>">
                        <button class="action-btn edit-btn">Edit</button>
                    </a>
                    <a href="hapus_produk.php?id=<?= $p['id']; ?>"
                       onclick="return confirm('Yakin hapus produk ini?')">
                        <button class="action-btn delete-btn">Hapus</button>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>

        </table>
    </div>

</div>

</body>
</html>
