<?php
session_start();
include '../Model/crudBarang.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../Login/formLogin.php");
    exit();
}

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $koneksi = Connection();

    // Cek apakah ada detail produk
    $checkDetailSql = "SELECT COUNT(*) as total FROM ventra_produk_detail WHERE produk_id = '$id'";
    $checkDetailResult = mysqli_query($koneksi, $checkDetailSql);
    $detailRow = mysqli_fetch_assoc($checkDetailResult);

    if ($detailRow['total'] > 0) {
        mysqli_close($koneksi);
        echo json_encode([
            'success' => false,
            'message' => 'Barang masih memiliki detail produk'
        ]);
        exit();
    }

    // Cek apakah barang ada di event
    // Cek apakah barang ada di event dan ambil semua nama event
    $checkEventSql = "SELECT e.nama_event 
                 FROM ventra_detail_event de
                 JOIN ventra_event e ON de.id_event = e.id_event
                 WHERE de.id_produk = '$id'";
    $checkEventResult = mysqli_query($koneksi, $checkEventSql);

    if (mysqli_num_rows($checkEventResult) > 0) {
        $eventNames = [];
        $message = 'Barang masih terdaftar dalam event:<br><ul class="mb-0 list-unstyled">';
        while ($row = mysqli_fetch_assoc($checkEventResult)) {
            $message .= '<li>' . htmlspecialchars($row['nama_event']) . '</li>';
        }
        $message .= '</ul>';
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
        exit();
    }

    // Jika tidak ada kendala, hapus barang
    $sql = "DELETE FROM ventra_produk WHERE id = '$id'";
    $result = mysqli_query($koneksi, $sql);

    mysqli_close($koneksi);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Barang berhasil dihapus'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menghapus barang'
        ]);
    }
    exit();
}

echo json_encode([
    'success' => false,
    'message' => 'ID barang tidak valid'
]);
exit();
