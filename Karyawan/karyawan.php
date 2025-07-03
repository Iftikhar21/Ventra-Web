<?php
session_start();
include '../Model/crudKaryawan.php';

if (!isset($_SESSION['username'])) {
  header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
  exit();
}

$data = getAllKasir();
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Fashion 24 - Kasir</title>
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
        <a class="nav-link active" href="../Karyawan/karyawan.php">
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
          <h2 class="text-dark fw-bold m-0">Karyawan</h2>
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
        <p class="text-muted">Lihat Data Karyawan</p>
      </div>

      <div class="row">
        <div class="container">
          <h2 class="fw-bold">> Data Kasir</h2>
          <a class="btn btn-success d-flex align-items-center mb-4 mt-3" href="addKasir.php" style="width: 250px;">
            <span class="material-symbols-rounded me-2">add</span>
            Tambah Anggota Kasir
          </a>
          <!-- Filter Input di Luar Tabel -->
          <div class="row mb-3">
            <div class="col-md-3">
              <input type="text" id="filterKode" class="form-control" placeholder="Cari NISN Kasir" oninput="filterTable()">
            </div>
            <div class="col-md-3">
              <input type="text" id="filterNama" class="form-control" placeholder="Cari Nama Kasir" oninput="filterTable()">
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
              <thead class="table-primary">
                <tr>
                  <th>NISN</th>
                  <th>Nama Kasir</th>
                  <th>Waktu Aktif</th>
                  <th>Waktu Non Aktif</th>
                  <th>Status</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody id="myTable">
                <?php $no = 1;
                foreach ($data as $karyawan): ?>
                  <tr>
                    <td><?= $karyawan['NISN']; ?></td>
                    <td><?= $karyawan['Nama']; ?></td>
                    <td><?= $karyawan['WaktuAktif']; ?></td>
                    <td><?= $karyawan['WaktuNonAktif']; ?></td>
                    <td>
                      <?php if ($karyawan['Status'] === 'Active'): ?>
                        <span class="text-success fw-bold"><?= $karyawan['Status']; ?></span>
                      <?php elseif ($karyawan['Status'] === 'Inactive'): ?>
                        <span class="text-danger fw-bold"><?= $karyawan['Status']; ?></span>
                      <?php else: ?>
                        <span class="text-warning fw-bold"><?= $karyawan['Status']; ?></span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <a href="editKasir.php?NISN=<?= $karyawan['NISN']; ?>" class="btn btn-warning btn-sm">
                        <i class="material-symbols-rounded" style="color: #fff; margin-top: 2px;">edit</i>
                      </a>
                      <a href="#" class="btn btn-danger btn-sm"
                        onclick="confirmDeleteKaryawan(<?= $karyawan['NISN']; ?>, '<?= addslashes($karyawan['Nama']); ?>')">
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
        </div>
      </div>
    </div>
  </main>

  <div class="modal fade" id="deleteKaryawanModal" tabindex="-1" aria-labelledby="deleteKaryawanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteKaryawanModalLabel">
            <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>
            Konfirmasi Hapus Karyawan
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <i class="material-symbols-rounded text-danger mb-3" style="font-size: 4rem;">delete_forever</i>
            <p class="fw-bold">Yakin ingin menghapus Karyawan ini?</p>
            <p id="karyawanName" class="text-muted fw-bold"></p>
            <p class="text-warning small">
              <i class="fa-solid fa-circle-info me-2"></i>
              Data yang dihapus tidak dapat dikembalikan
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa-solid fa-xmark me-2"></i>
            Batal
          </button>
          <a id="confirmDeleteKaryawanBtn" href="#" class="btn btn-danger">
            <i class="fa-solid fa-trash me-2"></i>
            Ya, Hapus
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="index.js"></script>
  <script src="../js/sidebar.js"></script>

  <script>
    function confirmDeleteKaryawan(nisn, namaKaryawan) {
      document.getElementById('karyawanName').textContent = namaKaryawan;
      document.getElementById('confirmDeleteKaryawanBtn').href = 'hapusKasir.php?NISN=' + nisn;
      new bootstrap.Modal(document.getElementById('deleteKaryawanModal')).show();
    }
  </script>
</body>

</html>