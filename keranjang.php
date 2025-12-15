<?php
session_start();
include 'php/koneksi.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Ambil keranjang user
$qKeranjang = mysqli_query($conn, "SELECT id FROM keranjang WHERE id_user = $id_user LIMIT 1");

if (mysqli_num_rows($qKeranjang) > 0) {
    $keranjang = mysqli_fetch_assoc($qKeranjang);
    $id_keranjang = $keranjang['id'];

    // Ambil item keranjang
    $query = "
    SELECT 
        item_keranjang.id AS cart_id,
        produk.nama_produk AS name,
        produk.harga_produk AS price,
        produk.gambar AS image,
        item_keranjang.jlh_pesan AS quantity
    FROM item_keranjang
    JOIN produk ON item_keranjang.id_produk = produk.id
    WHERE item_keranjang.id_keranjang = $id_keranjang
    ";

    $result = mysqli_query($conn, $query);
} else {
    $id_keranjang = 0;
    $result = false;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f6f6;
            margin: 0;
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
    height: 32px;
    border-radius: 20px;
    border: none;
    padding-left: 10px;
    font-size: 14px;
}

/* ==================================== */
/* 1. LAYOUT HALAMAN KERANJANG */
/* ==================================== */

.cart-page-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
    font-family: 'Arial', sans-serif;
}

.cart-page-container h2 {
    font-size: 32px;
    font-weight: bold;
    color: #333;
    margin-bottom: 30px;
    border-bottom: 3px solid #bf9455; /* Garis emas di bawah judul */
    display: inline-block;
    padding-bottom: 5px;
}

/* Struktur 2 Kolom */
.cart-layout {
    display: flex;
    gap: 30px;
    align-items: flex-start; /* Items start from the top */
}

/* Kolom Kiri: Daftar Item */
.cart-items-list {
    flex: 3; /* Mengambil 60-70% lebar */
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Kolom Kanan: Ringkasan */
.cart-summary {
    flex: 1; /* Mengambil 30-40% lebar */
    background: #fcfcfc;
    border: 1px solid #eee;
    padding: 25px;
    border-radius: 10px;
    position: sticky; /* Agar tetap terlihat saat scroll */
    top: 20px;
}

/* ==================================== */
/* 2. ITEM CARD & TATA LETAK */
/* ==================================== */

.cart-item-card {
    display: flex;
    align-items: center;
    border: 1px solid #e0e0e0;
    padding: 15px;
    border-radius: 10px;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
}

.item-image-wrapper {
    width: 100px;
    height: 100px;
    margin-right: 20px;
    border-radius: 8px;
    overflow: hidden;
    background: #f9f9f9;
}

.item-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* Agar jam tidak terpotong */
}

.item-details-main {
    flex-grow: 1; /* Mengambil ruang tengah */
}

.item-name {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.item-price {
    font-size: 14px;
    color: #555;
    margin-bottom: 10px;
}

/* Kuantitas dan Subtotal (Paling Kanan) */
.item-actions-summary {
    display: flex;
    flex-direction: column;
    align-items: flex-end; /* Semua elemen ke kanan */
    gap: 10px;
    min-width: 150px; /* Lebar minimum untuk subtotal/qty */
    text-align: right;
}

/* Kuantitas Selector */
.quantity-selector {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
}

.qty-btn {
    background: #f8f8f8;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    font-size: 16px;
    transition: 0.2s;
    color: #333;
}

.qty-btn:hover {
    background: #bf9455;
    color: white;
}

.qtyValue {
    padding: 0 10px;
    font-weight: 600;
    color: #1a1a1a;
}

.sub-total-price {
    font-size: 16px;
    color: #1a1a1a;
    font-weight: 700;
}

/* Tombol Hapus */
.remove-btn {
    background: none;
    border: none;
    color: #d9534f; /* Warna merah untuk hapus */
    font-size: 13px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.2s;
}

.remove-btn:hover {
    color: #a94442;
    text-decoration: underline;
}

.remove-btn i {
    margin-right: 5px;
}

/* ==================================== */
/* 3. RINGKASAN PEMBAYARAN */
/* ==================================== */

.cart-summary h3 {
    font-size: 20px;
    margin-bottom: 20px;
    font-weight: 700;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 10px;
}

.summary-line {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 16px;
    color: #555;
}

.total-amount {
    font-size: 20px;
    font-weight: bold;
    color: #1a1a1a !important;
    padding: 15px 0;
    border-top: 2px solid #bf9455; /* Garis emas tebal sebelum total */
    margin-top: 15px;
}

.total-amount span {
    font-weight: bold;
    color: #1a1a1a;
}

.checkout-btn {
    width: 100%;
    padding: 15px;
    margin-top: 20px;
    background: #bf9455;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.3s;
}

.checkout-btn:hover {
    background: #a38148;
}

.checkout-btn i {
    margin-left: 10px;
}

/* ==================================== */
/* 4. KERANJANG KOSONG */
/* ==================================== */

.cart-empty {
    text-align: center;
    padding: 60px;
    background: #fff;
    border: 1px dashed #ccc;
    border-radius: 10px;
    margin-top: 30px;
}

.empty-icon {
    font-size: 48px;
    color: #bf9455;
    margin-bottom: 15px;
}

.cart-empty h3 {
    color: #333;
    font-size: 24px;
    margin-bottom: 10px;
}

.back-to-shop-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 25px;
    background: #bf9455;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    transition: 0.3s;
}

