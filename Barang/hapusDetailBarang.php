<?php
session_start();
include '../Model/crudBarang.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
    exit();
}

if (isset($_GET['Kode_Brg'])) {
    $kodeBarang = $_GET['Kode_Brg'];
    $produk_id = isset($_GET['produk_id']) ? $_GET['produk_id'] : ''; // Ambil produk_id dari URL
    
    deleteDetailBarangByProduk($kodeBarang);
    
    // Redirect kembali ke detailBarang.php dengan menyertakan id produk
    header("Location: detailBarang.php?id=" . $produk_id);
    exit();
}
?>