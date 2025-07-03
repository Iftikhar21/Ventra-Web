<?php
    session_start();
    include '../Model/crudBarang.php';

    if (!isset($_SESSION['username'])) {
        header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
        exit();
      }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        deleteBarang($id);
        header('location:barang.php');
    }
?> 