<?php
  session_start();
  include '../Model/crudKaryawan.php';

  if (!isset($_SESSION['username'])) {
    header("Location: ../Login/FormLogin.php"); // Redirect kalau belum login
    exit();
  }
  $username = $_SESSION['username'];
?>

<?php 
    if (isset($_GET['ID'])) {
        $idKasir = $_GET['ID'];
    } else {
        echo "Masukkan ID Kasir";
        exit();
    }
    $data = getKasir($idKasir);
    if (empty($data)) {
        echo "ID Kasir Tidak Ditemukan !";
        exit();
    } else {
        $event = $data[0]; // Ambil data pertama

        $idKasir = $event['ID'];
        $namaKasir = $event['Nama'];
        $waktuAktif = $event['WaktuAktif'];
        $waktuNonAktif = $event['WaktuNonAktif'];
    }
?>

<?php
    if (isset($_POST['btnEdit'])) {
        $idKasir = $_POST['id_kasir'];
        $namaKasir = $_POST['nama_kasir'];
        $waktuAktif = $_POST['waktu_aktif'];
        $waktuNonAktif = $_POST['waktu_non_aktif'];

        $result = editKasir($idKasir, $namaKasir, $waktuAktif, $waktuNonAktif);
        if ($result) {
            header("Location:karyawan.php");
            exit();
        } else {
            echo "<div class='alert alert-danger mt-3'>Gagal memperbarui Karyawan.</div>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ventra POS Event</title>

  <!-- Bootstrap & Icon Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Rounded" rel="stylesheet">
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
        <a class="nav-link active" href="../Event/event.php">
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

  <!-- Main Content -->
  <main class="main-content position-relative border-radius-lg ps-5 pt-3" style="margin-left: 250px;">
    <div class="container-fluid">
      <div class="mb-4">
        <nav class="d-flex justify-content-between align-items-center mb-4">
          <button class="toggle-btn" onclick="toggleSidebar()">
            <span class="material-symbols-rounded">menu</span>
          </button>
          <h2 class="text-dark fw-bold m-0">Dashboard</h2>
          <div class="d-flex align-items-center gap-4">
            <div id="clock" class="text-nowrap fw-semibold text-dark"></div> |
            <div id="date" class="text-nowrap fw-semibold text-dark"></div> |
            <div class="text-nowrap fw-semibold">Hi, <?=$username;?> !</div>
            <li class="nav-item dropdown">
                <a href="#" class="text-body text-decoration-none nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="material-symbols-rounded fs-4">account_circle</i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="../Barang/tabelBarang.php">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../Login/logout.php">Logout</a></li>
                </ul>
            </li>
          </div>
        </nav>
        <p class="text-muted">Lihat Data Event</p>
        <a class="btn btn-info d-flex align-items-center" href="karyawan.php" style="width: 100px;">
            <span class="material-symbols-rounded me-2">chevron_left</span>
            Back
        </a>
      </div>
      <div class="container">
          <form action="" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col">
                        <label for="id_kasir" class="form-label">ID Kasir</label>
                        <input type="number" class="form-control" name="id_kasir" required value="<?php echo $idKasir?>" readonly>
                    </div>
                    <div class="col">
                        <label for="nama_kasir" class="form-label">Nama Kasir</label>
                        <input type="text" class="form-control" name="nama_kasir" required value="<?php echo $namaKasir?>">
                    </div>
                </div>
    
                <div class="row mb-3">
                    <div class="col">
                    <label for="waktu_aktif" class="form-label">Waktu Aktif</label>
                    <input type="date" class="form-control" name="waktu_aktif" required value="<?php echo $waktuAktif?>">
                    </div>
                    <div class="col">
                    <label for="waktu_non_aktif" class="form-label">Waktu Non Aktif</label>
                    <input type="date" class="form-control" name="waktu_non_aktif" required value="<?php echo $waktuNonAktif?>">
                    </div>
                </div>
    
                <button class="btn btn-success d-flex align-items-center" type="submit" name="btnEdit">
                    <span class="material-symbols-rounded me-2">check</span>
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