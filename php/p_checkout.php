<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$pembayaran = $_POST['pembayaran'];

// Cek input pembayaran
if (empty($pembayaran)) {
    echo "<script>alert('Pilih metode pembayaran!'); window.location='../beli.php';</script>";
    exit();
}

/* ===========================================================
   MODE 1 — PEMBELIAN LANGSUNG (BUY NOW)
   ===========================================================*/
if (isset($_SESSION['checkout'])) {

    $c = $_SESSION['checkout'];

       // Masukkan item
    $subtotal = $c['harga'] * $c['jumlah'];

    // Buat pesanan baru
    mysqli_query($conn, "
        INSERT INTO `order` (id_user, status, pembayaran, total)
        VALUES ($id_user, 'Pending', '$pembayaran', '$subtotal')
    ");
    $id_order = mysqli_insert_id($conn);



    mysqli_query($conn, "
        INSERT INTO item_order (id_order, id_produk, jlh_harga, jlh_pesan)
        VALUES ($id_order, {$c['produk_id']}, $subtotal, {$c['jumlah']})
    ");

    // Hapus session checkout
    unset($_SESSION['checkout']);

    echo "<script>alert('Pesanan berhasil dibuat!'); window.location='../pesanan.php';</script>";
    exit();
}


/* ===========================================================
   MODE 2 — CHECKOUT DARI KERANJANG
   ===========================================================*/

// Ambil id keranjang user
$qKeranjang = mysqli_query($conn, "SELECT id FROM keranjang WHERE id_user = $id_user LIMIT 1");

if (mysqli_num_rows($qKeranjang) == 0) {
    echo "<script>alert('Keranjang kosong!'); window.location='../produk.php';</script>";
    exit();
}

$id_keranjang = mysqli_fetch_assoc($qKeranjang)['id'];

// Ambil item keranjang
$qItem = mysqli_query($conn, "
    SELECT item_keranjang.*, produk.harga_produk
    FROM item_keranjang
    JOIN produk ON item_keranjang.id_produk = produk.id
    WHERE id_keranjang = $id_keranjang
");

if (mysqli_num_rows($qItem) == 0) {
    echo "<script>alert('Tidak ada item di keranjang!'); window.location='../produk.php';</script>";
    exit();
}

// Masukkan ke tabel order
mysqli_query($conn, "
    INSERT INTO `order` (id_user, status, pembayaran, total)
    VALUES ($id_user, 'pending', '$pembayaran', 0)
");

$id_order = mysqli_insert_id($conn);

$qItem = mysqli_query($conn, "
    SELECT item_keranjang.*, produk.harga_produk
    FROM item_keranjang
    JOIN produk ON item_keranjang.id_produk = produk.id
    WHERE id_keranjang = $id_keranjang
");

$total = 0;


// Masukkan tiap item ke item_order
while ($item = mysqli_fetch_assoc($qItem)) {
    $subtotal = $item['harga_produk'] * $item['jlh_pesan'];
    $total += $subtotal;

    mysqli_query($conn, "
        INSERT INTO item_order (id_order, id_produk, jlh_harga, jlh_pesan)
        VALUES ($id_order, {$item['id_produk']}, $subtotal, {$item['jlh_pesan']})
    ");
}

mysqli_query($conn, "
    UPDATE `order`
    SET total = $total
    WHERE id = $id_order
");

// Kosongkan isi keranjang
mysqli_query($conn, "DELETE FROM item_keranjang WHERE id_keranjang = $id_keranjang");

echo "<script>alert('Pesanan berhasil dibuat!'); window.location='../pesanan.php';</script>";
exit();
?>
