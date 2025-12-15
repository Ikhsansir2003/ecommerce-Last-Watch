<?php
session_start();
include 'php/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Ambil data pesanan
$qOrder = mysqli_query($conn, "
    SELECT * FROM `order`
    WHERE id_user = $id_user
    ORDER BY id DESC
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesanan Saya</title>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f6f6f6;
    }

    /* NAVBAR */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 25px;
    background: #bf9455;
    height: 40px;
    color: white;
}

.navbar a {
    color: rgb(255, 255, 255);
    text-decoration: none;
    margin-left: 20px;
    
}

.logo {
    width: 50px;
    padding-top: 10px;
}

.search {
    width: 40%;
    height: 35px;
    border-radius: 20px;
    border: none;
    padding-left: 10px;
    font-size: 14px;
}

    /* CONTAINER */
    .container {
        max-width: 900px;
        margin: 120px auto 40px auto;
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.1);
    }

    .order-box {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        background: #fafafa;
    }

    .product-item {
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
    }

    .cancel-btn {
        padding: 10px 15px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 10px;
    }

    .cancel-btn:hover {
        background: #b02a37;
    }

    .upload-btn {
    padding: 10px 15px;
    background: #198754;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
    margin-right: 10px;
}

.upload-btn:hover {
    background: #157347;
}

.action-group {
    margin-top: 15px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}


    /* FOOTER */
footer {
    width: 100%;
    /* Jangan gunakan fixed height (50px), gunakan padding agar konten tidak terpotong */
    padding: 20px 0; 
    background: #bf9455;
    color: #fff; /* Teks putih agar kontras dengan emas */
    font-family: 'Arial', sans-serif;
}

.footer-container {
    display: flex;
    flex-direction: column;
    align-items: center; /* Posisi tengah horizontal */
    justify-content: center;
    gap: 10px; /* Jarak antar elemen */
}

.footer-links a {
    text-decoration: none;
    color: #fff;
    margin: 0 15px;
    font-weight: bold;
    font-size: 14px;
    transition: color 0.3s;
}

.footer-links a:hover {
    color: #333; /* Efek hover menjadi gelap */
}

.copyright {
    font-size: 12px;
    margin-top: 5px;
    opacity: 0.8;
}
</style>
</head>
<body>

<!-- NAVBAR -->
 <header class="navbar">
        <img src="img/logo_toko.png" class="logo" />
        <input type="text" class="search" placeholder="Cari jam...">
        <div>
        <a href="index.php">Beranda</a>
        <a href="keranjang.php">Keranjang</a>
        <a href="pesanan.php">Pesanan</a>
        <a href="logout.php">Logout</a>
    </div>
    </header>

<div class="container">
    <h2>Pesanan Saya</h2>

    <?php
    if (mysqli_num_rows($qOrder) == 0) {
        echo "<p>Belum ada pesanan.</p>";
    }

    while ($order = mysqli_fetch_assoc($qOrder)):
        $id_order = $order['id'];

        // Ambil item pesanan
        $qItem = mysqli_query($conn, "
            SELECT item_order.*, produk.nama_produk
            FROM item_order
            JOIN produk ON item_order.id_produk = produk.id
            WHERE id_order = $id_order
        ");
    ?>

    <div class="order-box">
        <h3>Order #<?= $order['id']; ?></h3>
        <p>Status: <b><?= $order['status']; ?></b></p>
        <p>Metode Pembayaran: <b><?= $order['pembayaran']; ?></b></p>

        <!-- Jika Bank -->
        <?php if ($order['pembayaran'] == "Transfer Bank"): ?>
            <p><b>Nomor Rekening:</b> 1234567890 (Bank BRI)</p>

        <!-- Jika E-Wallet -->
        <?php elseif ($order['pembayaran'] == "E-Wallet"): ?>
            <p><b>Nomor E-Wallet:</b> 089876543210 (Dana)</p>
        <?php endif; ?>

        <hr>

        <h4>Produk yang dipesan:</h4>

        <?php 
        $totalHarga = 0;
        while ($item = mysqli_fetch_assoc($qItem)):
            $totalHarga += $item['jlh_harga'];
        ?>

            <div class="product-item">
                <b><?= $item['nama_produk']; ?></b><br>
                Jumlah: <?= $item['jlh_pesan']; ?><br>
                Total: Rp <?= number_format($item['jlh_harga'], 0, ',', '.'); ?>
            </div>

        <?php endwhile; ?>

        <h3>Total Pembayaran: Rp <?= number_format($totalHarga, 0, ',', '.'); ?></h3>

        <?php if ($order['status'] == "Pending"): ?>
    <div class="action-group">

        <!-- Upload Bukti Bayar -->
        <form action="upload_bukti.php" method="GET">
            <input type="hidden" name="id_order" value="<?= $order['id']; ?>">
            <button class="upload-btn">Upload Bukti Bayar</button>
        </form>

        <!-- Batalkan Pesanan -->
        <form action="php/batal_pesan.php" method="POST"
              onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
            <input type="hidden" name="id_order" value="<?= $order['id']; ?>">
            <button class="cancel-btn">Batalkan Pesanan</button>
        </form>

    </div>
        <?php elseif ($order['status'] == "Paid"): ?>
            <p style="color: #ecd903ff; font-weight: bold;">
                Dalam Proses Validasi Pemabayaran
            </p>

        <?php elseif ($order['status'] == "Cancel"): ?>
            <p style="color: red; font-weight: bold;">
                Pesanan Dibatalkan
            </p>
        
        <?php elseif ($order['status'] == "Success"): ?>
            <p style="color: green; font-weight: bold;">
                ✔ Pembayaran telah dikonfirmasi
            </p>
        <?php endif; ?>


    </div>

    <?php endwhile; ?>
</div>

<!-- FOOTER -->
<footer>
    <div class="footer-container">
        <div class="footer-links">
            <a href="#">Home</a>
            <a href="#">Collection</a>
            <a href="#">About</a>
            <a href="#">Contact</a>
        </div>
        <p class="copyright">&copy; 2025 Last Watch. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
