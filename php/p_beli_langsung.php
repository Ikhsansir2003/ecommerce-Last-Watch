<?php
session_start();
include 'koneksi.php';

// Validasi request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit();
}

// Validasi data session dari beli.php
if (!isset($_SESSION['direct_buy'])) {
    echo "<script>alert('Data pembelian tidak valid'); window.location='../index.php';</script>";
    exit();
}

// Ambil data dari session
$buy_data = $_SESSION['direct_buy'];
$id_produk = $buy_data['id_produk'];
$nama_produk = $buy_data['nama_produk'];
$harga_produk = $buy_data['harga_produk'];
$jumlah = $buy_data['jumlah'];
$subtotal = $buy_data['subtotal'];

// Ambil metode pembayaran dari form
$pembayaran = isset($_POST['pembayaran']) ? mysqli_real_escape_string($conn, $_POST['pembayaran']) : '';

if (empty($pembayaran)) {
    echo "<script>alert('Pilih metode pembayaran terlebih dahulu'); window.history.back();</script>";
    exit();
}

// Ambil id_user jika ada (untuk sistem yang memerlukan login)
$id_user = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : NULL;

// Generate nomor pesanan
$kode_pesanan = "ORD-" . date("Ymd") . "-" . strtoupper(substr(md5(uniqid()), 0, 6));

// Insert ke tabel pesanan
$query = "INSERT INTO pesanan (
    kode_pesanan,
    id_user,
    id_produk,
    nama_produk,
    harga_satuan,
    jumlah,
    total_harga,
    metode_pembayaran,
    status,
    tanggal_pesan
) VALUES (
    '$kode_pesanan',
    " . ($id_user ? $id_user : "NULL") . ",
    $id_produk,
    '" . mysqli_real_escape_string($conn, $nama_produk) . "',
    $harga_produk,
    $jumlah,
    $subtotal,
    '$pembayaran',
    'Pending',
    NOW()
)";

if (mysqli_query($conn, $query)) {
    $id_pesanan = mysqli_insert_id($conn);
    
    // Hapus data session
    unset($_SESSION['direct_buy']);
    
    // Set session sukses
    $_SESSION['success_order'] = [
        'kode_pesanan' => $kode_pesanan,
        'total' => $subtotal,
        'pembayaran' => $pembayaran
    ];
    
    // Redirect ke halaman sukses
    header("Location: sukses_order.php?order=" . $kode_pesanan);
    exit();
} else {
    echo "<script>
        alert('Terjadi kesalahan: " . mysqli_error($conn) . "');
        window.history.back();
    </script>";
    exit();
}

mysqli_close($conn);
?>