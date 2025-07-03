<?php
session_start();
include '../Model/crudBarang.php';
include '../Model/crudKategori.php';

if (!isset($_SESSION['username'])) {
  header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
  exit();
}

$data = getAllBarang();
$dataKategori = getAllKategori();
$username = $_SESSION['username'];
?>

<?php
if (isset($_POST['btnTambah'])) {
  $id = $_POST['id'];
  $namaBarang = $_POST['Nama_Brg'];
  $bahan = $_POST['Bahan'];
  $harga = $_POST['harga_jual'];
  $gambar = $_FILES['Gambar']['tmp_name'];
  $kategori = $_POST['Kategori'];

  addBarang($id, $namaBarang, $bahan, $harga, $gambar, $kategori);

  header("Location: barang.php"); // sesuaikan lokasi redirect
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ventra POS Barang</title>

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
            <div class="col">
              <input type="hidden" class="form-control" name="id" id="kodeBarang" required>
            </div>
            <div class="row mb-3">
              <div class="col">
                <label for="Nama_Brg" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" name="Nama_Brg" required>
              </div>
              <div class="col">
                <label for="Bahan" class="form-label">Bahan</label>
                <input type="text" class="form-control" name="Bahan" required>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col">
                <label for="harga_jual" class="form-label">Harga</label>
                <input type="number" class="form-control" name="harga_jual" required>
              </div>
              <div class="col">
                <label for="Kategori" class="form-label">Kategori</label>
                <select name="Kategori" class="form-control" required>
                  <option value="" selected disabled>-- Pilih Kategori --</option>
                  <?php foreach ($dataKategori as $kategori): ?>
                    <option value="<?= $kategori['id_kategori'] ?>">
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

            <button class="btn btn-success d-flex align-items-center" type="submit" name="btnTambah">
              <span class="material-symbols-rounded me-2">add</span>
              Tambah
            </button>
          </form>
        </div>
      </div>
    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

    <script>
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

            // Pastikan modal muncul
            console.log("Modal akan ditampilkan");
            modal.show();

            modalEl.addEventListener('shown.bs.modal', function onShow() {
              modalEl.removeEventListener('shown.bs.modal', onShow); // hanya sekali saat modal ditampilkan
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

    <script>
      function printBarcode() {
        const printWindow = window.open('', '_blank');
        const svgContent = document.getElementById('result').outerHTML;

        printWindow.document.write(`
              <html>
              <head><title>Print Barcode</title></head>
              <body style="text-align:center;">
                  ${svgContent}
                  <script>
                      window.onload = function() {
                          window.print();
                          window.onafterprint = function() { window.close(); };
                      };
                  <\/script>
              </body>
              </html>
          `);
        printWindow.document.close();
      }

      const input = document.getElementById('kodeBarang');
      const barcode = document.getElementById('barcode');

      input.addEventListener('input', function() {
        const value = this.value;
        if (value.length > 0) {
          JsBarcode("#barcode", value, {
            format: "CODE128",
            displayValue: true,
            lineColor: "#000",
            width: 2,
            height: 50,
            fontSize: 14
          });
        } else {
          barcode.innerHTML = ""; // hapus barcode jika kosong
        }
      });
    </script>

</body>

</html>