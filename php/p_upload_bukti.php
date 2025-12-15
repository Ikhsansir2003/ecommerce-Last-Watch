<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

/* =========================
   VALIDASI ID ORDER
========================= */
if (!isset($_POST['id_order']) || empty($_POST['id_order'])) {
    die("ID order tidak ditemukan");
}

$id_order = intval($_POST['id_order']);
$id_user  = $_SESSION['id_user'];

/* =========================
   VALIDASI FILE
========================= */
if (!isset($_FILES['bukti_bayar']) || $_FILES['bukti_bayar']['error'] !== 0) {
    die("File tidak ditemukan atau gagal diupload");
}

$file = $_FILES['bukti_bayar'];

/* =========================
   VALIDASI ORDER
========================= */
$q = mysqli_query($conn, "
    SELECT * FROM `order`
    WHERE id = $id_order
      AND id_user = $id_user
      AND status = 'pending'
");

if (mysqli_num_rows($q) == 0) {
    die("Pesanan tidak valid atau sudah dibayar");
}

/* =========================
   VALIDASI FILE TYPE & SIZE
========================= */
$allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed_ext)) {
    die("Format file tidak didukung (jpg, png, pdf)");
}

// maksimal 2MB
if ($file['size'] > 2 * 1024 * 1024) {
    die("Ukuran file maksimal 2MB");
}

/* =========================
   FOLDER UPLOAD
========================= */
$folder = "../uploads/bukti_bayar/";
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

/* =========================
   UPLOAD FILE
========================= */
$filename = "bukti_" . $id_order . "_" . time() . "." . $ext;
$path = $folder . $filename;

if (!move_uploaded_file($file['tmp_name'], $path)) {
    die("Gagal menyimpan file");
}

/* =========================
   UPDATE DATABASE
========================= */
mysqli_query($conn, "
    UPDATE `order`
    SET bukti_bayar = '$filename',
        status = 'paid'
    WHERE id = $id_order
");

/* =========================
   REDIRECT
========================= */
header("Location: ../pesanan.php?success=uploaded");
exit();
?>
