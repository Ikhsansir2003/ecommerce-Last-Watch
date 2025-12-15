<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Cek keranjang user
$qKeranjang = mysqli_query($conn, "SELECT id FROM keranjang WHERE id_user = $id_user LIMIT 1");

if (mysqli_num_rows($qKeranjang) > 0) {
    $id_keranjang = mysqli_fetch_assoc($qKeranjang)['id'];

    // Hapus isi keranjang
    mysqli_query($conn, "DELETE FROM item_keranjang WHERE id_keranjang = $id_keranjang");
}

// Redirect ke produk
echo "<script>
alert('Checkout dibatalkan. Keranjang dikosongkan.');
window.location='../index.php';
</script>";
?>
