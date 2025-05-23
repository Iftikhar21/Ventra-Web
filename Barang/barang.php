<?php
  session_start();
  include '../Model/crudBarang.php';
  include '../Model/crudKategori.php';

  if (!isset($_SESSION['username'])) {
    header("Location: ../Login/FormLogin.php"); // Redirect kalau belum login
    exit();
  }

  $data = getAllBarang();
  $dataKategori = getAllKategori();
  $username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ventra POS Barang</title>

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
        <a class="nav-link" href="../Dashboard/index.php">
          <i class="material-symbols-rounded">dashboard</i>
          <span class="nav-text">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="../Barang/barang.php">
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
          <h2 class="text-dark fw-bold m-0">Barang</h2>
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
        <p class="text-muted">Lihat Data Barang</p>
      </div>

      <div class="container">
        <h3 class="fw-bold mb-3">> Data Barang</h3>
        <a class="btn btn-success d-flex align-items-center mb-4" href="addBarang.php" style="width: 200px;">
            <span class="material-symbols-rounded me-2">add</span>
            Tambah Barang
        </a>
        <!-- Filter Input di Luar Tabel -->
        <div class="row mb-3">
          <div class="col-md-3">
            <input type="text" id="filterKode" class="form-control" placeholder="Cari Kode Barang" onkeyup="filterTable(1, this.value)">
          </div>
          <div class="col-md-3">
            <input type="text" id="filterNama" class="form-control" placeholder="Cari Nama Barang" onkeyup="filterTable(2, this.value)">
          </div>
          <div class="col-md-3">
            <select id="filterKategori" class="form-select" onchange="filterTable(6, this.value)">
              <option value="">Semua Kategori</option>
              <?php foreach ($dataKategori as $kategori): ?>
                  <option value="<?= $kategori['nama_kategori']; ?>"><?= $kategori['nama_kategori']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="table-card table-responsive">
          <table class="table table-striped table-borderless table-hover">
              <thead class="table-primary">
                  <tr class="text-center">
                      <th>#</th>
                      <th>Kode Barang</th>
                      <th>Gambar</th>
                      <th>Nama Barang</th>
                      <th>Harga Jual</th>
                      <th>Ukuran</th>
                      <th>Kategori</th>
                      <th>Stock</th>
                      <th>Aksi</th>
                  </tr>
              </thead>
              <tbody id="myTable">
                  <?php $no = 1; foreach ($data as $barang): ?>
                  <tr class="text-center">
                      <td><?= $no++; ?></td>
                      <td><?= $barang['Kode_Brg']; ?></td>
                      <td>
                        <img src="data:image/jpeg;base64,<?= base64_encode($barang['Gambar']); ?>"  alt="Image Product">
                      </td>
                      <td><?= $barang['Nama_Brg']; ?></td>
                      <td>Rp <?= number_format($barang['HargaJual'], 0, ',', '.'); ?></td>
                      <td><?= $barang['Ukuran']; ?></td>
                      <td><?= $barang['Kategori']; ?></td>
                      <td class="text-center">
                          <?php if ($barang['Stock'] < 5): ?>
                          <span class="badge bg-danger"><?= $barang['Stock']; ?></span>
                          <?php elseif ($barang['Stock'] < 10): ?>
                          <span class="badge bg-warning"><?= $barang['Stock']; ?></span>
                          <?php else: ?>
                          <span class="badge bg-success"><?= $barang['Stock']; ?></span>
                          <?php endif; ?>
                      </td>
                      <td class="text-center">
                          <a href="editBarang.php?Kode_Brg=<?= $barang['Kode_Brg']; ?>" class="btn btn-warning btn-sm">
                              <i class="material-symbols-rounded" style="color: #fff; margin-top: 2px;">edit</i>
                          </a>
                          <a href="hapusBarang.php?Kode_Brg=<?= $barang['Kode_Brg']; ?>" class="btn btn-danger btn-sm">
                              <i class="material-symbols-rounded" style="margin-top: 2px;">delete</i>
                          </a>
                      </td>
                  </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
          <div class="d-flex justify-content-between align-items-center mt-3">
            <button class="btn btn-primary btn-sm" onclick="prevPage()">Sebelumnya</button>
            <span id="pageInfo" class="fw-bold"></span>
            <button class="btn btn-success btn-sm" onclick="nextPage()">Berikutnya</button>
          </div>
        </div>
        <h3 class="fw-bold mb-3 mt-3">> Kategori</h3>
        <a class="btn btn-success d-flex align-items-center mb-4" href="addKategori.php" style="width: 200px;">
            <span class="material-symbols-rounded me-2">add</span>
            Tambah Kategori
        </a>
        <div class="table-responsive">
          <table class="table table-striped table-bordered text-center">
              <thead class="table-primary">
                  <tr>
                      <th>#</th>
                      <th>Nama Kategori</th>
                      <th class="text-center">Aksi</th>
                  </tr>
              </thead>
              <tbody id="myTable">
                  <?php $no = 1; foreach ($dataKategori as $kategori): ?>
                  <tr>
                      <td><?= $no++; ?></td>
                      <td><?= $kategori['nama_kategori']; ?></td>
                      <td class="text-center">
                          <a href="editKategori.php?id_kategori=<?= $kategori['id_kategori']; ?>" class="btn btn-warning btn-sm">
                              <i class="material-symbols-rounded" style="color: #fff; margin-top: 2px;">edit</i>
                          </a>
                          <a href="hapusKategori.php?id_kategori=<?= $kategori['id_kategori']; ?>" class="btn btn-danger btn-sm">
                              <i class="material-symbols-rounded" style="margin-top: 2px;">delete</i>
                          </a>
                      </td>
                  </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
        </div>
      </div>

      <div class="catalog-container">

        <!-- Catalog Header -->
        <div class="catalog-header">
          <h2 class="catalog-title">Katalog Produk</h2>
          <div class="catalog-filter">
            <button class="filter-btn active">Semua</button>
            <button class="filter-btn">Terbaru</button>
            <button class="filter-btn">Populer</button>
          </div>
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
                  <div class="product-price">Rp <?= number_format($barang['HargaJual'], 0, ',', '.'); ?></div>
                </div>
                <div class="product-details">
                  <span>Kategori: <?= $barang['Kategori']; ?></span><br>
                </div>
                <div class="product-details">
                  <span>Stok: <?= $barang['Stock']; ?></span>
                </div>
                <div class="product-actions">
                  <a class="action-btn primary" href="editBarang.php?Kode_Brg=<?= $barang['Kode_Brg']; ?>">
                    <span class="material-symbols-rounded">edit</span>
                  </a>
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

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="index.js"></script>
  <script src="../js/sidebar.js"></script>

 <script>
    // Scroll the catalog horizontally
    function scrollCatalog(direction) {
      const catalog = document.getElementById('productCatalog');
      const scrollAmount = 240; // Adjust based on card width + gap
      catalog.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
      });
      
      // Update dots after scrolling
      setTimeout(updateActiveDot, 300);
    }
    
    // Scroll to specific page
    function scrollToPage(pageIndex) {
      const catalog = document.getElementById('productCatalog');
      const cardWidth = 220; // Width of product card
      const gap = 24; // Gap between cards
      const visibleCards = Math.floor(catalog.clientWidth / (cardWidth + gap));
      const scrollAmount = pageIndex * (visibleCards * (cardWidth + gap));
      
      catalog.scrollTo({
        left: scrollAmount,
        behavior: 'smooth'
      });
      
      // Update active dot
      const dots = document.querySelectorAll('.dot');
      dots.forEach((dot, index) => {
        if (index === pageIndex) {
          dot.classList.add('active');
        } else {
          dot.classList.remove('active');
        }
      });
    }
    
    // Update active dot based on scroll position
    function updateActiveDot() {
      const catalog = document.getElementById('productCatalog');
      const dots = document.querySelectorAll('.dot');
      const cardWidth = 220; // Width of product card
      const gap = 24; // Gap between cards
      const visibleCards = Math.floor(catalog.clientWidth / (cardWidth + gap));
      const scrollPosition = catalog.scrollLeft;
      const maxScroll = catalog.scrollWidth - catalog.clientWidth;
      
      // Calculate which page we're on
      const pageCount = dots.length;
      const pageSize = maxScroll / (pageCount - 1);
      let currentPage = Math.round(scrollPosition / pageSize);
      
      // Edge case for last page
      if (scrollPosition + 5 >= maxScroll) currentPage = pageCount - 1;
      
      // Update dots
      dots.forEach((dot, index) => {
        if (index === currentPage) {
          dot.classList.add('active');
        } else {
          dot.classList.remove('active');
        }
      });
    }
    
    // Handle category filter clicks
    document.querySelectorAll('.filter-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Here you would add filter functionality
        // For demonstration, we'll just scroll back to the start
        scrollToPage(0);
      });
    });
    
    // Add hover effects for product cards
    document.querySelectorAll('.product-card').forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px)';
        this.style.boxShadow = 'var(--shadow-lg)';
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = 'var(--shadow-sm)';
      });
    });
    
    // Add favorite toggle functionality
    document.querySelectorAll('.action-btn:not(.primary)').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const icon = this.querySelector('i');
        if (icon.classList.contains('far')) {
          icon.classList.remove('far');
          icon.classList.add('fas');
          icon.style.color = '#ff4757';
        } else {
          icon.classList.remove('fas');
          icon.classList.add('far');
          icon.style.color = '';
        }
      });
    });
    
    // Listen for scroll events on the catalog
    document.getElementById('productCatalog').addEventListener('scroll', function() {
      updateActiveDot();
    });
    
    // Initialize scroll position
    window.addEventListener('load', function() {
      scrollToPage(0);
    });
  </script>

</body>
</html>