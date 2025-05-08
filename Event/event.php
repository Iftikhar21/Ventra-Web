<?php
  session_start();
  include '../Model/crudEvent.php';

  if (!isset($_SESSION['username'])) {
    header("Location: ../Login/FormLogin.php"); // Redirect kalau belum login
    exit();
  }

  $data = getAllEvent();
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
      </div>
      <div class="container">
        <a class="btn btn-success d-flex align-items-center mb-4" href="addEvent.php" style="width: 200px;">
            <span class="material-symbols-rounded me-2">add</span>
            Tambah Event
        </a>
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
              <thead class="table-primary">
                  <tr class="text-center">
                      <th>ID Event</th>
                      <th>Nama Event</th>
                      <th>Total Diskon</th>
                      <th>Waktu Aktif</th>
                      <th>Waktu Non Aktif</th>
                      <th>Aksi</th>
                  </tr>
              </thead>
              <tbody id="myTable">
                <?php $no = 1; foreach ($data as $event): ?>
                    <tr>
                        <td><?= $event['id_event']; ?></td>
                        <td><?= $event['nama_event']; ?></td>
                        <td class="text-center"><?= $event['total_diskon']; ?> %</td>
                        <td><?= $event['waktu_aktif']; ?></td>
                        <td><?= $event['waktu_non_aktif']; ?></td>
                        <td class="text-center">
                          <a href="editEvent.php?id_event=<?= $event['id_event']; ?>" class="btn btn-warning btn-sm">
                              <i class="material-symbols-rounded" style="color: #fff; margin-top: 2px;">edit</i>
                          </a>
                          <a href="hapusEvent.php?id_event=<?= $event['id_event']; ?>" class="btn btn-danger btn-sm">
                              <i class="material-symbols-rounded" style="margin-top: 2px;">delete</i>
                          </a>
                      </td>
                    </tr>
                <?php endforeach; ?>
              </tbody>
          </table>
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