<?php
session_start();
include '../php/koneksi.php';

// if ($_SESSION['role'] != 'admin') exit();

$id_order = intval($_POST['id_order']);
$status   = $_POST['status'];

$allowed = ['pending','paid','success','cancel'];

if (!in_array($status, $allowed)) {
    die("Status tidak valid");
}

mysqli_query($conn, "
    UPDATE `order`
    SET status = '$status'
    WHERE id = $id_order
");

header("Location: pesanan.php?success=updated");
exit();
