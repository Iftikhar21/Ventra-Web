<?php
    session_start();
    include '../Model/crudKategori.php';

    if (!isset($_SESSION['username'])) {
        header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
        exit();
      }

    if (isset($_GET['id_kategori'])) {
        $idKategori = $_GET['id_kategori'];
        deleteKategori($idKategori);
        header('location:barang.php');
    }
?> 