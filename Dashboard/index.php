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
  <title>Fashion 24 - Dashboard</title>
  <link rel="icon" href="../Img/logoBusanaSatu.png" type="image/x-icon">

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
      <img src="../Img/logoBusana.png" alt="logo" class="logo-full"/>
      <img src="../Img/logoBusanaSatu.png" alt="logo" class="logo-collapsed"/>
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
          <i class="material-symbols-rounded">package_2</i>
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
              <!-- <i class="fa-solid fa-box" style="font-size: 36px; color: #ff0000; background-color:rgb(255, 186, 186); padding: 15px; border-radius: 10px;"></i> -->
              <i class="material-symbols-rounded" style="font-size: 36px; color: #ff0000; background-color:rgb(255, 186, 186); padding: 15px; border-radius: 10px;">package_2</i>
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
              <i class="material-symbols-rounded" style="font-size: 36px; color: #003366; background-color:rgb(133, 194, 255); padding: 15px; border-radius: 10px;">package_2</i>
              <!-- <i class="fa-solid fa-box" style="font-size: 36px; color: #003366; background-color:rgb(133, 194, 255); padding: 15px; border-radius: 10px;"></i> -->
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
              <h4>Jumlah Transaksi per Tahun</h4>
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
              <h4>Jumlah Pendapatan per Tahun</h4>
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
  <!-- <div class="modal fade" id="modalBarangHabis" tabindex="-1" aria-labelledby="modalBarangHabisLabel" aria-hidden="true">
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
  </div> -->

  <div class="modal fade" id="modalBarangHabis" tabindex="-1" aria-labelledby="modalBarangHabisLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalBarangHabisLabel">List Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Filter Section -->
          <!-- <div class="row mb-3">
            <div class="col-md-8">
              <input type="text" class="form-control" id="searchProduct" placeholder="Cari nama barang...">
            </div>
            <div class="col-md-4">
              <select id="filterKategori" class="form-select" onchange="filterTable(5, this.value)">
                <option value="">Semua Kategori</option>
                <?php foreach ($dataKategori as $kategori): ?>
                  <option value="<?= $kategori['nama_kategori']; ?>"><?= $kategori['nama_kategori']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div> -->

          <!-- Product List -->
          <div class="product-list-container" style="max-height: 400px; overflow-y: auto;">
            <?php if (!empty($dataBarangMenipis)) : ?>
              <?php foreach ($dataBarangMenipis as $product) : ?>
                <a href="../Barang/editDetailBarang.php?id=<?= $product['id']; ?>&&Kode_Brg=<?= $product['Kode_Brg']; ?>" class="text-decoration-none">
                  <div class="product-item border rounded p-3 mb-2 cursor-pointer"
                    data-category="<?= $product['Kode_Brg']; ?>"
                    onclick="toggleCheckbox(this)">
                    <div class="row align-items-center">
                      <div class="col-8">
                        <div class="fw-bold"><?= $product['Nama_Brg']; ?></div>
                        <small class="text-muted"><?= $product['Kode_Brg']; ?></small>
                        <div class="fw-bold text-primary"><?= $product['ukuran']; ?></div>
                      </div>
                      <div class="col-4 text-end">
                        <div class="fw-bold text-danger fs-4"><?= $product['stock']?></div>
                      </div>
                    </div>
                  </div>
                </a>
              <?php endforeach; ?>
            <?php else : ?>
              <div class="text-center py-5">
                <div class="text-muted">Tidak ada produk yang tersedia untuk ditambahkan</div>
              </div>
            <?php endif; ?>
          </div>

          <!-- No Results Message -->
          <div id="noResultsMessage" class="text-center py-5 d-none">
            <div class="text-muted">Tidak ada produk yang sesuai dengan filter</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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

</body>

</html>