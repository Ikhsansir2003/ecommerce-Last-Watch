<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

// Pastikan ada cart_id yang dikirim
if (!isset($_POST['cart_id'])) {
    header("Location: ../keranjang.php?error=no_id");
    exit();
}

$cart_id = intval($_POST['cart_id']);

// Cek apakah item ada
$cek = mysqli_query($conn, "SELECT * FROM item_keranjang WHERE id = $cart_id");

if (mysqli_num_rows($cek) == 0) {
    header("Location: ../keranjang.php?error=item_not_found");
    exit();
}

// Hapus item dari item_keranjang
mysqli_query($conn, "DELETE FROM item_keranjang WHERE id = $cart_id");

// Setelah hapus, cek apakah keranjang masih memiliki item
$data = mysqli_fetch_assoc($cek);
$id_keranjang = $data['id_keranjang'];

$cek_sisa = mysqli_query($conn, "SELECT * FROM item_keranjang WHERE id_keranjang = $id_keranjang");

if (mysqli_num_rows($cek_sisa) == 0) {
    // Jika kosong → TIDAK menghapus tabel keranjang
    // agar user tetap memiliki keranjang default
}

// Redirect balik ke halaman keranjang
header("Location: ../keranjang.php?removed=1");
exit();
?>
