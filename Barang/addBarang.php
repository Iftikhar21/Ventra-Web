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

<?php
    if (isset($_POST['btnTambah'])) {
        $kodeBarang = $_POST['Kode_Brg'];
        $namaBarang = $_POST['Nama_Brg'];
        $modal = $_POST['Modal'];
        $hargaJual = $_POST['HargaJual'];
        $ukuran = $_POST['Ukuran'];
        $bahan = $_POST['Bahan'];
        $gambar = $_POST['Gambar'];
        $kategori = $_POST['Kategori'];
        $stock = $_POST['Stock'];

        addBarang($kodeBarang, $namaBarang, $hargaJual, $modal, $ukuran, $bahan, $gambar, $kategori, $stock);

        header("Location: barang.php"); // sesuaikan lokasi redirect
        exit();
    }
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
        <a class="btn btn-info d-flex align-items-center" href="barang.php" style="width: 100px;">
            <span class="material-symbols-rounded me-2">chevron_left</span>
            Back
        </a>

      </div>
      <div class="container">
          <form action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col">
                    <label for="Nama_Brg" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" name="Nama_Brg" required>
                    </div>
                    <div class="col">
                    <label for="Kode_Brg" class="form-label">Kode Barang</label>
                    <input type="number" class="form-control" name="Kode_Brg" required>
                    </div>
                </div>
    
                <div class="row mb-3">
                    <div class="col">
                    <label for="Modal" class="form-label">Harga Modal</label>
                    <input type="number" class="form-control" name="Modal" required>
                    </div>
                    <div class="col">
                    <label for="HargaJual" class="form-label">Harga Jual</label>
                    <input type="number" class="form-control" name="HargaJual" required>
                    </div>
                </div>
    
                <div class="row mb-3">
                    <div class="col">
                    <label for="Ukuran" class="form-label">Ukuran</label>
                    <input type="text" class="form-control" name="Ukuran" required>
                    </div>
                    <div class="col">
                    <label for="Bahan" class="form-label">Bahan</label>
                    <input type="text" class="form-control" name="Bahan" required>
                    </div>
                </div>
    
                <div class="mb-3">
                    <label for="Gambar" class="form-label">Gambar (Upload)</label>
                    <input type="file" class="form-control" name="Gambar" accept="image/*" required>
                </div>
    
                <div class="mb-3">
                  <label for="Kategori" class="form-label">Kategori</label>
                  <select name="Kategori" class="form-control" required>
                    <option value="" selected disabled>-- Pilih Kategori --</option>
                    <?php foreach ($dataKategori as $kategori): ?>
                      <option value="<?= $kategori['nama_kategori'] ?>">
                        <?= $kategori['nama_kategori'] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
    
                <div class="mb-3">
                    <label for="Stock" class="form-label">Stok</label>
                    <input type="number" class="form-control" name="Stock" required>
                </div>
    
                <button class="btn btn-success d-flex align-items-center" type="submit" name="btnTambah">
                    <span class="material-symbols-rounded me-2">add</span>
                    Simpan
                </button>
            </form>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="index.js"></script>
  <script src="../js/sidebar.js"></script>
</body>
</html>