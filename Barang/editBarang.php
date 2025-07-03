<?php
session_start();
include '../Model/crudBarang.php';
include '../Model/crudKategori.php';

$dataKategori = getAllKategori();


if (!isset($_SESSION['username'])) {
  header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
  exit();
}
$username = $_SESSION['username'];
?>

<?php
if (isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  echo "Masukkan ID Produk";
  exit();
}
$data = getBarang($id);
if (empty($data)) {
  echo "ID Produk Tidak Ditemukan !";
  exit();
} else {
  $barang = $data; // Ambil data pertama

  $id = $barang['id'];
  $namaBarang = $barang['Nama_Brg'];
  $bahan = $barang['Bahan'];
  $hargaJual = $barang['harga_jual'];
  $kategori = $barang['Kategori'];
  $gambar = base64_encode($barang['Gambar']); // BLOB dari DB → base64
}
?>

<?php
if (isset($_POST['btnEdit'])) {
  $id = $_POST['id'];
  $namaBarang = $_POST['Nama_Brg'];
  $bahan = $_POST['Bahan'];
  $harga = $_POST['harga_jual'];
  $kategori = $_POST['Kategori'];

  // Jika ada gambar baru, simpan gambar tersebut
  $photoTmp = isset($_FILES['Gambar']['tmp_name']) && $_FILES['Gambar']['error'] === 0
    ? $_FILES['Gambar']['tmp_name']
    : null;

  // Jika tidak ada gambar baru, gunakan gambar lama
  if ($photoTmp === null) {
    // Pertahankan gambar lama, tidak mengubahnya
    $photoTmp = $barang['Gambar']; // Gambar lama dari DB
  }

  $result = editBarang($id, $namaBarang, $bahan, $harga, $photoTmp, $kategori);
  if ($result) {
    header("Location: barang.php");
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
  <title>Fashion 24 - Edit Barang</title>
  <link rel="icon" href="../Img/logoBusanaSatu.png" type="image/x-icon">

  <!-- Bootstrap & Icon Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Rounded" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
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
              <a class="user-avatar dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="material-symbols-rounded">account_circle</i>
              </a>
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
                <label for="id" class="form-label">ID Produk</label>
                <input type="number" class="form-control" name="id" id="id" required readonly value="<?= $barang['id'] ?>">
              </div>
              <div class="col">
                <label for="Nama_Brg" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" name="Nama_Brg" required value="<?= $barang['Nama_Brg'] ?>">
              </div>
            </div>

            <div class="row mb-3">
              <div class="col">
                <label for="Bahan" class="form-label">Bahan</label>
                <input type="text" class="form-control" name="Bahan" required value="<?= $barang['Bahan'] ?>">
              </div>
              <div class="col">
                <label for="harga_jual" class="form-label">Harga</label>
                <input type="number" class="form-control" name="harga_jual" required value="<?= $barang['harga_jual'] ?>">
              </div>
            </div>

            <div class="row mb-3">
              <div class="col">
                <label for="Kategori" class="form-label">Kategori</label>
                <select name="Kategori" class="form-control" required>
                  <option value="" disabled>-- Pilih Kategori --</option>
                  <?php foreach ($dataKategori as $kategori): ?>
                    <option value="<?= $kategori['id_kategori'] ?>"
                      <?= ($kategori['id_kategori'] == $barang['Kategori']) ? 'selected' : '' ?>>
                      <?= $kategori['nama_kategori'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>


            <div class="row mb-3 align-items-center">
              <div class="col-md-6">
                <label for="Gambar" class="form-label">Gambar (Upload)</label>
                <input type="file" class="form-control" id="input-photo" name="Gambar" accept="image/*">
              </div>
              <div class="col-md-6 text-center">
                <label class="form-label d-block">Preview Gambar</label>
                <img id="preview-gambar" alt="Gambar Barang" class="img-thumbnail" style="max-height: 200px;">
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

  <div class="modal fade" id="cropImageModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crop Image</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <img id="crop-image" style="max-width: 100%; max-height: 100%;" />
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" onclick="cropImage()">Crop</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

  <script src="index.js"></script>
  <script src="../js/sidebar.js"></script>

  <script>
    // Isi dari PHP (base64 gambar)
    const gambarBase64 = "<?= $gambar ?>";

    // Tampilkan gambar ke dalam <img>
    if (gambarBase64) {
      const img = document.getElementById("preview-gambar");
      img.src = "data:image/jpeg;base64," + gambarBase64;
    } else {
      // Jika tidak ada gambar lama, biarkan placeholder kosong
      document.getElementById("preview-gambar").src = "";
    }

    document.getElementById('input-photo').addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('preview-gambar').src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    });

    let cropper;

    document.getElementById('input-photo').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
          const image = document.getElementById('crop-image');
          image.src = event.target.result;

          const modalEl = document.getElementById('cropImageModal');
          const modal = new bootstrap.Modal(modalEl);
          modal.show();

          modalEl.addEventListener('shown.bs.modal', function onShow() {
            modalEl.removeEventListener('shown.bs.modal', onShow); // supaya hanya sekali
            if (cropper) cropper.destroy();

            cropper = new Cropper(image, {
              aspectRatio: 1,
              viewMode: 1,
              autoCropArea: 1,
              responsive: true,
              background: false
            });
          });
        };
        reader.readAsDataURL(file);
      }
    });

    function cropImage() {
      if (cropper) {
        const canvas = cropper.getCroppedCanvas({
          width: 300,
          height: 300,
        });

        // Update image preview
        document.getElementById('preview-gambar').src = canvas.toDataURL();

        // Hapus cropper
        cropper.destroy();
        cropper = null;

        // Tutup modal
        const modalEl = document.getElementById('cropImageModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
      }
    }
  </script>

</body>

</html>