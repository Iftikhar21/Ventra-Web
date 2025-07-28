<?php
session_start();
include '../Model/crudLaporan.php';

$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$dataPerTanggal = getLaporanByTanggal($tanggal);
$dataMetodePembayaranPerTanggal = getRekapPembayaranByTanggal($tanggal);

// Statistik
$totalTransaksi = count(array_unique(array_column($dataPerTanggal, 'ID_Transaksi')));
$totalItemTerjual = array_sum(array_column($dataPerTanggal, 'JMLH'));
$totalPendapatan = array_sum(array_column($dataPerTanggal, 'total_harga'));

// Rekap metode pembayaran
$rekap = [];
$grandTotal = 0;
foreach ($dataMetodePembayaranPerTanggal as $metode) {
    $rekap[] = [
        'metode' => $metode['metode'],
        'jumlah_transaksi' => $metode['jumlah_transaksi'],
        'total' => $metode['total']
    ];
    $grandTotal += $metode['total'];
}

echo json_encode([
    'totalTransaksi' => $totalTransaksi,
    'totalItemTerjual' => $totalItemTerjual,
    'totalPendapatan' => $totalPendapatan,
    'rekap' => $rekap,
    'grandTotal' => $grandTotal
]);