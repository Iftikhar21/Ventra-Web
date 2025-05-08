<?php
  session_start();
  include '../Model/crudEvent.php';

  if (!isset($_SESSION['username'])) {
    header("Location: ../Login/FormLogin.php"); // Redirect kalau belum login
    exit();
  }
  $username = $_SESSION['username'];
?>

<?php 
    if (isset($_GET['id_event'])) {
        $idEvent = $_GET['id_event'];
    } else {
        echo "Masukkan ID Event";
        exit();
    }
    $data = getEvent($idEvent);
    if (empty($data)) {
        echo "ID Event Tidak Ditemukan !";
        exit();
    } else {
        $event = $data[0]; // Ambil data pertama

        $idEvent = $event['id_event'];
        $namaEvent = $event['nama_event'];
        $totalDiskon = $event['total_diskon']; // ini akan undefined karena tidak dikembalikan
        $waktuAktif = $event['waktu_aktif'];
        $waktuNonAktif = $event['waktu_non_aktif'];
    }
?>

<?php
    if (isset($_POST['btnEdit'])) {
        $idEvent = $_POST['id_event'];
        $namaEvent = $_POST['nama_event'];
        $totalDiskon = $_POST['total_diskon']; // ini akan undefined karena tidak dikembalikan
        $waktuAktif = $_POST['waktu_aktif'];
        $waktuNonAktif = $_POST['waktu_non_aktif'];

        $result = editEvent($idEvent, $namaEvent, $totalDiskon, $waktuAktif, $waktuNonAktif);
        if ($result) {
            header("Location:event.php");
            exit();
        } else {
            echo "<div class='alert alert-danger mt-3'>Gagal memperbarui barang.</div>";
        }
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
          <a class="nav-link" href="#">
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
            <li class="nav-item dropdown">
                <a href="#" class="text-body text-decoration-none nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="material-symbols-rounded fs-4">account_circle</i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="../Barang/tabelBarang.php">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../Pelanggan/tabelanggota.php">Logout</a></li>
                </ul>
            </li>
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
                        <label for="id_event" class="form-label">Id Event</label>
                        <input type="number" class="form-control" name="id_event" required value="<?php echo $idEvent?>" readonly>
                    </div>
                    <div class="col">
                        <label for="nama_event" class="form-label">Nama Event</label>
                        <input type="text" class="form-control" name="nama_event" required value="<?php echo $namaEvent?>">
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
    
                <div class="row mb-3">
                    <div class="col">
                    <label for="total_diskon" class="form-label">Total Diskon</label>
                    <input type="number" class="form-control" name="total_diskon" required value="<?php echo $waktuNonAktif?>">
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