<?php
session_start();
include '../php/koneksi.php';

$aksi = $_POST['aksi'];

if ($aksi == "tambah") {

    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $desk = $_POST['deskripsi'];

    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    move_uploaded_file($tmp, "../img/".$gambar);

    mysqli_query($conn,"
        INSERT INTO produk (nama_produk, harga_produk, deskripsi, gambar)
        VALUES ('$nama','$harga','$desk','$gambar')
    ");

    header("Location: produk.php");

}

elseif ($aksi == "edit") {

    $id = $_POST['id'];
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $desk = $_POST['deskripsi'];
    $gambar_lama = $_POST['gambar_lama'];

    if ($_FILES['gambar']['name'] != "") {
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../img/".$gambar);
    } else {
        $gambar = $gambar_lama;
    }

    mysqli_query($conn,"
        UPDATE produk SET
        nama_produk='$nama',
        harga_produk='$harga',
        deskripsi='$desk',
        gambar='$gambar'
        WHERE id=$id
    ");

    header("Location: produk.php");
}
?>
