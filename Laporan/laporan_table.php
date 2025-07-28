<?php
session_start();
include '../Model/crudLaporan.php';
include '../Model/crudEvent.php';

$tanggalFilter = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$dataPerTanggal = getLaporanByTanggal($tanggalFilter);
$event = getAllEvent();

if (empty($dataPerTanggal)) {
  echo '<tr><td colspan="8" class="text-center">Data tidak ada.</td></tr>';
  exit;
}

foreach ($dataPerTanggal as $laporan):
    $diskon = 0;
    $tanggalTransaksi = strtotime($laporan['tanggal_transaksi']);
    foreach ($event as $ev) {
        $waktuAktif = strtotime($ev['waktu_aktif']);
        $waktuNonAktif = strtotime($ev['waktu_non_aktif']);
        if ($tanggalTransaksi >= $waktuAktif && $tanggalTransaksi <= $waktuNonAktif) {
            $diskon = $ev['total_diskon'];
            break;
        }
    }
    $total = $laporan['harga_satuan'] * $laporan['JMLH'];
    $totalSetelahDiskon = $total - ($total * ($diskon / 100));
?>
<tr data-id="<?= $laporan['ID_Transaksi']; ?>" data-tanggal="<?= $laporan['tanggal_transaksi']; ?>" data-produk="<?= strtolower($laporan['nama_produk']); ?>" data-metode="<?= $laporan['Payment']; ?>">
    <td><?= date('d/m/Y', strtotime($laporan['tanggal_transaksi'])); ?></td>
    <td><?= $laporan['nama_produk']; ?></td>
    <td><?= $laporan['ukuran']; ?></td>
    <td><?= "Rp " . number_format($laporan['harga_satuan'], 0, ',', '.'); ?></td>
    <td><?= $laporan['JMLH']; ?></td>
    <td><?= $laporan['stock']; ?></td>
    <td><?= "Rp. " . number_format($laporan['total_harga'], 0, ',', '.') ?></td>
    <td>
        <button class="btn btn-danger btn-sm" onclick="showDeleteRowConfirmation('<?= $laporan['id']; ?>', '<?= $laporan['nama_produk']; ?>')">
            <i class="fas fa-trash"></i> Hapus
        </button>
    </td>
</tr>
<?php endforeach; ?>