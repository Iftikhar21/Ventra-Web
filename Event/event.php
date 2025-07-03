<?php
session_start();
include '../Model/crudEvent.php';

if (!isset($_SESSION['username'])) {
  header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
  exit();
}

$data = getAllEvent();
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ventra POS Event</title>

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
        <a class="nav-link" href="../Dashboard/index.php">
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

  <button class="toggle-btn">
    <span class="material-symbols-rounded">menu</span>
  </button>

  <!-- Main Content -->
  <main class="main-content position-relative border-radius-lg ps-5 pt-3">
    <div class="container-fluid">
      <div class="mb-4">
        <nav class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="text-dark fw-bold m-0">Event</h2>
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
        <p class="text-muted">Lihat Data Event</p>
      </div>

      <div class="row">
        <div class="container">
          <h2 class="fw-bold">> Data Event</h2>
          <a class="btn btn-success d-flex align-items-center mb-4 mt-3" href="addEvent.php" style="width: 200px;">
            <span class="material-symbols-rounded me-2">add</span>
            Tambah Event
          </a>
          <!-- Filter Input di Luar Tabel -->
          <div class="row mb-3">
            <div class="col-md-3">
              <label for="filterKode" class="form-label">ID Event</label>
              <input type="text" id="filterKode" class="form-control" placeholder="Cari ID Event" onkeyup="filterTable(0, this.value)">
            </div>
            <div class="col-md-3">
              <label for="filterNama" class="form-label">Nama Event</label>
              <input type="text" id="filterNama" class="form-control" placeholder="Cari Nama Event" onkeyup="filterTable(1, this.value)">
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
              <thead class="table-primary">
                <tr class="text-center">
                  <th>ID Event</th>
                  <th>Nama Event</th>
                  <th>Total Diskon</th>
                  <th>Waktu Aktif</th>
                  <th>Waktu Non Aktif</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="myTable">
                <?php if (empty($data)) : ?>
                  <tr class="text-center">
                    <td colspan="6">Data Tidak Ada</td>
                  </tr>
                <?php endif; ?>
                <?php $no = 1;
                foreach ($data as $event): ?>
                  <tr>
                    <td><?= $event['id_event']; ?></td>
                    <td><?= $event['nama_event']; ?></td>
                    <td class="text-center"><?= $event['total_diskon']; ?> %</td>
                    <td><?= $event['waktu_aktif']; ?></td>
                    <td><?= $event['waktu_non_aktif']; ?></td>
                    <td>
                      <?php if ($event['Status'] === 'Active'): ?>
                        <span class="text-success fw-bold"><?= $event['Status']; ?></span>
                      <?php elseif ($event['Status'] === 'Inactive'): ?>
                        <span class="text-danger fw-bold"><?= $event['Status']; ?></span>
                      <?php else: ?>
                        <span class="text-warning fw-bold"><?= $event['Status']; ?></span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <a href="detailEvent.php?id_event=<?= $event['id_event']; ?>" class="btn btn-info btn-sm text-light">
                        <i class="fa-regular fa-eye me-2"></i>Lihat Detail
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
        </div>
      </div>
    </div>
  </main>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/sidebar.js"></script>
  <script src="index.js" defer></script>

</body>

</html>