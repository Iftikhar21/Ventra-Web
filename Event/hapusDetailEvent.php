<?php
    session_start();
    require_once '../Model/crudEvent.php';

    if (!isset($_SESSION['username'])) {
        header("Location: ../Login/formLogin.php");
        exit();
    }

    if (isset($_GET['id_event']) && isset($_GET['id_produk'])) {
        $idEvent = $_GET['id_event'];
        $idBarang = $_GET['id_produk'];

        if (removeProductFromEvent($idEvent, $idBarang)) {
            $_SESSION['success_message'] = 'Produk berhasil dihapus dari event';
        } else {
            $_SESSION['error_message'] = 'Gagal menghapus produk dari event';
        }

        header("Location: detailEvent.php?id_event=$idEvent");
        exit();
    } else {
        header("Location: event.php");
        exit();
    }
?>