<?php
    session_start();
    include '../Model/crudKaryawan.php';

    if (!isset($_SESSION['username'])) {
        header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
        exit();
      }

    if (isset($_GET['NISN'])) {
        $nisn = $_GET['NISN'];
        deleteKasir($nisn);
        header('location:karyawan.php');
    }
?> 