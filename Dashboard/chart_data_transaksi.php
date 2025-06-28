<?php
    require_once('../Model/crudTransaksi.php');

    $dataTransaksi = getTransaksiPerBulanTahunIni();

    header('Content-Type: application/json');
    echo json_encode($dataTransaksi);
?>