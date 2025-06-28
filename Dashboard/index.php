<?php
session_start();
include '../Model/crudBarang.php';
include '../Model/crudKaryawan.php';
include '../Model/crudTransaksi.php';

if (!isset($_SESSION['username'])) {
  header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
  exit();
}


$data = getAllBarang();

$jumlahProduk = getTotalBarang();
$jumlahKasir = getTotalKasir();
$jumlahTransaksi = getTotalTransaksi();
$jumlahBarangMenipis = getTotalBarangMenipis();
$dataBarangMenipis = getBarangMenipis();


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
        <a class="nav-link active" href="index.php">
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
        <a class="nav-link" href="../Laporan/laporan.php">
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
          <h2 class="text-dark fw-bold m-0">Dashboard</h2>
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
        <p class="text-muted">Lihat Data Bulan Ini</p>
      </div>

      <!-- Cards -->
      <div class="row">
        <div class="col-sm-8">
          <div class="card">
            <div class="card-body" style="display: flex; justify-content: space-between; align-items: center;">
              <div>
                <h5 class="card-title">Jumlah Barang yang Hampir Habis</h5>
                <p class="card-text" style="color: red;"><?= $jumlahBarangMenipis; ?></p>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalBarangHabis">Lihat Barang</button>
              </div>
              <i class="fa-solid fa-box" style="font-size: 36px; color: #ff0000; background-color:rgb(255, 186, 186); padding: 15px; border-radius: 10px;"></i>
              <!-- <i class="material-symbols-rounded">inventory_2</i> -->
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card">
            <div class="card-body" style="display: flex; justify-content: space-between; align-items: center;">
              <div>
                <h5 class="card-title">Jumlah Total Barang</h5>
                <p class="card-text"><?= $jumlahProduk; ?></p>
              </div>
              <i class="fa-solid fa-box" style="font-size: 36px; color: #003366; background-color:rgb(133, 194, 255); padding: 15px; border-radius: 10px;"></i>
              <!-- <i class="material-symbols-rounded" style="font-size: 48px; color: #003366; background-color:rgb(133, 194, 255); padding: 5px; border-radius: 10px;">inventory_2</i> -->
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-sm-5">
          <div class="card">
            <div class="card-body" style="display: flex; justify-content: space-between; align-items: center;">
              <div>
                <h5 class="card-title">Jumlah Karyawan</h5>
                <p class="card-text"><?= $jumlahKasir; ?></p>
              </div>
              <i class="fa-solid fa-user-tie" style="font-size: 36px; color: rgb(181, 169, 3); background-color:rgb(255, 250, 187); padding: 15px; border-radius: 10px;"></i>
              <!-- <i class="material-symbols-rounded" style="font-size: 48px; color:rgb(248, 234, 43); background-color:rgb(255, 250, 187); padding: 5px; border-radius: 10px;">assignment_ind</i> -->
            </div>
          </div>
        </div>
        <div class="col-sm-7">
          <div class="card">
            <div class="card-body" style="display: flex; justify-content: space-between; align-items: center;">
              <div>
                <h5 class="card-title">Jumlah Transaksi Bulan Ini</h5>
                <p class="card-text"><?= $jumlahTransaksi; ?></p>
              </div>
              <i class="fa-solid fa-chart-simple" style="font-size: 36px; color: #A0C878; background-color:rgb(230, 255, 204); padding: 15px; border-radius: 10px;"></i>
              <!-- <i class="material-symbols-rounded" style="font-size: 48px; color: #A0C878; background-color:rgb(230, 255, 204); padding: 5px; border-radius: 10px;">equalizer</i> -->
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="catalog-container">

          <!-- Catalog Header -->
          <div class="catalog-header">
            <h2 class="catalog-title">Katalog Produk</h2>
          </div>

          <!-- Catalog Wrapper -->
          <div class="catalog-wrapper">
            <button class="arrow-btn arrow-left" onclick="scrollCatalog(-1)">
              <i class="fas fa-chevron-left"></i>
            </button>

            <div class="catalog-scroll" id="productCatalog">
              <!-- Produk -->
              <?php foreach ($data as $barang) : ?>
                <div class="product-card">
                  <div class="product-badge">New</div>
                  <div class="product-image">
                    <img src="data:image/jpeg;base64,<?= base64_encode($barang['Gambar']); ?>" alt="Produk Busana">
                  </div>
                  <div class="product-info">
                    <h3 class="product-name"><?= $barang['Nama_Brg']; ?></h3>
                    <div class="product-meta">
                      <div class="product-price">Rp <?= number_format($barang['harga_jual'], 0, ',', '.'); ?></div>
                    </div>
                    <div class="product-details">
                      <span>Kategori: <?= $barang['nama_kategori']; ?></span><br>
                    </div>
                    <!-- <div class="product-details">
                      <span>Stok: <?= $barang['stock']; ?></span>
                    </div> -->
                    <div class="product-actions">
                      <a class="action-btn primary" href="../Barang/editBarang.php?id=<?= $barang['id']; ?>">
                        <span class="material-symbols-rounded">info</span>
                      </a>
                      <!-- <a class="action-btn delete" href="../Barang/editBarang.php?id=<?= $barang['id']; ?>">
                        <span class="material-symbols-rounded">delete</span>
                      </a> -->
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
              <button class="arrow-btn arrow-right" onclick="scrollCatalog(1)">
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>

            <!-- Pagination Dots -->
            <div class="pagination-dots">
              <div class="dot active" onclick="scrollToPage(0)"></div>
              <div class="dot" onclick="scrollToPage(1)"></div>
              <div class="dot" onclick="scrollToPage(2)"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="container mt-4">
          <div class="container-analytics" id="transaction-section">
            <div class="title">
              <h4>Jumlah Transaksi per Bulan</h4>
              <div class="export-buttons">
                <button class="export-btn" onclick="exportToPDF('transaction-section')">Export to PDF</button>
              </div>
            </div>

            <div class="summary-card">
              <div class="summary-item">
                <div class="summary-value" id="total-transactions">-</div>
                <div class="summary-label">Total Transaksi</div>
              </div>
              <div class="summary-item">
                <div class="summary-value" id="avg-transactions">-</div>
                <div class="summary-label">Rata-rata per Bulan</div>
              </div>
              <div class="summary-item">
                <div class="summary-value" id="max-transactions">-</div>
                <div class="summary-label">Transaksi Tertinggi</div>
              </div>
              <div class="summary-item">
                <div class="summary-value" id="min-transactions">-</div>
                <div class="summary-label">Transaksi Terendah</div>
              </div>
            </div>

            <div class="col-12 mt-4">
              <div class="chart-container">
                <canvas id="chartBarTransaction"></canvas>
              </div>
            </div>

            <div class="chart-details">
              <h5>Detail Transaksi</h5>
              <div id="transaction-details" class="loading">Memuat data transaksi...</div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="container mt-4">
          <div class="container-analytics" id="profit-section">
            <div class="title">
              <h4>Jumlah Pendapatan per Bulan</h4>
              <div class="export-buttons">
                <button class="export-btn" onclick="exportToPDF('profit-section')">Export to PDF</button>
              </div>
            </div>

            <div class="summary-card">
              <div class="summary-item">
                <div class="summary-value" id="total-profit">-</div>
                <div class="summary-label">Total Pendapatan</div>
              </div>
              <div class="summary-item">
                <div class="summary-value" id="avg-profit">-</div>
                <div class="summary-label">Rata-rata per Bulan</div>
              </div>
              <div class="summary-item">
                <div class="summary-value" id="max-profit">-</div>
                <div class="summary-label">Pendapatan Tertinggi</div>
              </div>
              <div class="summary-item">
                <div class="summary-value" id="min-profit">-</div>
                <div class="summary-label">Pendapatan Terendah</div>
              </div>
            </div>

            <div class="col-12 mt-4">
              <div class="chart-container">
                <canvas id="chartBarProfit"></canvas>
              </div>
            </div>

            <div class="chart-details">
              <h5>Detail Pendapatan</h5>
              <div id="profit-details" class="loading">Memuat data pendapatan...</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Kumpulan Modal -->
  <div class="modal fade" id="modalBarangHabis" tabindex="-1" aria-labelledby="modalBarangHabisLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modalBarangHabisLabel">List Barang</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Barang yang Hampir Habis</p>
          <table class="table table-bordered table-light">
            <thead class="table-dark">
              <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Stok Barang</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($dataBarangMenipis == null) {
                echo "<tr><td colspan='3' class='text-center'>Tidak ada barang yang hampir habis</td></tr>";
              }
              ?>
              <?php
              foreach ($dataBarangMenipis as $barang) {
                $kodeBarang = $barang['Kode_Brg'];
                $namaBarang = $barang['Nama_Brg'];
                $stokBarang = $barang['stock'];
              ?>

                <tr>
                  <td><?= $kodeBarang ?></td>
                  <td><?= $namaBarang ?></td>
                  <td style="color: red;"><?= $stokBarang ?></td>
                </tr>

              <?php
              }
              ?>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/docx/7.8.2/docx.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/docx/8.5.0/docx.min.js"></script>

  <script src="index.js"></script>
  <script src="../js/sidebar.js"></script>

  <script>
    // Format number with commas
    function formatNumber(num) {
      return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Format currency
    function formatCurrency(num) {
      return 'Rp' + formatNumber(num);
    }

    // Calculate statistics
    function calculateStats(data, isCurrency = false) {
      const values = data.map(item => isCurrency ? parseFloat(item.total_pendapatan) : parseInt(item.jumlah));
      const sum = values.reduce((a, b) => a + b, 0);
      const avg = sum / values.length;
      const max = Math.max(...values);
      const min = Math.min(...values);

      return {
        sum: isCurrency ? formatCurrency(sum) : formatNumber(sum),
        avg: isCurrency ? formatCurrency(avg) : formatNumber(Math.round(avg)),
        max: isCurrency ? formatCurrency(max) : formatNumber(max),
        min: isCurrency ? formatCurrency(min) : formatNumber(min),
        rawValues: values
      };
    }

    // Generate details HTML
    function generateDetailsHTML(data, isCurrency = false) {
      let html = '<table style="width:100%; border-collapse: collapse;">';
      html += '<tr style="background-color: #6c5ce7; color: white;">';
      html += '<th style="padding: 8px; text-align: left;">Bulan</th>';
      html += '<th style="padding: 8px; text-align: right;">' + (isCurrency ? 'Pendapatan' : 'Jumlah Transaksi') + '</th>';
      html += '</tr>';

      data.forEach((item, index) => {
        const value = isCurrency ? formatCurrency(parseFloat(item.total_pendapatan)) : formatNumber(parseInt(item.jumlah));
        html += `<tr style="border-bottom: 1px solid #ddd; background-color: ${index % 2 === 0 ? '#ffffff' : '#f8f9fa'}">`;
        html += `<td style="padding: 8px;">${item.bulan}</td>`;
        html += `<td style="padding: 8px; text-align: right;">${value}</td>`;
        html += '</tr>';
      });

      html += '</table>';
      return html;
    }

    // Export to PDF
    function exportToPDF(sectionId) {
      const {
        jsPDF
      } = window.jspdf;
      const element = document.getElementById(sectionId);
      const title = element.querySelector('h4').textContent;

      // Show loading state
      const exportBtn = document.querySelector(`#${sectionId} .export-btn`);
      const originalText = exportBtn.textContent;
      exportBtn.textContent = "Membuat PDF...";
      exportBtn.disabled = true;

      html2canvas(element).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF('p', 'mm', 'a4');
        const imgWidth = 210; // A4 width in mm
        const pageHeight = 295; // A4 height in mm
        const imgHeight = canvas.height * imgWidth / canvas.width;
        let heightLeft = imgHeight;
        let position = 0;

        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;

        while (heightLeft >= 0) {
          position = heightLeft - imgHeight;
          pdf.addPage();
          pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
          heightLeft -= pageHeight;
        }

        pdf.save(`${title}.pdf`);
      }).finally(() => {
        // Restore button state
        exportBtn.textContent = originalText;
        exportBtn.disabled = false;
      });
    }

    // Load data from server
    document.addEventListener("DOMContentLoaded", function() {
      // Load transaction data
      fetch('chart_data_transaksi.php')
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          processChartData(
            data,
            'chartBarTransaction',
            'Jumlah Transaksi per Bulan',
            'Jumlah Transaksi',
            false
          );
        })
        .catch(error => {
          console.error('Error loading transaction data:', error);
          document.getElementById('transaction-details').innerHTML =
            '<div style="color: red;">Gagal memuat data transaksi: ' + error.message + '</div>';
        });

      // Load profit data
      fetch('chart_data_pendapatan.php')
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          processChartData(
            data,
            'chartBarProfit',
            'Jumlah Pendapatan per Bulan',
            'Jumlah Pendapatan (Rp)',
            true
          );
        })
        .catch(error => {
          console.error('Error loading profit data:', error);
          document.getElementById('profit-details').innerHTML =
            '<div style="color: red;">Gagal memuat data pendapatan: ' + error.message + '</div>';
        });
    });

    // Variabel global untuk menyimpan instance chart
    let transactionChart = null;
    let profitChart = null;

    // Fungsi untuk memproses data chart
    function processChartData(data, chartId, chartLabel, yAxisLabel, isCurrency) {
      if (!data || data.length === 0) {
        document.getElementById(chartId.replace('chartBar', '').toLowerCase() + '-details').innerHTML =
          '<div style="color: red;">Tidak ada data yang tersedia</div>';
        return;
      }

      const labels = data.map(item => item.bulan);
      const values = data.map(item => isCurrency ? parseFloat(item.total_pendapatan) : parseInt(item.jumlah));

      // Calculate statistics
      const stats = calculateStats(data, isCurrency);

      // Update summary cards
      if (chartId === 'chartBarTransaction') {
        document.getElementById('total-transactions').textContent = stats.sum;
        document.getElementById('avg-transactions').textContent = stats.avg;
        document.getElementById('max-transactions').textContent = stats.max;
        document.getElementById('min-transactions').textContent = stats.min;
        document.getElementById('transaction-details').innerHTML = generateDetailsHTML(data, false);
      } else {
        document.getElementById('total-profit').textContent = stats.sum;
        document.getElementById('avg-profit').textContent = stats.avg;
        document.getElementById('max-profit').textContent = stats.max;
        document.getElementById('min-profit').textContent = stats.min;
        document.getElementById('profit-details').innerHTML = generateDetailsHTML(data, true);
      }

      // Get canvas context
      const ctx = document.getElementById(chartId).getContext('2d');

      // Destroy previous chart if exists
      if (chartId === 'chartBarTransaction' && transactionChart) {
        transactionChart.destroy();
      } else if (chartId === 'chartBarProfit' && profitChart) {
        profitChart.destroy();
      }

      // Create new chart
      const newChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: chartLabel,
            data: values,
            backgroundColor: 'rgba(108, 92, 231, 0.7)',
            borderColor: 'rgba(108, 92, 231, 1)',
            borderWidth: 1,
            borderRadius: 5
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            tooltip: {
              callbacks: {
                label: function(context) {
                  return isCurrency ?
                    formatCurrency(context.raw) :
                    formatNumber(context.raw);
                }
              }
            },
            legend: {
              position: 'top',
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: yAxisLabel
              },
              ticks: {
                callback: function(value) {
                  return isCurrency ?
                    formatCurrency(value) :
                    formatNumber(value);
                }
              }
            },
            x: {
              title: {
                display: true,
                text: 'Bulan'
              },
              grid: {
                display: false
              }
            }
          },
          animation: {
            duration: 1000,
            easing: 'easeInOutQuad'
          }
        }
      });

      // Save chart reference
      if (chartId === 'chartBarTransaction') {
        transactionChart = newChart;
      } else {
        profitChart = newChart;
      }
    }
  </script>

</body>

</html>