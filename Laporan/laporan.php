<?php
  date_default_timezone_set('Asia/Jakarta');
  session_start();
  include '../Model/crudBarang.php';
  include '../Model/crudKaryawan.php';
  include '../Model/crudTransaksi.php';
  include '../Model/crudLaporan.php';

  if (!isset($_SESSION['username'])) {
    header("Location: ../Login/FormLogin.php"); // Redirect kalau belum login
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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

  <!-- Main Content -->
  <main class="main-content position-relative border-radius-lg ps-5 pt-3" style="margin-left: 250px;">
    <div class="container-fluid">
      <div class="mb-4">
        <nav class="d-flex justify-content-between align-items-center mb-4">
          <button class="toggle-btn" onclick="toggleSidebar()">
            <span class="material-symbols-rounded">menu</span>
          </button>
          <h2 class="text-dark fw-bold m-0">Laporan</h2>
          <div class="d-flex align-items-center gap-4">
            <div id="clock" class="text-nowrap fw-semibold text-dark"></div> |
            <div id="date" class="text-nowrap fw-semibold text-dark"></div> |
            <div class="text-nowrap fw-semibold">Hi, <?=$username;?> !</div>
            <div class="dropdown">
                <a class="user-avatar dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="material-symbols-rounded">account_circle</i>
                </a>
            </div>
          </div>
        </nav>
        <p class="text-muted">Lihat Data Penjualan Bulan Ini</p>
      </div>
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
                    <th width="5%">ID</th>
                    <th width="15%">Tanggal</th>
                    <th width="20%">Produk</th>
                    <th width="10%">Harga Jual</th>
                    <th width="5%">Terjual</th>
                    <th width="5%">Sisa</th>
                    <th width="20%">Metode Pembayaran</th>
                    <th width="20%">Uang</th>
                  </tr>
                </thead>
                <tbody id="myTable">
                  <?php if (empty($dataPerTanggal)): ?>
                    <tr>
                      <td colspan="8" class="text-center">Data tidak ada.</td>
                    </tr>
                  <?php endif; ?>
                  <?php $no = 1; foreach ($dataPerTanggal as $laporan): ?>
                  <tr data-id="<?= $laporan['ID_Transaksi']; ?>" data-tanggal="<?= $laporan['tanggal_transaksi']; ?>" data-produk="<?= strtolower($laporan['nama_produk']); ?>" data-metode="<?= $laporan['Payment']; ?>">
                    <!-- <td><?= $no++; ?></td> -->
                    <td><?= $laporan['ID_Transaksi']; ?></td>
                    <td><?= date('d/m/Y', strtotime($laporan['tanggal_transaksi'])); ?></td>
                    <td><?= $laporan['nama_produk']; ?></td>
                    <td><?= "Rp ".number_format($laporan['harga_satuan'], 0,',', '.'); ?></td>
                    <td><?= $laporan['JMLH']; ?></td>
                    <td><?= $laporan['stock']; ?></td>
                    <td><?= $laporan['Payment']; ?></td>
                    <td class="fw-bold"><?= "Rp ".number_format($laporan['Total'], 0,',', '.'); ?></td>
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
                        <th width="10%">No</th>
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
                      <?php $no = 1; $grandTotal = 0; foreach($dataMetodePembayaranPerTanggal as $metode): ?>
                        <?php $grandTotal += $metode['total']; ?>
                        <tr>
                          <td><?= $no++; ?></td>
                          <td class="text-start"><?= $metode['metode']; ?></td>
                          <td><?= $metode['jumlah_transaksi']; ?></td>
                          <td><?= "Rp ".number_format($metode['total'], 0,',', '.'); ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-secondary text-center">
                      <tr>
                        <td colspan="3">TOTAL PENDAPATAN</td>
                        <td><?= "Rp ".number_format($grandTotal, 0,',', '.'); ?></td>
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
                              <div class="stat-number text-success"><?= "Rp ".number_format($grandTotal, 0,',', '.'); ?></div>
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
        
        if (!paymentMethods[metode]) {
          paymentMethods[metode] = {
            jumlah: 0,
            total: 0
          };
        }
        
        paymentMethods[metode].jumlah++;
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
            <td>${data.jumlah}</td>
            <td>Rp ${data.total.toLocaleString('id-ID')}</td>
          `;
          paymentTableBody.appendChild(row);
        });
      }
      
      // Update total di footer
      if (paymentTableFooter) {
        paymentTableFooter.innerHTML = `
          <tr>
            <th colspan="3">TOTAL PENDAPATAN</th>
            <th class="text-end">Rp ${grandTotal.toLocaleString('id-ID')}</th>
          </tr>
        `;
      }
    }

    // Fungsi export Excel yang diperbaiki - semua data dalam 1 sheet
    function exportToExcel() {
      const wb = XLSX.utils.book_new();
      
      // Satu sheet untuk semua data
      const allData = [];
      
      // ===== SECTION 1: HEADER LAPORAN =====
      allData.push(['LAPORAN PENJUALAN VENTRA POS']);
      allData.push(['Tanggal Export: ' + new Date().toLocaleDateString('id-ID')]);
      allData.push(['Waktu Export: ' + new Date().toLocaleTimeString('id-ID')]);
      allData.push(['Filter Tanggal: ' + document.getElementById('tanggalFilter').value]);
      allData.push([]); // Baris kosong
      
      // ===== SECTION 2: DETAIL TRANSAKSI =====
      allData.push(['=== DETAIL TRANSAKSI ===']);
      allData.push(['ID', 'Tanggal', 'Produk', 'Harga Jual', 'Terjual', 'Sisa', 'Metode Pembayaran', 'Total']);
      
      // Ambil data transaksi (gunakan data yang sudah difilter)
      const rowsToExport = filteredRows.length > 0 ? filteredRows : document.querySelectorAll('#myTable tr');
      
      rowsToExport.forEach(row => {
        const rowData = [];
        for (let i = 0; i < row.cells.length; i++) {
          rowData.push(row.cells[i].textContent);
        }
        allData.push(rowData);
      });

      allData.push([]); // Baris kosong

       // ===== SECTION 3: METODE PEMBAYARAN =====
      allData.push(['No', 'Metode Pembayaran', 'Jumlah Transaksi', 'Total']);
      
      // Ambil data dari tabel metode pembayaran
      const paymentRows = document.querySelectorAll('#paymentMethodTable tbody tr');
      paymentRows.forEach((row) => {
        const rowData = [];
        for (let i = 0; i < row.cells.length; i++) {
          rowData.push(row.cells[i].textContent);
        }
        allData.push(rowData);
      });
      
      // Tambahkan total pendapatan
      const footerRow = document.querySelector('#paymentMethodTable tfoot tr');
      if (footerRow) {
        allData.push(['', 'TOTAL PENDAPATAN', '', footerRow.cells[1].textContent]);
      }
      
      allData.push([]); // Baris kosong
      allData.push([]); // Baris kosong
      
      // Buat worksheet
      const ws = XLSX.utils.aoa_to_sheet(allData);
      
      // Set column widths untuk readability
      ws['!cols'] = [
        { wch: 3 },   // Column A
        { wch: 11 },  // Column B
        { wch: 13 },  // Column C
        { wch: 10 },  // Column D
        { wch: 6 },  // Column E
        { wch: 4 },  // Column F
        { wch: 15 },  // Column G
        { wch: 10 }   // Column H
      ];
      
      // Styling untuk header sections (opsional, jika mendukung)
      const headerCells = ['A1', 'A6', 'A11', 'A' + (14 + paymentRows.length)];
      headerCells.forEach(cell => {
        if (ws[cell]) {
          ws[cell].s = {
            font: { bold: true, size: 12 },
            fill: { fgColor: { rgb: "E6E6FA" } }
          };
        }
      });
      
      // Tambahkan worksheet ke workbook
      XLSX.utils.book_append_sheet(wb, ws, "Laporan Lengkap");
      
      // Generate filename dengan tanggal dan waktu
      const now = new Date();
      const dateStr = now.toISOString().split('T')[0];
      const timeStr = now.toTimeString().split(' ')[0].replace(/:/g, '-');
      const tanggalFilter = document.getElementById('tanggalFilter').value;
      
      // Export file
      XLSX.writeFile(wb, `Laporan_Lengkap_${tanggalFilter}_${timeStr}.xlsx`);
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