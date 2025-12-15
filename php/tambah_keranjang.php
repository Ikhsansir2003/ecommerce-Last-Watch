<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$id_produk = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_user   = $_SESSION['id_user'];

// Validasi produk
$cek_produk = mysqli_query($conn, "SELECT id FROM produk WHERE id = $id_produk");
if (mysqli_num_rows($cek_produk) == 0) {
    header("Location: ../index.php?error=produk_tidak_ada");
    exit();
}

/* ==========================================================
   1. CEK APAKAH USER SUDAH PUNYA KERANJANG
   ========================================================== */
$qKeranjang = mysqli_query($conn, "SELECT id FROM keranjang WHERE id_user = $id_user LIMIT 1");

if (mysqli_num_rows($qKeranjang) > 0) {
    $dataKeranjang = mysqli_fetch_assoc($qKeranjang);
    $id_keranjang = $dataKeranjang['id'];
} else {
    // Jika belum ada keranjang → buat keranjang baru
    mysqli_query($conn, "INSERT INTO keranjang (id_user) VALUES ($id_user)");
    $id_keranjang = mysqli_insert_id($conn);
}

/* ==========================================================
   2. CEK APAKAH PRODUK SUDAH ADA DI item_keranjang
   ========================================================== */
$qItem = mysqli_query($conn, 
    "SELECT * FROM item_keranjang 
     WHERE id_keranjang = $id_keranjang AND id_produk = $id_produk"
);

if (mysqli_num_rows($qItem) > 0) {
    // Jika sudah ada → tambah quantity
    mysqli_query($conn, 
        "UPDATE item_keranjang 
         SET jlh_pesan = jlh_pesan + 1 
         WHERE id_keranjang = $id_keranjang AND id_produk = $id_produk"
    );
} else {
    // Jika belum ada → insert sebagai item baru
    mysqli_query($conn, 
        "INSERT INTO item_keranjang (id_keranjang, id_produk, jlh_pesan)
         VALUES ($id_keranjang, $id_produk, 1)"
    );
}

/* Redirect ke halaman keranjang */
header("Location: ../keranjang.php?success=added");
exit();
?>
