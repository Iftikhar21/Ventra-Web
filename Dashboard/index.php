<?php
session_start();
include '../Model/crudBarang.php';
include '../Model/crudAdmin.php';
include '../Model/crudKaryawan.php';
include '../Model/crudTransaksi.php';

if (!isset($_SESSION['username']) && !isset($_SESSION['ID'])) {
  header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
  exit();
}


$data = getAllBarang();

$jumlahProduk = getTotalBarang();
$jumlahKasir = getTotalKasir();
$jumlahTransaksi = getTotalTransaksi();
$jumlahBarangMenipis = getTotalBarangMenipis();
$dataBarangMenipis = getBarangMenipis();

$id = $_SESSION['ID'];
$dataAdmin = getAdminById($id);

$username = $dataAdmin['username'];
$email = $dataAdmin['email'];

if (isset($_GET['action'])) {
  if ($_GET['action'] == 'getProfile') {
    $id = $_SESSION['ID'];
    $data = getAdminById($id);

    if ($data) {
      echo json_encode([
        'status' => true,
        'username' => $data['username'],
        'email' => $data['email']
      ]);
    } else {
      echo json_encode(['status' => false, 'message' => 'Data admin tidak ditemukan']);
    }
    exit();
  }
}

// Fungsi untuk update profile admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'updateProfile') {
  $id = $_SESSION['ID'];
  $username = $_POST['username'] ?? '';
  $email = $_POST['email'] ?? '';

  // Validasi input
  if (empty($username) || empty($email)) {
    echo json_encode(['status' => false, 'message' => 'Username dan email harus diisi']);
    exit();
  }

  // Update data admin
  $success = updateAdminProfile($id, $username, $email);

  if ($success) {
    $_SESSION['username'] = $username;
    echo json_encode([
      'status' => true,
      'message' => 'Profile berhasil diupdate',
      'newUsername' => $username
    ]);
  } else {
    echo json_encode(['status' => false, 'message' => 'Gagal mengupdate profile']);
  }
  exit();
}

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
      <img src="../Img/logoBusana.png" alt="logo" class="logo-full" />
      <img src="../Img/logoBusanaSatu.png" alt="logo" class="logo-collapsed" />
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
              <a class="user-avatar dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="material-symbols-rounded">account_circle</i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#updateProfileModal">
                    <i class="fa-regular fa-user me-2"></i> Update Profile
                  </a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="../Login/logout.php">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                  </a></li>
              </ul>
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

  <!-- Modal Update Profile -->
  <div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <div class="d-flex align-items-center">
            <div class="modal-icon-circle me-3">
              <i class="fas fa-user-edit"></i>
            </div>
            <div>
              <h5 class="modal-title mb-0" id="updateProfileModalLabel">Edit Profile</h5>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body p-4">
          <form id="updateProfileForm" class="needs-validation" novalidate>
            <!-- Username Field -->
            <div class="mb-3">
              <label for="username" class="form-label fw-medium">Username</label>
              <div class="input-group">
                <span class="input-group-text bg-light">
                  <i class="fas fa-user text-muted"></i>
                </span>
                <input type="text" class="form-control" id="username" name="username" value="john_doe" required>
                <div class="invalid-feedback">
                  Please choose a username.
                </div>
              </div>
            </div>

            <!-- Email Field -->
            <div class="mb-4">
              <label for="email" class="form-label fw-medium">Email Address</label>
              <div class="input-group">
                <span class="input-group-text bg-light">
                  <i class="fas fa-envelope text-muted"></i>
                </span>
                <input type="email" class="form-control" id="email" name="email" value="john@example.com" required>
                <div class="invalid-feedback">
                  Please provide a valid email.
                </div>
              </div>
            </div>

            <!-- Status Messages -->
            <div class="alert alert-danger d-flex align-items-center d-none" id="updateProfileError" role="alert">
              <i class="fas fa-exclamation-triangle me-2 flex-shrink-0"></i>
              <div id="errorMessage">Error message here</div>
            </div>

            <div class="alert alert-success d-flex align-items-center d-none" id="updateProfileSuccess" role="alert">
              <i class="fas fa-check-circle me-2 flex-shrink-0"></i>
              <div id="successMessage">Success message here</div>
            </div>
          </form>
          <p class="small mb-0">
          <div class="row d-flex align-items-center">
            <div class="col-1">
              <i class="fa-solid fa-circle-info me-2"></i>
            </div>
            <div class="col-11">
              <span class="fw-bold">Username</span> yang diubah akan memengaruhi login kamu. Pastikan kamu mengingat <span class="fw-bold">Username</span> kamu.
            </div>
          </div>
          </p>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i>Cancel
          </button>
          <button type="button" class="btn btn-primary rounded-pill px-4" id="updateProfileBtn">
            <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
            <i class="fas fa-save me-2"></i>Save Changes
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Barang Habis -->
  <div class="modal fade" id="modalBarangHabis" tabindex="-1" aria-labelledby="modalBarangHabisLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalBarangHabisLabel">List Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Product List -->
          <div class="product-list-container" style="max-height: 400px; overflow-y: auto;">
            <?php if (!empty($dataBarangMenipis)) : ?>
              <?php foreach ($dataBarangMenipis as $product) : ?>
                <a href="../Barang/editDetailBarang.php?id=<?= $product['id']; ?>&&Kode_Brg=<?= $product['Kode_Brg']; ?>&&produk_id=<?= $product['produk_id']; ?>" class="text-decoration-none">
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
                        <div class="fw-bold text-danger fs-4"><?= $product['stock'] ?></div>
                      </div>
                    </div>
                  </div>
                </a>
              <?php endforeach; ?>
            <?php else : ?>
              <div class="text-center py-5">
                <div class="text-muted">Tidak ada produk yang stok nya menipis</div>
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

  <script>
    document.getElementById('updateProfileBtn').addEventListener('click', function(e) {
      e.preventDefault();

      const formData = new FormData(document.getElementById('updateProfileForm'));
      const errorElement = document.getElementById('updateProfileError');
      const successElement = document.getElementById('updateProfileSuccess');

      // Tampilkan loading state
      const btn = this;
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';

      fetch('index.php?action=updateProfile', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.status) {
            errorElement.classList.add('d-none');
            successElement.classList.remove('d-none');
            successElement.textContent = data.message || 'Profile updated successfully!';

            // Update UI dengan data baru
            if (data.newUsername) {
              document.querySelector('.fw-semibold').textContent = 'Hi, ' + data.newUsername + ' !';
            }

            setTimeout(() => {
              $('#updateProfileModal').modal('hide');
              successElement.classList.add('d-none');
            }, 2000);
          } else {
            successElement.classList.add('d-none');
            errorElement.classList.remove('d-none');
            errorElement.textContent = data.message || 'Failed to update profile';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          errorElement.classList.remove('d-none');
          errorElement.textContent = 'Terjadi kesalahan jaringan';
        })
        .finally(() => {
          btn.disabled = false;
          btn.textContent = 'Save changes';
        });
    });

    // Isi form saat modal dibuka
    document.getElementById('updateProfileModal').addEventListener('show.bs.modal', function() {
      document.getElementById('updateProfileError').classList.add('d-none');
      document.getElementById('updateProfileSuccess').classList.add('d-none');
      document.getElementById('username').value = '<?= $username ?>';
      document.getElementById('email').value = '<?= $email ?>';
    });
  </script>
</body>

</html>