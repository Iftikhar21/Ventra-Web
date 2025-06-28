<?php
date_default_timezone_set('Asia/Jakarta');
session_start();
include '../Model/crudBarang.php';
include '../Model/crudKaryawan.php';
include '../Model/crudTransaksi.php';
include '../Model/crudLaporan.php';
include '../Model/crudEvent.php';

if (!isset($_SESSION['username'])) {
  header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
  exit();
}

$jumlahProduk = getTotalBarang();
$jumlahKasir = getTotalKasir();
$jumlahTransaksi = getTotalTransaksi();
$jumlahBarangMenipis = getTotalBarangMenipis();
$dataBarangMenipis = getBarangMenipis();

$tanggal = date('Y-m-d');

$data = getAllLaporan();
$dataMetodePembayaranPerHari = getRekapPembayaranHariIni();

$tanggalFilter = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$dataPerTanggal = getLaporanByTanggal($tanggalFilter);
$dataMetodePembayaranPerTanggal = getRekapPembayaranByTanggal($tanggalFilter);


$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ventra POS Dashboard</title>

  <!-- Bootstrap & Icon Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Rounded" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="../Style/sidebar.css">
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="logo">
      <img src="../Img/VentraLogo.jpg" alt="logo" />
      <span class="nav-text fw-bold">Ventra POS</span>
    </div>
    <ul class="nav flex-column mt-3">
      <li class="nav-item">
        <a class="nav-link" href="../Dashboard/index.php">
          <i class="material-symbols-rounded">dashboard</i>
          <span class="nav-text">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../Barang/barang.php">
          <i class="material-symbols-rounded">inventory_2</i>
          <span class="nav-text">Barang</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../Karyawan/karyawan.php">
          <i class="material-symbols-rounded">assignment_ind</i>
          <span class="nav-text">Karyawan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../Event/event.php">
          <i class="material-symbols-rounded">event</i>
          <span class="nav-text">Event</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="../Laporan/laporan.php">
          <div class="d-flex align-items-center gap-2">
            <i class="material-symbols-rounded">bar_chart</i>
            <span class="nav-text">Laporan</span>
          </div>
        </a>
      </li>
    </ul>
    <div class="sidebar-bottom">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link" href="../Login/logout.php">
            <i class="material-symbols-rounded">logout</i>
            <span class="nav-text">Logout</span>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <button class="toggle-btn">
    <span class="material-symbols-rounded">menu</span>
  </button>

  <!-- Main Content -->
  <main class="main-content position-relative border-radius-lg ps-5 pt-3">
    <div class="container-fluid">
      <div class="mb-4">
        <nav class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="text-dark fw-bold m-0">Laporan</h2>
          <div class="d-flex align-items-center gap-4">
            <div id="clock" class="text-nowrap fw-semibold text-dark"></div> |
            <div id="date" class="text-nowrap fw-semibold text-dark"></div> |
            <div class="text-nowrap fw-semibold">Hi, <?= $username; ?> !</div>
            <div class="dropdown">
              <a class="user-avatar dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="material-symbols-rounded">account_circle</i>
              </a>
            </div>
          </div>
        </nav>
        <p class="text-muted">Lihat Data Penjualan Bulan Ini</p>
      </div>

      <div class="row">
        <div class="container">
          <div class="row mb-3 justify-content-between align-items-center">
            <div class="col-auto">
              <h2 class="fw-bold mb-3">> Data Laporan Penjualan</h2>
              <div class="alert alert-info small mb-3">
                <i class="fas fa-calendar-day me-1"></i> Menampilkan data untuk tanggal: <strong><?= date('d M Y', strtotime($tanggalFilter)); ?></strong>
              </div>
            </div>
            <div class="col-auto">
              <div class="dropdown">
                <form class="d-flex align-items-center gap-3" method="GET" action="">
                  <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-warning" onclick="resetFilter()">
                      <i class="fa-solid fa-arrows-rotate"></i>
                    </button>
                    <input type="text" class="form-control" name="tanggal" id="tanggalFilter"
                      value="<?= isset($_GET['tanggal']) ? $_GET['tanggal'] : $tanggal; ?>"
                      style="width: 150px;" readonly>
                    <i class="material-symbols-rounded fs-5">calendar_month</i>
                  </div>
                  <!-- <button type="submit" class="btn btn-primary">Terapkan</button> -->
                </form>
              </div>
            </div>
          </div>

          <?php if (empty($dataPerTanggal)): ?>
            <div class="card">
              <div class="card-body text-center">
                <i class="fas fa-exclamation-circle text-muted mb-4" style="font-size: 2rem;"></i>
                <h5 class="card-title">Tidak Ada Data</h5>
                <p class="card-text">Data tidak ditemukan untuk tanggal yang dipilih.</p>
              </div>
            </div>
          <?php endif; ?>

          <?php if (!empty($dataPerTanggal)): ?>
            <!-- Filter Input di Luar Tabel -->
            <div class="row mb-3">
              <div class="col-md-3">
                <label for="" class="form-label">ID Transaksi</label>
                <input type="text" id="filterID" class="form-control" placeholder="Cari..." oninput="filterTable()">
              </div>
              <div class="col-md-3">
                <label for="" class="form-label">Nama Produk</label>
                <input type="text" id="filterNamaProduk" class="form-control" placeholder="Cari..." oninput="filterTable()">
              </div>
              <div class="col-md-3">
                <label for="" class="form-label">Metode Pembayaran</label>
                <select name="Metode" id="filterMetodePembayaran" class="form-control" required onchange="filterTable()">
                  <option value="" selected>-- Semua Metode --</option>
                  <?php foreach ($dataMetodePembayaranPerTanggal as $metode): ?>
                    <option value="<?= $metode['metode'] ?>">
                      <?= $metode['metode'] ?> <!-- Ini yang menampilkan teks opsi -->
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-3">
                <label for="" class="form-label">Export ke Excel</label>
                <button onclick="exportToExcel()" class="btn btn-success d-flex align-items-center w-100 no-print">
                  <span class="material-symbols-rounded me-2">open_in_new</span>Export
                </button>
              </div>
            </div>


            <div class="table-responsive">
              <!-- Tabel Utama Laporan Penjualan -->
              <div class="card mb-4">
                <div class="card-body">
                  <table class="table table-striped table-hover text-center" id="mainTable">
                    <thead class="table-light">
                      <tr>
                        <!--<th width="5%">ID</th>-->
                        <th width="15%">Tanggal</th>
                        <th width="20%">Produk</th>
                        <th width="10%">Ukuran</th>
                        <th width="15%">Harga Jual</th>
                        <th width="5%">Terjual</th>
                        <th width="5%">Sisa</th>
                        <th width="20%">Sub Total</th>
                      </tr>
                    </thead>
                    <tbody id="myTable">
                      <?php if (empty($dataPerTanggal)): ?>
                        <tr>
                          <td colspan="8" class="text-center">Data tidak ada.</td>
                        </tr>
                      <?php endif; ?>
                      <?php $no = 1;
                      foreach ($dataPerTanggal as $laporan): ?>
                        <?php
                        $event = getAllEvent();
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
                          <!-- <td><?= $no++; ?></td> -->
                          <!--<td><?= $laporan['ID_Transaksi']; ?></td>-->
                          <td><?= date('d/m/Y', strtotime($laporan['tanggal_transaksi'])); ?></td>
                          <td><?= $laporan['nama_produk']; ?></td>
                          <td><?= $laporan['ukuran']; ?></td>
                          <td><?= "Rp " . number_format($laporan['harga_satuan'], 0, ',', '.'); ?></td>
                          <td><?= $laporan['JMLH']; ?></td>
                          <td><?= $laporan['stock']; ?></td>
                          <!--<td><?= $laporan['Payment']; ?></td>-->
                          <td class="fw-bold">
                            <?= "Rp. " . number_format($totalSetelahDiskon, 0, ',', '.') ?>
                            <?php if ($diskon > 0): ?>
                              <span class="badge bg-success">Diskon <?= $diskon ?></span>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>

                  <!-- Pagination -->
                  <div class="d-flex justify-content-between align-items-center mt-3">
                    <button class="btn btn-outline-primary" onclick="prevPage()">
                      <i class="fas fa-chevron-left me-1"></i> Sebelumnya
                    </button>
                    <span id="pageInfo" class="fw-bold"></span>
                    <button class="btn btn-outline-primary" onclick="nextPage()">
                      Berikutnya <i class="fas fa-chevron-right ms-1"></i>
                    </button>
                  </div>
                  <hr>
                  <div class="row mt-3">
                    <div class="col-md-6">
                      <table class="table table-bordered text-center" id="paymentMethodTable">
                        <thead class="table-light">
                          <tr>
                            <!--<th width="10%">No</th>-->
                            <th class="text-start">Metode Pembayaran</th>
                            <th>Jumlah</th>
                            <th width="30%">Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (empty($dataMetodePembayaranPerTanggal)): ?>
                            <tr>
                              <td colspan="4" class="text-center">Data tidak ada.</td>
                            </tr>
                          <?php endif; ?>

                          <?php
                          $no = 1;
                          $grandTotal = 0;

                          $metodePembayaranDenganDiskon = [];

                          foreach ($dataPerTanggal as $laporan) {
                            $event = getAllEvent();
                            $diskonTransaksi = 0;
                            $tanggalTransaksi = strtotime($laporan['tanggal_transaksi']);

                            foreach ($event as $ev) {
                              $waktuAktif = strtotime($ev['waktu_aktif']);
                              $waktuNonAktif = strtotime($ev['waktu_non_aktif']);

                              if ($tanggalTransaksi >= $waktuAktif && $tanggalTransaksi <= $waktuNonAktif) {
                                $diskonTransaksi = $ev['total_diskon'];
                                break;
                              }
                            }

                            $totalSebelumDiskon = $laporan['harga_satuan'] * $laporan['JMLH'];
                            $totalSetelahDiskon = $totalSebelumDiskon - ($totalSebelumDiskon * ($diskonTransaksi / 100));

                            $metodePembayaran = $laporan['Payment'];

                            if (!isset($metodePembayaranDenganDiskon[$metodePembayaran])) {
                              $metodePembayaranDenganDiskon[$metodePembayaran] = [
                                'metode' => $metodePembayaran,
                                'jumlah_transaksi' => 0,
                                'total' => 0
                              ];
                            }

                            $metodePembayaranDenganDiskon[$metodePembayaran]['jumlah_transaksi']++;
                            $metodePembayaranDenganDiskon[$metodePembayaran]['total'] += $totalSetelahDiskon;

                            $grandTotal += $totalSetelahDiskon;
                          }

                          foreach ($metodePembayaranDenganDiskon as $metode): ?>
                            <tr>
                              <!--<td><?= $no++; ?></td>-->
                              <td class="text-start"><?= $metode['metode']; ?></td>
                              <td><?= $metode['jumlah_transaksi']; ?></td>
                              <td><?= "Rp " . number_format($metode['total'], 0, ',', '.'); ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-secondary text-center">
                          <tr>
                            <td colspan="2">TOTAL PENDAPATAN</td>
                            <td><?= "Rp " . number_format($grandTotal, 0, ',', '.'); ?></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                    <div class="col-md-6">
                      <div class="card stats-card" id="statistikCard">
                        <h5 class="card-title text-center py-2">Statistik Penjualan</h5>
                        <?php if (empty($dataPerTanggal)): ?>
                          <p class="text-center">Data tidak ada.</p>
                        <?php endif; ?>
                        <?php if (!empty($dataPerTanggal)): ?>
                          <div class="card-body p-0">
                            <div class="row g-0">
                              <div class="col-12 col-md-6">
                                <div class="stat-item">
                                  <p class="stat-label">Transaksi</p>
                                  <div class="stat-number text-primary"><?= count($dataPerTanggal); ?></div>
                                </div>
                              </div>
                              <div class="col-12 col-md-6">
                                <div class="stat-item">
                                  <p class="stat-label">Item Terjual</p>
                                  <div class="stat-number text-info"><?= array_sum(array_column($dataPerTanggal, 'JMLH')); ?></div>
                                </div>
                              </div>
                              <div class="col-12 col-md-12">
                                <div class="stat-item">
                                  <p class="stat-label">Total Pendapatan <?= date('d M Y', strtotime($tanggalFilter)); ?></p>
                                  <div class="stat-number text-success"><?= "Rp " . number_format($grandTotal, 0, ',', '.'); ?></div>
                                </div>
                              </div>
                              <div class="row mt-2 mb-2">
                                <div class="col-12">
                                  <div class="text-center">
                                    <small class="opacity-75">
                                      <i class="fas fa-clock me-1"></i>
                                      Data diperbarui secara real-time • <?= date('d M Y', strtotime($tanggalFilter)); ?>
                                    </small>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="index.js"></script>
  <script src="../js/sidebar.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Cek apakah ada parameter tanggal di URL
      const urlParams = new URLSearchParams(window.location.search);
      const tanggalParam = urlParams.get('tanggal');

      // Jika tidak ada parameter tanggal, gunakan tanggal hari ini
      const selectedDate = tanggalParam || new Date().toISOString().split('T')[0];

      flatpickr("#tanggalFilter", {
        dateFormat: "Y-m-d",
        defaultDate: selectedDate, // Pastikan selectedDate sudah dalam timezone yang benar
        plugins: [
          new confirmDatePlugin({
            showAlways: true,
            theme: "light",
            onClick: (_, __, instance) => {
              // Set ke tanggal hari ini dengan timezone yang diinginkan
              const today = new Date();
              // Sesuaikan dengan timezone yang diinginkan
              const todayInTargetTimezone = convertToTargetTimezone(today);

              instance.setDate(todayInTargetTimezone, true);
              document.getElementById('tanggalFilter').value = todayInTargetTimezone.toISOString().split('T')[0];

              // Submit form
              document.querySelector('form[method="GET"]').submit();
            }
          })
        ],
        onChange: function(selectedDates, dateStr, instance) {
          // Jika perlu menyesuaikan timezone saat perubahan
          const adjustedDate = convertToTargetTimezone(selectedDates[0]);
          instance.setDate(adjustedDate);

          setTimeout(() => {
            document.querySelector('form[method="GET"]').submit();
          }, 100);
        }
      });

      // Fungsi untuk mengkonversi ke timezone target
      function convertToTargetTimezone(date) {
        // Contoh: Konversi ke WIB (UTC+7)
        const offset = 7; // UTC+7
        const utc = date.getTime() + (date.getTimezoneOffset() * 60000);
        return new Date(utc + (3600000 * offset));
      }

      // Initialize pagination dan filter
      filteredRows = Array.from(document.querySelectorAll('#myTable tr'));
      showPage(currentPage);
      updateStatistics();
    });
  </script>

  <script>
    // Script yang diperbaiki untuk statistik penjualan
    let currentPage = 1;
    const rowsPerPage = 10;
    let filteredRows = [];

    // Fungsi untuk filter tabel berdasarkan input
    function filterTable() {
      const filterID = document.getElementById('filterID').value.toLowerCase();
      const filterNamaProduk = document.getElementById('filterNamaProduk').value.toLowerCase();
      const filterTanggal = document.getElementById('tanggalFilter').value;
      const filterMetodePembayaran = document.getElementById('filterMetodePembayaran').value;

      const allRows = document.querySelectorAll('#myTable tr');
      filteredRows = [];

      allRows.forEach((row, index) => {
        const idText = row.cells[0].textContent.toLowerCase();
        const tanggalData = row.getAttribute('data-tanggal');
        const produkData = row.getAttribute('data-produk');
        const metodeData = row.getAttribute('data-metode');

        // Cek semua kondisi filter
        const idMatch = idText.includes(filterID) || filterID === '';
        const produkMatch = produkData.includes(filterNamaProduk) || filterNamaProduk === '';

        // Perbaiki logika filter tanggal
        let tanggalMatch = true;
        if (filterTanggal !== '') {
          const selectedDate = new Date(filterTanggal);
          const rowDate = new Date(tanggalData);

          tanggalMatch = selectedDate.getFullYear() === rowDate.getFullYear() &&
            selectedDate.getMonth() === rowDate.getMonth() &&
            selectedDate.getDate() === rowDate.getDate();
        }

        const metodeMatch = metodeData === filterMetodePembayaran || filterMetodePembayaran === '';

        if (idMatch && produkMatch && tanggalMatch && metodeMatch) {
          filteredRows.push(row);
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });

      // Reset pagination setelah filter
      currentPage = 1;
      showPage(currentPage);
      updateStatistics();
    }

    function showPage(page) {
      const allRows = document.querySelectorAll('#myTable tr');

      // Sembunyikan semua baris terlebih dahulu
      allRows.forEach(row => {
        row.style.display = 'none';
      });

      // Tentukan baris mana yang akan ditampilkan berdasarkan filter
      const rowsToShow = filteredRows.length > 0 ? filteredRows : Array.from(allRows);

      const start = (page - 1) * rowsPerPage;
      const end = start + rowsPerPage;

      // Tampilkan baris untuk halaman saat ini
      for (let i = start; i < end && i < rowsToShow.length; i++) {
        if (rowsToShow[i]) {
          rowsToShow[i].style.display = '';
        }
      }

      // Update info halaman
      const totalPages = Math.ceil(rowsToShow.length / rowsPerPage);
      document.getElementById('pageInfo').textContent = `Halaman ${page} dari ${totalPages}`;

      // Update status tombol
      const prevButton = document.querySelector('button[onclick="prevPage()"]');
      const nextButton = document.querySelector('button[onclick="nextPage()"]');

      prevButton.disabled = page === 1;
      nextButton.disabled = page >= totalPages || totalPages === 0;
    }

    function prevPage() {
      if (currentPage > 1) {
        currentPage--;
        showPage(currentPage);
      }
    }

    function nextPage() {
      const rowsToShow = filteredRows.length > 0 ? filteredRows : document.querySelectorAll('#myTable tr');
      const totalPages = Math.ceil(rowsToShow.length / rowsPerPage);

      if (currentPage < totalPages) {
        currentPage++;
        showPage(currentPage);
      }
    }

    // PERBAIKAN UTAMA: Fungsi updateStatistics yang lebih akurat
    function updateStatistics() {
      // Gunakan data yang sudah difilter berdasarkan tanggal dan filter lainnya
      const visibleRows = filteredRows.length > 0 ? filteredRows : document.querySelectorAll('#myTable tr');

      let totalTransaksi = 0;
      let totalItemTerjual = 0;
      let totalPendapatan = 0;

      // Hitung statistik berdasarkan SEMUA data yang difilter, bukan hanya yang terlihat di halaman
      visibleRows.forEach(row => {
        totalTransaksi++;

        // Ambil data dari kolom yang benar
        const itemTerjual = parseInt(row.cells[4].textContent) || 0; // Kolom "Terjual"
        const pendapatanText = row.cells[7].textContent; // Kolom "Uang"

        // Bersihkan format rupiah dan konversi ke angka
        const pendapatan = parseFloat(pendapatanText.replace(/[Rp\s,.]/g, '')) || 0;

        totalItemTerjual += itemTerjual;
        totalPendapatan += pendapatan;
      });

      // Update statistik di card dengan format yang benar
      const statElements = document.querySelectorAll('.stat-number');
      if (statElements[0]) statElements[0].textContent = totalTransaksi;
      if (statElements[1]) statElements[1].textContent = totalItemTerjual;
      if (statElements[2]) statElements[2].textContent = 'Rp ' + totalPendapatan.toLocaleString('id-ID');

      // Update juga tabel metode pembayaran berdasarkan data yang difilter
      updatePaymentMethodTable(visibleRows);
    }

    // Fungsi baru untuk update tabel metode pembayaran berdasarkan data yang difilter
    function updatePaymentMethodTable(visibleRows) {
      const paymentMethods = {};
      let grandTotal = 0;

      // Hitung ulang metode pembayaran berdasarkan data yang difilter
      visibleRows.forEach(row => {
        const metode = row.getAttribute('data-metode');
        const pendapatanText = row.cells[7].textContent;
        const pendapatan = parseFloat(pendapatanText.replace(/[Rp\s,.]/g, '')) || 0;

        // PERBAIKAN: Ambil jumlah item terjual dari kolom yang benar (kolom ke-4, index 4)
        const itemTerjual = parseInt(row.cells[4].textContent) || 0;

        if (!paymentMethods[metode]) {
          paymentMethods[metode] = {
            jumlahTransaksi: 0, // Jumlah transaksi
            jumlahItem: 0, // Total item terjual
            total: 0
          };
        }

        paymentMethods[metode].jumlahTransaksi++; // Increment jumlah transaksi
        paymentMethods[metode].jumlahItem += itemTerjual; // Tambahkan item terjual
        paymentMethods[metode].total += pendapatan;
        grandTotal += pendapatan;
      });

      // Update tabel metode pembayaran
      const paymentTableBody = document.querySelector('#paymentMethodTable tbody');
      const paymentTableFooter = document.querySelector('#paymentMethodTable tfoot');

      if (paymentTableBody) {
        paymentTableBody.innerHTML = '';
        let no = 1;

        Object.entries(paymentMethods).forEach(([metode, data]) => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${no++}</td>
            <td class="text-start">${metode}</td>
            <td>${data.jumlahItem}</td>
            <td>Rp ${data.total.toLocaleString('id-ID')}</td>
          `;
          paymentTableBody.appendChild(row);
        });
      }

      // Update total di footer
      if (paymentTableFooter) {
        paymentTableFooter.innerHTML = `
          <tr>
            <td colspan="3">TOTAL PENDAPATAN</td>
            <td>Rp ${grandTotal.toLocaleString('id-ID')}</td>
          </tr>
        `;
      }
    }
    // Fungsi export Excel yang diperbaiki - semua data dalam 1 sheet
    function exportToExcel() {
      const wb = XLSX.utils.book_new();

      // ===== SHEET 1: LAPORAN DETAIL =====
      const allData = [];

      // Header Laporan dengan styling yang lebih baik
      allData.push(['LAPORAN PENJUALAN VENTRA POS']);
      allData.push(['']);
      allData.push(['Tanggal Export:', new Date().toLocaleDateString('id-ID')]);
      allData.push(['Waktu Export:', new Date().toLocaleTimeString('id-ID')]);
      allData.push(['Filter Tanggal:', document.getElementById('tanggalFilter').value]);
      allData.push(['']);

      // ===== DETAIL TRANSAKSI =====
      allData.push(['DETAIL TRANSAKSI']);
      allData.push(['Tanggal', 'Produk', 'Ukuran', 'Harga Satuan', 'Qty Terjual', 'Sisa Stock', 'Sub Total']);

      // Ambil data transaksi yang sudah difilter
      const rowsToExport = filteredRows.length > 0 ? filteredRows : document.querySelectorAll('#myTable tr');
      let totalPendapatan = 0;
      let totalItemTerjual = 0;

      rowsToExport.forEach(row => {
        if (row.cells && row.cells.length > 0) {
          const rowData = [];

          // Tanggal
          rowData.push(row.cells[0].textContent.trim());

          // Produk
          rowData.push(row.cells[1].textContent.trim());

          // Ukuran
          rowData.push(row.cells[2].textContent.trim());

          // Harga Satuan (hilangkan format Rp dan ubah ke number)
          const harga = parseFloat(row.cells[3].textContent.replace(/[Rp\s,.]/g, '')) || 0;
          rowData.push(harga);

          // Qty Terjual
          const qty = parseInt(row.cells[4].textContent.trim()) || 0;
          rowData.push(qty);

          // Sisa Stock
          rowData.push(parseInt(row.cells[5].textContent.trim()) || 0);

          // Sub Total (hilangkan format Rp dan ubah ke number)
          const subTotal = parseFloat(row.cells[6].textContent.replace(/[Rp\s,.]/g, '')) || 0;
          rowData.push(subTotal);

          allData.push(rowData);

          totalPendapatan += subTotal;
          totalItemTerjual += qty;
        }
      });

      // Tambahkan total
      allData.push(['']);
      allData.push(['', '', '', 'TOTAL ITEM:', totalItemTerjual, '', totalPendapatan]);
      allData.push(['']);

      // ===== REKAP METODE PEMBAYARAN =====
      allData.push(['REKAP METODE PEMBAYARAN']);
      allData.push(['Metode Pembayaran', 'Jumlah Transaksi', 'Total Pendapatan']);

      // Hitung ulang metode pembayaran dari data yang difilter
      const paymentMethods = {};
      let grandTotal = 0;

      rowsToExport.forEach(row => {
        if (row.getAttribute) {
          const metode = row.getAttribute('data-metode');
          const pendapatan = parseFloat(row.cells[6].textContent.replace(/[Rp\s,.]/g, '')) || 0;

          if (!paymentMethods[metode]) {
            paymentMethods[metode] = {
              jumlahTransaksi: 0,
              total: 0
            };
          }

          paymentMethods[metode].jumlahTransaksi++;
          paymentMethods[metode].total += pendapatan;
          grandTotal += pendapatan;
        }
      });

      const rekapStartRow = allData.length;

      // Tambahkan data metode pembayaran
      Object.entries(paymentMethods).forEach(([metode, data]) => {
        allData.push([metode, data.jumlahTransaksi, data.total]);
      });

      allData.push(['']);
      allData.push(['TOTAL PENDAPATAN', '', grandTotal]);

      // Buat worksheet
      const ws = XLSX.utils.aoa_to_sheet(allData);

      // ===== STYLING DAN FORMATTING =====

      const rekapEndRow = rekapStartRow + Object.keys(paymentMethods).length + 1; // +1 untuk baris total

      for (let row = rekapStartRow; row <= rekapEndRow; row++) {
        // Format kolom total (kolom C, index 2)
        const totalCell = XLSX.utils.encode_cell({
          r: row,
          c: 2
        });
        if (ws[totalCell] && typeof ws[totalCell].v === 'number') {
          ws[totalCell].t = 'n';
          ws[totalCell].z = '_-"Rp"* #,##0_-;-"Rp"* #,##0_-;_-"Rp"* "-"_-;_-@_-';
        }

        // Format khusus untuk baris total
        if (row === rekapEndRow) {
          for (let col = 0; col < 3; col++) {
            const cellAddr = XLSX.utils.encode_cell({
              r: row,
              c: col
            });
            if (ws[cellAddr]) {
              if (!ws[cellAddr].s) ws[cellAddr].s = {};
              ws[cellAddr].s.font = {
                bold: true
              };
              ws[cellAddr].s.fill = {
                fgColor: {
                  rgb: "FFE699"
                }
              };
            }
          }
        }
      }

      // Set column widths
      ws['!cols'] = [{
          wch: 26
        }, // Tanggal
        {
          wch: 15
        }, // Produk
        {
          wch: 15
        }, // Ukuran
        {
          wch: 12
        }, // Harga
        {
          wch: 12
        }, // Qty
        {
          wch: 12
        }, // Sisa
        {
          wch: 20
        } // Sub Total
      ];

      // Set row heights untuk header
      if (!ws['!rows']) ws['!rows'] = [];
      ws['!rows'][0] = {
        hpt: 25
      }; // Header utama
      ws['!rows'][6] = {
        hpt: 20
      }; // Header section
      ws['!rows'][7] = {
        hpt: 18
      }; // Header tabel

      // Format currency untuk kolom harga dan total
      const range = XLSX.utils.decode_range(ws['!ref']);

      for (let row = 8; row <= range.e.r; row++) { // Mulai dari baris data transaksi (row 8, index 7)
        const currentRow = allData[row];
        if (!currentRow) continue;

        // Format kolom harga (kolom D, index 3)
        const hargaCell = XLSX.utils.encode_cell({
          r: row,
          c: 3
        });
        if (ws[hargaCell] && typeof ws[hargaCell].v === 'number') {
          ws[hargaCell].t = 'n';
          ws[hargaCell].z = '_-"Rp"* #,##0_-;-"Rp"* #,##0_-;_-"Rp"* "-"_-;_-@_-';
        }

        // Format kolom sub total (kolom G, index 6)
        const totalCell = XLSX.utils.encode_cell({
          r: row,
          c: 6
        });
        if (ws[totalCell] && typeof ws[totalCell].v === 'number') {
          ws[totalCell].t = 'n';
          ws[totalCell].z = '_-"Rp"* #,##0_-;-"Rp"* #,##0_-;_-"Rp"* "-"_-;_-@_-';
        }

        // Format qty columns (center alignment)
        const qtyCell = XLSX.utils.encode_cell({
          r: row,
          c: 4
        });
        const sisaCell = XLSX.utils.encode_cell({
          r: row,
          c: 5
        });

        if (ws[qtyCell]) {
          ws[qtyCell].s = {
            alignment: {
              horizontal: "center"
            }
          };
        }
        if (ws[sisaCell]) {
          ws[sisaCell].s = {
            alignment: {
              horizontal: "center"
            }
          };
        }

        // Add borders untuk data rows
        for (let col = 0; col < 7; col++) {
          const cellAddr = XLSX.utils.encode_cell({
            r: row,
            c: col
          });
          if (ws[cellAddr]) {
            if (!ws[cellAddr].s) ws[cellAddr].s = {};
            ws[cellAddr].s.border = {
              top: {
                style: "thin",
                color: {
                  rgb: "D3D3D3"
                }
              },
              bottom: {
                style: "thin",
                color: {
                  rgb: "D3D3D3"
                }
              },
              left: {
                style: "thin",
                color: {
                  rgb: "D3D3D3"
                }
              },
              right: {
                style: "thin",
                color: {
                  rgb: "D3D3D3"
                }
              }
            };
          }
        }
      }

      // Styling untuk header utama
      ws['A1'].s = {
        font: {
          bold: true,
          size: 16,
          color: {
            rgb: "FFFFFF"
          }
        },
        fill: {
          fgColor: {
            rgb: "2F5233"
          }
        },
        alignment: {
          horizontal: "center",
          vertical: "center"
        }
      };

      // Merge cells untuk header utama
      ws['!merges'] = [
        // Merge A1:G1 (header utama)
        {
          s: {
            r: 0,
            c: 0
          }, // Start row 0, column 0 (A1)
          e: {
            r: 0,
            c: 6
          } // End row 0, column 6 (G1)
        },
        // Merge E13:F13
        {
          s: {
            r: 12,
            c: 4
          }, // Start row 12, column 4 (E13)
          e: {
            r: 12,
            c: 5
          } // End row 12, column 5 (F13)
        }
      ];

      // Styling untuk section headers
      const sectionHeaders = [7]; // Row index untuk "DETAIL TRANSAKSI"
      sectionHeaders.forEach(rowIndex => {
        const cellAddr = XLSX.utils.encode_cell({
          r: rowIndex - 1,
          c: 0
        }); // -1 karena 0-indexed
        if (ws[cellAddr]) {
          ws[cellAddr].s = {
            font: {
              bold: true,
              size: 12,
              color: {
                rgb: "FFFFFF"
              }
            },
            fill: {
              fgColor: {
                rgb: "4472C4"
              }
            },
            alignment: {
              horizontal: "left",
              vertical: "center"
            }
          };
        }
      });

      // Styling untuk header tabel
      const tableHeaderRow = 7; // Baris header tabel transaksi (0-indexed)
      for (let col = 0; col < 7; col++) {
        const cellAddr = XLSX.utils.encode_cell({
          r: tableHeaderRow,
          c: col
        });
        if (ws[cellAddr]) {
          ws[cellAddr].s = {
            font: {
              bold: true,
              color: {
                rgb: "000000"
              }
            },
            fill: {
              fgColor: {
                rgb: "B4C6E7"
              }
            },
            alignment: {
              horizontal: "center",
              vertical: "center"
            },
            border: {
              top: {
                style: "medium",
                color: {
                  rgb: "4472C4"
                }
              },
              bottom: {
                style: "medium",
                color: {
                  rgb: "4472C4"
                }
              },
              left: {
                style: "thin",
                color: {
                  rgb: "4472C4"
                }
              },
              right: {
                style: "thin",
                color: {
                  rgb: "4472C4"
                }
              }
            }
          };
        }
      }

      // Styling untuk baris total
      const totalRowIndex = 8 + rowsToExport.length + 1; // Sesuaikan dengan posisi baris total
      for (let col = 0; col < 7; col++) {
        const cellAddr = XLSX.utils.encode_cell({
          r: totalRowIndex,
          c: col
        });
        if (ws[cellAddr]) {
          ws[cellAddr].s = {
            font: {
              bold: true
            },
            fill: {
              fgColor: {
                rgb: "FFE699"
              }
            },
            border: {
              top: {
                style: "medium"
              },
              bottom: {
                style: "medium"
              }
            }
          };
        }
      }


      // Tambahkan kedua sheet ke workbook
      XLSX.utils.book_append_sheet(wb, ws, "Detail Transaksi");

      // Generate filename
      const tanggalFilter = document.getElementById('tanggalFilter').value;
      const timestamp = new Date().toISOString().slice(0, 19).replace(/:/g, '-');

      // Export file
      XLSX.writeFile(wb, `Laporan_Ventra_POS${tanggalFilter}_${timestamp}.xlsx`);

      // Tampilkan notifikasi berhasil
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Export Berhasil!',
          text: 'File Excel telah berhasil diunduh',
          icon: 'success',
          timer: 2000,
          showConfirmButton: false
        });
      } else {
        alert('Export Excel berhasil!');
      }
    }

    function resetFilter() {
      // Reset semua filter input
      document.getElementById('filterID').value = '';
      document.getElementById('filterNamaProduk').value = '';
      document.getElementById('filterMetodePembayaran').value = '';

      // Reset tanggal ke hari ini
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('tanggalFilter').value = today;

      // Update flatpickr instance
      const flatpickrInstance = document.getElementById('tanggalFilter')._flatpickr;
      if (flatpickrInstance) {
        flatpickrInstance.setDate(new Date(), true);
      }

      // Redirect untuk reset semua filter
      window.location.href = window.location.pathname;
    }

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
      // Cek parameter URL
      const urlParams = new URLSearchParams(window.location.search);
      const tanggalParam = urlParams.get('tanggal');
      const selectedDate = tanggalParam || new Date().toISOString().split('T')[0];

      // Inisialisasi flatpickr
      flatpickr("#tanggalFilter", {
        dateFormat: "Y-m-d",
        defaultDate: selectedDate,
        plugins: [
          new confirmDatePlugin({
            confirmText: "Today",
            showAlways: true,
            theme: "light",
            onClick: (_, __, instance) => {
              const today = new Date().toISOString().split('T')[0];
              instance.setDate(new Date(), true);
              document.getElementById('tanggalFilter').value = today;
              document.querySelector('form[method="GET"]').submit();
            }
          })
        ],
        onChange: function(selectedDates, dateStr, instance) {
          setTimeout(() => {
            document.querySelector('form[method="GET"]').submit();
          }, 100);
        }
      });

      // Inisialisasi pagination dan statistik
      filteredRows = Array.from(document.querySelectorAll('#myTable tr'));
      showPage(currentPage);
      updateStatistics(); // Pastikan statistik dihitung dengan benar saat halaman dimuat
    });
  </script>
</body>

</html>