<?php
session_start();
include '../php/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}


$q = mysqli_query($conn, "
    SELECT o.*, u.nama 
    FROM `order` o
    JOIN user u ON o.id_user = u.id
    ORDER BY o.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Pesanan</title>
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

.container {
    max-width: 1100px;
    margin: 40px auto;
    background: #fff;
    padding: 25px;
    border-radius: 10px;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}
th {
    background: #bf9455;
    color: white;
}
select, button {
    padding: 6px 10px;
    border-radius: 6px;
}
button {
    background: #198754;
    color: white;
    border: none;
    cursor: pointer;
}
button:hover {
    background: #157347;
}
.detail {
    font-size: 13px;
    color: #555;
}
.status {
    font-weight: bold;
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
    <h2>Kelola Pesanan</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Pelanggan</th>
            <th>Pembayaran</th>
            <th>Status</th>
            <th>Total</th>
            <th>Detail</th>
            <th>Aksi</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($q)): ?>
        <?php
            // hitung total
            $id_order = $row['id'];
            $qTotal = mysqli_query($conn, "
                SELECT SUM(jlh_harga) AS total 
                FROM item_order 
                WHERE id_order = $id_order
            ");
            $total = mysqli_fetch_assoc($qTotal)['total'];
        ?>

        <tr>
            <td>#<?= $row['id']; ?></td>
            <td><?= $row['nama']; ?></td>
            <td><?= $row['pembayaran']; ?></td>
            <td class="status"><?= strtoupper($row['status']); ?></td>
            <td>Rp <?= number_format($total,0,',','.'); ?></td>

            <td class="detail">
                <?php
                $qItem = mysqli_query($conn, "
                    SELECT p.nama_produk, i.jlh_pesan
                    FROM item_order i
                    JOIN produk p ON i.id_produk = p.id
                    WHERE i.id_order = $id_order
                ");
                while ($i = mysqli_fetch_assoc($qItem)) {
                    echo $i['nama_produk']." (".$i['jlh_pesan']."x)<br>";
                }
                ?>
            </td>

            <td>
                <form action="update_pesanan.php" method="POST">
                    <input type="hidden" name="id_order" value="<?= $row['id']; ?>">
                    <select name="status">
                        <option value="pending" <?= $row['status']=='Pending'?'selected':''; ?>>Pending</option>
                        <option value="paid" <?= $row['status']=='Paid'?'selected':''; ?>>Paid</option>
                        <option value="success" <?= $row['status']=='Success'?'selected':''; ?>>Selesai</option>
                        <option value="cancel" <?= $row['status']=='Cancel'?'selected':''; ?>>Dibatalkan</option>
                    </select>
                    <br><br>
                    <button>Update</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
