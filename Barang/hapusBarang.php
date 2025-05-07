<?php
    include '../Model/crudBarang.php';

    if (!isset($_SESSION['username'])) {
        header("Location: ../Login/FormLogin.php"); // Redirect kalau belum login
        exit();
      }

    if (isset($_GET['Kode_Brg'])) {
        $kodeBarang = $_GET['Kode_Brg'];
        deleteBarang($kodeBarang);
        header('location:barang.php');
    }
?> 