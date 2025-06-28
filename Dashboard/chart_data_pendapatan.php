<?php
    require_once('../Model/crudTransaksi.php');

    $dataPendapatan = getPendapatanPerBulanTahunIni();

    header('Content-Type: application/json');
    echo json_encode($dataPendapatan);
?>