<?php
    session_start();
    include '../Model/crudEvent.php';

    if (!isset($_SESSION['username'])) {
        header("Location: ../Login/FormLogin.php"); // Redirect kalau belum login
        exit();
      }

    if (isset($_GET['id_event'])) {
        $idEvent = $_GET['id_event'];
        deleteEvent($idEvent);
        header('location:event.php');
    }
?> 