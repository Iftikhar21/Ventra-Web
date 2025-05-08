<?php
    require_once('../Model/crudTransaksi.php');

    $data = getTransaksiPerBulanTahunIni();

    header('Content-Type: application/json');
    echo json_encode($data);
?>