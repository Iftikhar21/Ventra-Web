<?php
session_start();
include '../Model/crudKategori.php';

if (!isset($_SESSION['username'])) {
  header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
  exit();
}
$username = $_SESSION['username'];
?>

<?php
if (isset($_GET['id_kategori'])) {
  $idKategori = $_GET['id_kategori'];
} else {
  echo "Masukkan ID Kategori";
  exit();
}
$data = getKategori($idKategori);
if (empty($data)) {
  echo "ID Kategori Tidak Ditemukan !";
  exit();
} else {
  $kategori = $data[0]; // Ambil data pertama

  $idKategori = $kategori['id_kategori'];
  $namaKategori = $kategori['nama_kategori'];
}
?>

<?php
if (isset($_POST['btnEdit'])) {
  $idKategori = $_POST['id_kategori'];
  $namaKategori = $_POST['nama_kategori'];

  $result = editKategori($idKategori, $namaKategori);

  if ($result) {
    header("Location:barang.php");
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Fashion 24 - Edit Kategori</title>
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
        <a class="nav-link active" href="../Barang/barang.php">
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
          <a class="nav-link" href="../Profile/profile.php">
            <i class="material-symbols-rounded">account_circle</i>
            <span class="nav-text">Profile</span>
          </a>
        </li>
        <li class="nav-item logout-item">
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
          <h2 class="text-dark fw-bold m-0">Barang</h2>
          <div class="d-flex align-items-center gap-4">
            <div id="clock" class="text-nowrap fw-semibold text-dark"></div> |
            <div id="date" class="text-nowrap fw-semibold text-dark"></div> |
            <div class="text-nowrap fw-semibold">Hi, <?= $username; ?> !</div>
            <div class="dropdown">
              <a class="user-avatar dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="material-symbols-rounded">account_circle</i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="../Profile/profile.php">
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
        <p class="text-muted">Lihat Data Barang</p>
        <a class="btn btn-info d-flex align-items-center" href="barang.php" style="width: 100px;">
          <span class="material-symbols-rounded me-2">chevron_left</span>
          Back
        </a>

      </div>
      <div class="row">
        <div class="container">
          <form action="" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
              <div class="col">
                <label for="id_kategori" class="form-label">ID Kategori</label>
                <input type="number" class="form-control" name="id_kategori" required value="<?php echo $idKategori ?>" readonly>
              </div>
              <div class="col">
                <label for="nama_kategori" class="form-label">Nama Kategori</label>
                <input type="text" class="form-control" name="nama_kategori" required value="<?php echo $namaKategori ?>">
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
  </main>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="index.js"></script>
  <script src="../js/sidebar.js"></script>
</body>

</html>