<?php
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Cari produk di keranjang dan hapus
    foreach ($_SESSION['keranjang'] as $key => $item) {
        if ($item['id'] == $id) {
            unset($_SESSION['keranjang'][$key]);
            break;
        }
    }

    // Redirect kembali ke halaman keranjang
    header("Location: keranjang.php");
    exit();
}
?>
