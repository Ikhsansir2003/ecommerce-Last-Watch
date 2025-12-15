<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$id_user = $_SESSION['id_user'];  // Diperbaiki: hapus duplikasi

// Pastikan user memilih metode pembayaran
if (!isset($_POST['pembayaran']) || empty($_POST['pembayaran'])) {
    echo "<script>
            alert('Pilih metode pembayaran terlebih dahulu!');
            window.location='../beli.php';
          </script>";
    exit();
}

$pembayaran = $_POST['pembayaran'];

// Cek jika ini pembelian langsung dari session (bukan dari keranjang)
if (isset($_SESSION['beli_produk'])) {
    $id_produk = $_SESSION['beli_produk']['id_produk'];
    $jumlah = $_SESSION['beli_produk']['jumlah'];

    // Ambil harga produk
    $qProduk = mysqli_query($conn, "SELECT harga_produk FROM produk WHERE id = $id_produk");
    if (mysqli_num_rows($qProduk) == 0) {
        echo "<script>alert('Produk tidak ditemukan!'); window.location='../produk.php';</script>";
        exit();
    }
    $produk = mysqli_fetch_assoc($qProduk);
    $harga = $produk['harga_produk'];
    $subtotal = $harga * $jumlah;

    // Buat pesanan baru di tabel order
    mysqli_query($conn, "
        INSERT INTO `order` (id_user, status, pembayaran)
        VALUES ($id_user, 'Menunggu Pembayaran', '$pembayaran')
    ");
    $id_order = mysqli_insert_id($conn);

    // Pindahkan item ke tabel item_order
    mysqli_query($conn, "
        INSERT INTO item_order (id_order, id_produk, jlh_harga, jlh_pesan)
        VALUES ($id_order, $id_produk, $subtotal, $jumlah)
    ");

    // Hapus session setelah proses
    unset($_SESSION['beli_produk']);

    // Redirect
    echo "<script>
            alert('Pesanan berhasil dibuat!');
            window.location='../pesanan.php';
          </script>";
} else {
    // Logika asli: Pembelian dari keranjang
    // 1. Ambil id keranjang user
    $qKeranjang = mysqli_query($conn, "SELECT id FROM keranjang WHERE id_user = $id_user LIMIT 1");
    if (mysqli_num_rows($qKeranjang) == 0) {
        echo "<script>
                alert('Keranjang kosong!');
                window.location='../produk.php';
              </script>";
        exit();
    }
    $id_keranjang = mysqli_fetch_assoc($qKeranjang)['id'];

    // 2. Ambil item keranjang
    $qItem = mysqli_query($conn, "
        SELECT item_keranjang.*, produk.harga_produk
        FROM item_keranjang
        JOIN produk ON item_keranjang.id_produk = produk.id
        WHERE id_keranjang = $id_keranjang
    ");
    if (mysqli_num_rows($qItem) == 0) {
        echo "<script>
                alert('Keranjang kosong!');
                window.location='../produk.php';
              </script>";
        exit();
    }

    // 3. Buat pesanan baru di tabel order
    mysqli_query($conn, "
        INSERT INTO `order` (id_user, status, pembayaran)
        VALUES ($id_user, 'Menunggu Pembayaran', '$pembayaran')
    ");
    $id_order = mysqli_insert_id($conn);

    // 4. Pindahkan item ke tabel item_order
    while ($item = mysqli_fetch_assoc($qItem)) {
        $subtotal = $item['harga_produk'] * $item['jlh_pesan'];
        mysqli_query($conn, "
            INSERT INTO item_order (id_order, id_produk, jlh_harga, jlh_pesan)
            VALUES ($id_order, {$item['id_produk']}, $subtotal, {$item['jlh_pesan']})
        ");
    }

    // 5. Hapus isi keranjang
    mysqli_query($conn, "DELETE FROM item_keranjang WHERE id_keranjang = $id_keranjang");

    // 6. Redirect
    echo "<script>
            alert('Pesanan berhasil dibuat!');
            window.location='../pesanan.php';
          </script>";
}
?>