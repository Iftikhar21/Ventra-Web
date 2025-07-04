<?php
    session_start();
    include '../Model/crudKaryawan.php';

    if (!isset($_SESSION['username'])) {
        header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
        exit();
      }

    if (isset($_GET['kode_kasir'])) {
        $kodeKasir = $_GET['kode_kasir'];
        deleteKasir($kodeKasir);
        header('location:karyawan.php');
    }
?> 