.back-to-shop-btn:hover {
    background: #a38148;
}

/* ==================================== */
/* 5. RESPONSIVE */
/* ==================================== */

@media (max-width: 900px) {
    .cart-layout {
        flex-direction: column; /* Ubah tata letak menjadi kolom tunggal */
    }

    .cart-items-list, .cart-summary {
        flex: auto;
        width: 100%;
    }
    
    .cart-summary {
        order: -1; /* Pindahkan ringkasan ke atas di HP */
        margin-bottom: 20px;
    }
}

@media (max-width: 600px) {
    .cart-item-card {
        flex-wrap: wrap;
        gap: 15px;
    }

    .item-image-wrapper {
        width: 80px;
        height: 80px;
        margin-right: 10px;
    }

    .item-details-main {
        min-width: 50%;
    }

    .item-actions-summary {
        flex-direction: row; /* Qty & Subtotal sejajar di HP */
        justify-content: space-between;
        width: 100%;
        border-top: 1px solid #f0f0f0;
        padding-top: 15px;
    }

    .sub-total-price {
        font-size: 14px;
    }
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="cart-page-container">
    <h2>Keranjang Belanja Anda</h2>

    <?php if($result && mysqli_num_rows($result) > 0): ?>

        <div class="cart-layout">

            <div class="cart-items-list">
                <?php 
                $total = 0;
                // Reset pointer result set jika diperlukan, atau pastikan loop utama belum dijalankan
                // mysqli_data_seek($result, 0); 
                
                while($row = mysqli_fetch_assoc($result)): 
                    $sub_total = $row['price'] * $row['quantity'];
                    $total += $sub_total;
                ?>

                <div class="cart-item-card">
                    
                    <div class="item-image-wrapper">
                        <img src="img/<?= $row['image']; ?>" alt="<?= $row['name']; ?>">
                    </div>

                    <div class="item-details-main">
                        <h4 class="item-name"><?= $row['name']; ?></h4>
                        <p class="item-price">Rp <?= number_format($row['price'], 0, ',', '.'); ?>/unit</p>

                        <form action="php/hapus_keranjang.php" method="POST" class="remove-form">
                            <input type="hidden" name="cart_id" value="<?= $row['cart_id']; ?>">
                            <button type="submit" class="remove-btn">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                    </div>

                    <div class="item-actions-summary">
                        
                        <div class="quantity-selector" data-id="<?= $row['cart_id']; ?>">
                            <button class="qty-btn minusBtn">-</button>
                            <span class="qtyValue"><?= $row['quantity']; ?></span>
                            <button class="qty-btn plusBtn">+</button>
                        </div>
                        
                        <p class="sub-total-price">
                            **Rp <?= number_format($sub_total, 0, ',', '.'); ?>**
                        </p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div> <div class="cart-summary">
                <h3>Ringkasan Belanja</h3>
                <div class="summary-line">
                    <span>Subtotal Barang</span>
                    <span>Rp <?= number_format($total, 0, ',', '.'); ?></span>
                </div>
                <div class="summary-line total-amount">
                    <span>Total Pembayaran</span>
                    **<span>Rp <?= number_format($total, 0, ',', '.'); ?></span>**
                </div>

                <form action="php/p_beli.php" method="POST">
                    <input type="hidden" name="total_amount" value="<?= $total; ?>"> 
                    <button type="submit" class="checkout-btn">
                        Checkout <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div> </div> <?php else: ?>
        <div class="cart-empty">
            <i class="fas fa-shopping-cart empty-icon"></i>
            <h3>Keranjang belanja Anda kosong.</h3>
            <p>Yuk, temukan jam tangan favorit Anda!</p>
            <a href="index.php" class="back-to-shop-btn">Mulai Belanja</a>
        </div>
    <?php endif; ?>

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

<!-- AJAX UPDATE QUANTITY -->
<script>
// Fungsi pembantu untuk format angka (Rp 1.000.000)
const formatRupiah = (angka) => {
    return angka.toLocaleString('id-ID');
};

// Fungsi untuk menghitung ulang Total Pembayaran di Ringkasan Belanja
const updateCartTotal = () => {
    let grandTotal = 0;
    
    // 1. Ambil semua Subtotal Item yang ada di halaman
    document.querySelectorAll('.sub-total-price').forEach(subTotalElement => {
        const text = subTotalElement.textContent; // Ambil teks (e.g., "**Rp 1.000.000**")
        // Hapus karakter non-digit ('Rp', spasi, titik, koma) dan konversi ke angka
        const value = parseInt(text.replace(/[^0-9]/g, ''));
        if (!isNaN(value)) {
            grandTotal += value;
        }
    });

    // 2. Perbarui tampilan di Ringkasan Belanja (.cart-summary)
    const summarySubtotal = document.querySelector('.cart-summary .summary-line:first-child span:last-child');
    const totalAmountSpan = document.querySelector('.total-amount span:last-child');
    const totalInput = document.querySelector('input[name="total_amount"]'); // Hidden input untuk Checkout

    if (summarySubtotal) {
        summarySubtotal.textContent = `Rp ${formatRupiah(grandTotal)}`;
    }
    
    if (totalAmountSpan) {
        totalAmountSpan.innerHTML = `**Rp ${formatRupiah(grandTotal)}**`;
    }

    // 3. Perbarui input tersembunyi untuk proses checkout
    if (totalInput) {
        totalInput.value = grandTotal;
    }
};

// ===================================
// Logika Per Item
// ===================================
document.querySelectorAll('.quantity-selector').forEach(qtyBox => {
    const minusBtn = qtyBox.querySelector('.minusBtn');
    const plusBtn = qtyBox.querySelector('.plusBtn');
    const qtyValue = qtyBox.querySelector('.qtyValue');
    const cartId = qtyBox.dataset.id;

    // Selector Subtotal Item (Di dalam .item-actions-summary)
    const subTotalElement = qtyBox.closest('.item-actions-summary').querySelector('.sub-total-price');

    // Selector Harga Unit (Diambil dari .item-details-main, sibling dari item-actions-summary)
    const itemDetailsMain = qtyBox.closest('.cart-item-card').querySelector('.item-details-main');
    const priceText = itemDetailsMain.querySelector('.item-price').innerText;
    
    // Parsing harga unit ke integer
    const price = parseInt(priceText.replace(/[^0-9]/g, '')); 

    // Fungsi untuk mengirim update kuantitas ke server
    const updateServer = (quantity) => {
        fetch("php/update_cart.php", {
            method: "POST",
            headers: { 
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `cart_id=${cartId}&quantity=${quantity}`
        })
        .then(response => response.text())
        .then(data => {
            // Optional: Tambahkan notifikasi
        })
        .catch(error => console.error('Error updating cart:', error));
    };

    // Fungsi untuk memperbarui tampilan Subtotal Item
    const updateDisplay = (quantity) => {
        qtyValue.textContent = quantity;
        const newSubTotal = price * quantity;
        subTotalElement.innerHTML = `**Rp ${formatRupiah(newSubTotal)}**`;
        
        // Panggil update total global setiap kali subtotal item berubah
        updateCartTotal();
    };

    // ===================================
    // EVENT LISTENERS
    // ===================================
    
    if (minusBtn) {
        minusBtn.addEventListener('click', () => {
            let current = parseInt(qtyValue.textContent);
            if (current > 1) {
                current--;
                updateDisplay(current);
                updateServer(current);
            }
        });
    }

    if (plusBtn) {
        plusBtn.addEventListener('click', () => {
            let current = parseInt(qtyValue.textContent);
            current++;
            updateDisplay(current);
            updateServer(current);
        });
    }
    
});

// Pastikan total global dihitung saat halaman dimuat
document.addEventListener('DOMContentLoaded', updateCartTotal); 
</script>

</body>
</html>
