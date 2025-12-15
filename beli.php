<?php
session_start();
include 'php/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

/* ===========================================================
   CEK JIKA PEMBELIAN LANGSUNG (BUY NOW)
   ===========================================================*/
if (isset($_SESSION['checkout'])) {

    $c = $_SESSION['checkout'];

    // buat data mirip seperti hasil query keranjang
    $items = [
        [
            "id_produk"   => $c['produk_id'],
            "nama_produk" => $c['nama'],
            "harga_produk"=> $c['harga'],
            "jlh_pesan"   => $c['jumlah']
        ]
    ];

    $total = $c['total'];

    // Lewati pengecekan keranjang
    goto tampil_checkout;
}



/* ===========================================================
   JIKA TIDAK ADA SESSION checkout → berarti checkout dari KERANJANG
   ===========================================================*/

// Ambil keranjang user
$qKeranjang = mysqli_query($conn, "SELECT id FROM keranjang WHERE id_user = $id_user LIMIT 1");
if (mysqli_num_rows($qKeranjang) == 0) {
    echo "<script>alert('Keranjang kosong'); window.location='produk.php';</script>";
    exit();
}

$id_keranjang = mysqli_fetch_assoc($qKeranjang)['id'];

// Ambil item dari keranjang
$query = "
SELECT 
    item_keranjang.id_produk,
    produk.nama_produk,
    produk.harga_produk,
    item_keranjang.jlh_pesan
FROM item_keranjang
JOIN produk ON item_keranjang.id_produk = produk.id
WHERE item_keranjang.id_keranjang = $id_keranjang
";

$result = mysqli_query($conn, $query);

// Hitung total
$total = 0;
$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $sub = $row['harga_produk'] * $row['jlh_pesan'];
    $total += $sub;
    $items[] = $row;
}

/* Label lompat */
tampil_checkout:
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f6f6f6;
        }

        /* NAVBAR */
        .navbar {
            background: #bf9455;
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 999;
        }
        .navbar h3 { margin: 0; }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }

        /* CONTAINER */
        .container {
            max-width: 900px;
            margin: 120px auto 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        }

        .checkout-box h3 { margin-bottom: 10px; }

        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #ddd;
        }

        .input-box {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #aaa;
            border-radius: 8px;
        }

        .checkout-btn {
            width: 100%;
            margin-top: 15px;
            padding: 12px;
            font-size: 18px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .checkout-btn:hover {
            background: #218838;
        }

        /* BUTTON BATAL */
        .cancel-btn {
            width: 100%;
            margin-top: 10px;
            padding: 12px;
            font-size: 18px;
            background: #d9534f;
            color: white;
            border-radius: 8px;
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }
        .cancel-btn:hover {
            background: #c9302c;
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
<div class="navbar">
    <h3>Last Watch</h3>
    <div>
        <a href="index.php">Beranda</a>
        <a href="keranjang.php">Keranjang</a>
        <a href="pesanan.php">Pesanan</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Checkout</h2>

    <div class="checkout-box">

        <h3>Ringkasan Pesanan</h3>

        <?php foreach($items as $item): ?>
            <div class="cart-item">
                <div>
                    <b><?= $item['nama_produk']; ?></b><br>
                    Harga: Rp <?= number_format($item['harga_produk'], 0, ',', '.'); ?><br>
                    Jumlah: <?= $item['jlh_pesan']; ?>
                </div>

                <p><b>Rp <?= number_format($item['harga_produk'] * $item['jlh_pesan'], 0, ',', '.'); ?></b></p>
            </div>
        <?php endforeach; ?>

        <hr>
        <h3>Total Pembayaran: <b>Rp <?= number_format($total, 0, ',', '.'); ?></b></h3>

        <form action="php/p_checkout.php" method="POST">
            <h3>Metode Pembayaran</h3>

            <select name="pembayaran" required class="input-box">
                <option value="">-- Pilih Pembayaran --</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="COD">COD</option>
                <option value="E-Wallet">E-Wallet</option>
            </select>

            <button type="submit" class="checkout-btn">Buat Pesanan</button>
        </form>

        <!-- BUTTON BATAL -->
        <a href="php/batal_beli.php" class="cancel-btn">Batalkan</a>

    </div>
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
