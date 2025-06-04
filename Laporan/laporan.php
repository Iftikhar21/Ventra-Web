<?php
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

  $data = getAllLaporan();

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
        <li class="nav-item-settings">
          <a class="nav-link" href="../Settings/settings.php">
            <i class="material-symbols-rounded">settings</i>
            <span class="nav-text">Settings</span>
          </a>
        </li>
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
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="../Profile/profile.php">
                            <i class="fas fa-user-circle me-2"></i> Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="../Login/logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
          </div>
        </nav>
        <p class="text-muted">Lihat Data Penjualan Bulan Ini</p>
      </div>
      <div class="container">
        <div class="row mb-3 justify-content-between align-items-center">
          <div class="col-auto">
            <h2 class="fw-bold mb-0">> Data Laporan Penjualan</h2>
          </div>
          <div class="col-auto">
            <button class="btn btn-outline-secondary d-flex align-items-center gap-2" id="filterTanggal" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="material-symbols-rounded fs-5">calendar_month</i>
              <span>Filter Tanggal</span>
            </button>
          </div>
        </div>
        
        <!-- Filter Input di Luar Tabel -->
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="" class="form-label">ID Transaksi</label>
            <input type="text" id="filterID" class="form-control" placeholder="Cari..." oninput="filterTable()">
          </div>
          <div class="col-md-4">
            <label for="" class="form-label">Nama Produk</label>
            <input type="text" id="filterNamaProduk" class="form-control" placeholder="Cari..." oninput="filterTable()">
          </div>
          <div class="col-md-4">
            <label for="" class="form-label">Export ke Excel</label>
            <button onclick="exportToExcel()" class="btn btn-success d-flex align-items-center w-100 no-print">
                <span class="material-symbols-rounded me-2">open_in_new</span>Export
            </button>
          </div>
        </div>

        <div class="table-responsive">
          <!-- Tabel Utama Laporan Penjualan -->
          <div class="card mb-4">
            <!-- <div class="card-header bg-primary text-white">
              <h3 class="card-title mb-0">Laporan Penjualan Harian</h3>
            </div> -->
            <div class="card-body">
              <table class="table table-striped table-hover text-center">
                <thead class="table-light">
                  <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="20%">Produk</th>
                    <th width="10%">Harga</th>
                    <th width="10%">Stok Awal</th>
                    <th width="10%">Terjual</th>
                    <th width="10%">Sisa</th>
                    <th width="15%">Pendapatan</th>
                  </tr>
                </thead>
                <tbody id="myTable">
                  <?php $no = 1; foreach ($data as $laporan): ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td><?= date('d/m/Y', strtotime($laporan['tanggal_transaksi'])); ?></td>
                    <td><?= $laporan['nama_produk']; ?></td>
                    <td class="text-end"><?= "Rp ".number_format($laporan['harga_satuan'], 0,',', '.'); ?></td>
                    <td class="text-center"><?= $laporan['JMLH'] + $laporan['stock']; ?></td>
                    <td class="text-center"><?= $laporan['JMLH']; ?></td>
                    <td class="text-center"><?= $laporan['stock']; ?></td>
                    <td class="text-center fw-bold"><?= "Rp ".number_format($laporan['Total'], 0,',', '.'); ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              
              <!-- Pagination -->
              <div class="d-flex justify-content-between align-items-center mt-3">
                <button class="btn btn-outline-primary" onclick="prevPage()">
                  <i class="fas fa-chevron-left me-1"></i> Sebelumnya
                </button>
                <span id="pageInfo" class="fw-bold">Halaman 1 dari 5</span>
                <button class="btn btn-outline-primary" onclick="nextPage()">
                  Berikutnya <i class="fas fa-chevron-right ms-1"></i>
                </button>
              </div>
              <hr>
              <div class="row mt-3">
                <div class="col-md-6">
                  <table class="table table-bordered">
                    <thead class="table-light">
                      <tr>
                        <th width="10%">No</th>
                        <th>Metode Pembayaran</th>
                        <th width="30%">Jumlah</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1</td>
                        <td>QRIS</td>
                        <td class="text-end">Rp 10.000</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>Tunai</td>
                        <td class="text-end">Rp 25.000</td>
                      </tr>
                      <tr>
                        <td>3</td>
                        <td>Transfer Bank</td>
                        <td class="text-end">Rp 15.000</td>
                      </tr>
                    </tbody>
                    <tfoot class="table-secondary">
                      <tr>
                        <th colspan="2">TOTAL PENDAPATAN</th>
                        <th class="text-end">Rp 50.000</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                
                <div class="col-md-6">
                  <div class="card h-100">
                    <div class="card-body text-center">
                      <h5 class="card-title">Statistik Penjualan</h5>
                      <div class="d-flex justify-content-around mt-4">
                        <div class="text-center">
                          <div class="fs-1 text-primary"><?= count($data); ?></div>
                          <div class="text-muted">Transaksi</div>
                        </div>
                        <div class="text-center">
                          <div class="fs-1 text-success"><?= array_sum(array_column($data, 'JMLH')); ?></div>
                          <div class="text-muted">Item Terjual</div>
                        </div>
                        <div class="text-center">
                          <div class="fs-1 text-info"><?= "Rp ".number_format(array_sum(array_column($data, 'Total')), 0,',', '.'); ?></div>
                          <div class="text-muted">Total Pendapatan</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="index.js"></script>
  <script src="../js/sidebar.js"></script>

  <script>
      flatpickr("#filterTanggal", {
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
          filterTable();
        }
      });
  </script>
  <script>
    // Fungsi untuk filter tabel berdasarkan input
    function filterTable() {
      const filterID = document.getElementById('filterID').value.toLowerCase();
      const filterNamaProduk = document.getElementById('filterNamaProduk').value.toLowerCase();
      const filterTanggal = document.getElementById('filterTanggal').value;
      
      const rows = document.querySelectorAll('#myTable tr');
      
      rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const idTransaksi = cells[0].textContent.toLowerCase();
        const tanggal = cells[1].textContent.toLowerCase();
        const namaProduk = cells[2].textContent.toLowerCase();
        
        // Format tanggal untuk pencocokan
        const dateObj = new Date(cells[1].textContent);
        const formattedDate = dateObj.toISOString().split('T')[0];
        
        // Cek semua kondisi filter
        const idMatch = idTransaksi.includes(filterID) || filterID === '';
        const namaMatch = namaProduk.includes(filterNamaProduk) || filterNamaProduk === '';
        const tanggalMatch = formattedDate.includes(filterTanggal) || filterTanggal === '';
        
        // Tampilkan atau sembunyikan baris berdasarkan filter
        if (idMatch && namaMatch && tanggalMatch) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
      
      // Reset pagination setelah filter
      currentPage = 1;
      showPage(currentPage);
    }

    // Fungsi untuk export data ke Excel
    function exportToExcel() {
      const table = document.querySelector('table');
      const ws = XLSX.utils.table_to_sheet(table);
      
      // Dapatkan range data
      const range = XLSX.utils.decode_range(ws['!ref']);
      
      // Set column widths (dalam karakter)
      ws['!cols'] = [
        { wch: 5 },   // Column A - No
        { wch: 25 },  // Column B - Hari/Tanggal
        { wch: 20 },  // Column C - Nama Produk
        { wch: 15 },  // Column D - Harga
        { wch: 12 },  // Column E - Jumlah Stok Awal
        { wch: 10 },  // Column F - Terjual
        { wch: 10 },  // Column G - Sisa
        { wch: 15 }   // Column H - Uang
      ];
      
      // Style untuk setiap cell
      for (let R = range.s.r; R <= range.e.r; ++R) {
        for (let C = range.s.c; C <= range.e.c; ++C) {
          const cell_address = { c: C, r: R };
          const cell_ref = XLSX.utils.encode_cell(cell_address);
          
          if (!ws[cell_ref]) continue;
          
          // Base style untuk semua cell dengan border yang kuat
          ws[cell_ref].s = {
            border: {
              top: { style: "medium", color: { rgb: "000000" } },
              bottom: { style: "medium", color: { rgb: "000000" } },
              left: { style: "medium", color: { rgb: "000000" } },
              right: { style: "medium", color: { rgb: "000000" } }
            },
            alignment: {
              vertical: "center",
              horizontal: "center"
            },
            font: {
              name: "Arial",
              sz: 10
            }
          };
          
          // Style khusus untuk header (baris pertama)
          if (R === 0) {
            ws[cell_ref].s.fill = {
              fgColor: { rgb: "D3D3D3" }  // Light gray background
            };
            ws[cell_ref].s.font = {
              name: "Arial",
              sz: 11,
              bold: true
            };
            ws[cell_ref].s.alignment = {
              vertical: "center",
              horizontal: "center",
              wrapText: true
            };
          }
          
          // Alignment khusus berdasarkan kolom
          if (R > 0) { // Bukan header
            switch (C) {
              case 0: // No - center
                ws[cell_ref].s.alignment.horizontal = "center";
                break;
              case 1: // Hari/Tanggal - left
                ws[cell_ref].s.alignment.horizontal = "left";
                break;
              case 2: // Nama Produk - left
                ws[cell_ref].s.alignment.horizontal = "left";
                break;
              case 3: // Harga - right
              case 7: // Uang - right
                ws[cell_ref].s.alignment.horizontal = "right";
                break;
              case 4: // Stok Awal - center
              case 5: // Terjual - center
              case 6: // Sisa - center
                ws[cell_ref].s.alignment.horizontal = "center";
                break;
            }
          }
        }
      }
      
      // Set row height untuk header
      ws['!rows'] = [
        { hpt: 25 } // Header row height
      ];
      
      // Freeze panes (freeze header row)
      ws['!freeze'] = { xSplit: 0, ySplit: 1 };
      
      // Buat workbook
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, "Laporan Penjualan");
      
      // Set properties workbook
      wb.Props = {
        Title: "Laporan Penjualan",
        Subject: "Data Penjualan Harian",
        Author: "Sistem Penjualan",
        CreatedDate: new Date()
      };
      
      // Export dengan nama file yang include tanggal
      const today = new Date();
      const dateStr = today.toISOString().split('T')[0]; // Format: YYYY-MM-DD
      const timeStr = today.toTimeString().split(' ')[0].replace(/:/g, '-'); // Format: HH-MM-SS
      
      XLSX.writeFile(wb, `Laporan_Penjualan_${dateStr}_${timeStr}.xlsx`, {
        cellStyles: true,
        bookSST: false
      });
    }

    // Fungsi alternatif dengan formatting yang lebih advanced
    function exportToExcelAdvanced() {
      // Ambil data dari tabel
      const table = document.querySelector('table');
      const rows = table.querySelectorAll('tr');
      
      // Convert ke array data
      const data = [];
      rows.forEach((row, rowIndex) => {
        const rowData = [];
        const cells = row.querySelectorAll('td, th');
        cells.forEach(cell => {
          let cellValue = cell.textContent.trim();
          
          // Convert currency dan number
          if (cellValue.includes('Rp ')) {
            cellValue = parseFloat(cellValue.replace(/[Rp\s,.]/g, '')) || 0;
          } else if (!isNaN(cellValue) && cellValue !== '' && rowIndex > 0) {
            cellValue = parseFloat(cellValue) || cellValue;
          }
          
          rowData.push(cellValue);
        });
        data.push(rowData);
      });
      
      // Buat worksheet dari data
      const ws = XLSX.utils.aoa_to_sheet(data);
      
      // Set column widths
      ws['!cols'] = [
        { wch: 5 },   // No
        { wch: 25 },  // Hari/Tanggal
        { wch: 20 },  // Nama Produk
        { wch: 15 },  // Harga
        { wch: 12 },  // Stok Awal
        { wch: 10 },  // Terjual
        { wch: 10 },  // Sisa
        { wch: 15 }   // Uang
      ];
      
      // Apply styles
      const range = XLSX.utils.decode_range(ws['!ref']);
      
      for (let R = range.s.r; R <= range.e.r; ++R) {
        for (let C = range.s.c; C <= range.e.c; ++C) {
          const cell_address = { c: C, r: R };
          const cell_ref = XLSX.utils.encode_cell(cell_address);
          
          if (!ws[cell_ref]) continue;
          
          // Format currency untuk kolom harga dan uang
          if ((C === 3 || C === 7) && R > 0) {
            ws[cell_ref].z = '"Rp "#,##0';
          }
          
          // Base styling dengan border yang jelas
          ws[cell_ref].s = {
            border: {
              top: { style: "medium", color: { rgb: "000000" } },
              bottom: { style: "medium", color: { rgb: "000000" } },
              left: { style: "medium", color: { rgb: "000000" } },
              right: { style: "medium", color: { rgb: "000000" } }
            },
            alignment: {
              vertical: "center"
            },
            font: {
              name: "Arial",
              sz: 10
            }
          };
          
          // Header styling
          if (R === 0) {
            ws[cell_ref].s.fill = { fgColor: { rgb: "4472C4" } };
            ws[cell_ref].s.font = { color: { rgb: "FFFFFF" }, bold: true };
            ws[cell_ref].s.alignment.horizontal = "center";
          } else {
            // Data row alignment
            if (C === 0 || C === 4 || C === 5 || C === 6) {
              ws[cell_ref].s.alignment.horizontal = "center";
            } else if (C === 3 || C === 7) {
              ws[cell_ref].s.alignment.horizontal = "right";
            } else {
              ws[cell_ref].s.alignment.horizontal = "left";
            }
          }
        }
      }
      
      // Auto filter
      ws['!autofilter'] = { ref: ws['!ref'] };
      
      // Freeze panes
      ws['!freeze'] = { xSplit: 0, ySplit: 1 };
      
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, "Laporan Penjualan");
      
      const fileName = `Laporan_Penjualan_${new Date().toISOString().split('T')[0]}.xlsx`;
      XLSX.writeFile(wb, fileName, { cellStyles: true });
    }

    // Variabel untuk pagination
    let currentPage = 1;
    const rowsPerPage = 10;

    // Fungsi untuk menampilkan halaman tertentu
    function showPage(page) {
      const rows = document.querySelectorAll('#myTable tr:not([style="display: none;"])');
      const start = (page - 1) * rowsPerPage;
      const end = start + rowsPerPage;
      
      // Sembunyikan semua baris terlebih dahulu
      rows.forEach(row => {
        row.style.display = 'none';
      });
      
      // Tampilkan baris untuk halaman ini
      for (let i = start; i < end && i < rows.length; i++) {
        if (rows[i]) {
          rows[i].style.display = '';
        }
      }
      
      // Update info halaman
      document.getElementById('pageInfo').textContent = `Halaman ${page} dari ${Math.ceil(rows.length / rowsPerPage)}`;
      
      // Nonaktifkan tombol jika sudah di halaman pertama/terakhir
      document.querySelector('button[onclick="prevPage()"]').disabled = page === 1;
      document.querySelector('button[onclick="nextPage()"]').disabled = page === Math.ceil(rows.length / rowsPerPage);
    }

    // Fungsi untuk halaman sebelumnya
    function prevPage() {
      if (currentPage > 1) {
        currentPage--;
        showPage(currentPage);
      }
    }

    // Fungsi untuk halaman berikutnya
    function nextPage() {
      const rows = document.querySelectorAll('#myTable tr:not([style="display: none;"])');
      if (currentPage < Math.ceil(rows.length / rowsPerPage)) {
        currentPage++;
        showPage(currentPage);
      }
    }

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
      showPage(currentPage);
      
      // Tambahkan event listener untuk input filter
      document.getElementById('filterID').addEventListener('input', filterTable);
      document.getElementById('filterNamaProduk').addEventListener('input', filterTable);
      
      // Load library XLSX untuk export Excel
      const script = document.createElement('script');
      script.src = 'https://cdn.sheetjs.com/xlsx-0.19.3/package/dist/xlsx.full.min.js';
      document.head.appendChild(script);
    });
  </script>
</body>
</html>