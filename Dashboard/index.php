<?php
  session_start();
  include '../Model/crudBarang.php';
  include '../Model/crudKaryawan.php';
  include '../Model/crudTransaksi.php';

  if (!isset($_SESSION['username'])) {
    header("Location: ../Login/FormLogin.php"); // Redirect kalau belum login
    exit();
  }

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ventra POS Dashboard</title>

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
        <p class="text-muted">Lihat Data Bulan Ini</p>
      </div>

      <!-- Cards -->
      <div class="row">
        <div class="col-sm-8">
          <div class="card">
            <div class="card-body" style="display: flex; justify-content: space-between; align-items: center;">
              <div>
                <h5 class="card-title">Jumlah Barang yang Hampir Habis</h5>
                <p class="card-text" style="color: red;"><?=$jumlahBarangMenipis;?></p>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalBarangHabis">Lihat Barang</button>
              </div>
              <i class="material-symbols-rounded" style="font-size: 48px; color: #ff0000; background-color:rgb(255, 186, 186); padding: 5px; border-radius: 10px;">inventory_2</i>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card">
            <div class="card-body" style="display: flex; justify-content: space-between; align-items: center;">
              <div>
                <h5 class="card-title">Jumlah Total Barang</h5>
                <p class="card-text"><?=$jumlahProduk;?></p>
              </div>
              <i class="material-symbols-rounded" style="font-size: 48px; color: #003366; background-color:rgb(133, 194, 255); padding: 5px; border-radius: 10px;">inventory_2</i>
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
                <p class="card-text"><?=$jumlahKasir;?></p>
              </div>
              <i class="material-symbols-rounded" style="font-size: 48px; color:rgb(248, 234, 43); background-color:rgb(255, 250, 187); padding: 5px; border-radius: 10px;">assignment_ind</i>
            </div>
          </div>
        </div>
        <div class="col-sm-7">
          <div class="card">
            <div class="card-body" style="display: flex; justify-content: space-between; align-items: center;">
              <div>
                <h5 class="card-title">Jumlah Transaksi Bulan Ini</h5>
                <p class="card-text"><?=$jumlahTransaksi;?></p>
              </div>
              <i class="material-symbols-rounded" style="font-size: 48px; color: #A0C878; background-color:rgb(230, 255, 204); padding: 5px; border-radius: 10px;">equalizer</i>
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
                  $stokBarang = $barang['Stock']; 
                  ?>

                  <tr>
                      <td><?=$kodeBarang?></td>
                      <td><?=$namaBarang?></td>
                      <td style="color: red;"><?=$stokBarang?></td>
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
  <script src="index.js"></script>
  <script src="../js/sidebar.js"></script>
</body>
</html>