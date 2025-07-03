<?php
session_start();
include '../Model/crudKategori.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../Login/formLogin.php");
    exit();
}

header('Content-Type: application/json');

if (isset($_GET['id_kategori'])) {
    $idKategori = $_GET['id_kategori'];
    $koneksi = Connection();
    
    // Cek apakah kategori digunakan oleh produk
    $checkProdukSql = "SELECT COUNT(*) as total FROM ventra_produk WHERE Kategori = '$idKategori'";
    $checkProdukResult = mysqli_query($koneksi, $checkProdukSql);
    $produkRow = mysqli_fetch_assoc($checkProdukResult);
    
    if ($produkRow['total'] > 0) {
        mysqli_close($koneksi);
        echo json_encode([
            'success' => false,
            'message' => 'Kategori masih digunakan oleh beberapa produk'
        ]);
        exit();
    }
    
    // Jika tidak ada kendala, hapus kategori
    $sql = "DELETE FROM categories WHERE id_kategori = '$idKategori'";
    $result = mysqli_query($koneksi, $sql);
    
    mysqli_close($koneksi);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menghapus kategori'
        ]);
    }
    exit();
}

echo json_encode([
    'success' => false,
    'message' => 'ID kategori tidak valid'
]);
exit();
?>