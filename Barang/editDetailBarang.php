<?php
session_start();
include '../Model/crudBarang.php';
include '../Model/crudKategori.php';

if (!isset($_SESSION['username'])) {
  header("Location: ../Login/formLogin.php");
  exit();
}

$username = $_SESSION['username'];

// Ambil kode barang dari URL
$kodeBarang = isset($_GET['Kode_Brg']) ? $_GET['Kode_Brg'] : '';

// Ambil data detail barang
$data = getDetailBarangByEdit($kodeBarang);
if (!$data) {
  echo "<script>alert('Barang tidak ditemukan!');</script>";
  echo "<script>window.location='detailBarang.php';</script>";
  exit;
}

$data = $data[0];
$produk_id = $data['produk_id'];

// Update detail barang jika form disubmit
if (isset($_POST['btnUpdate'])) {
  $kodeBarangBaru = $_POST['kodeBarang']; // Kode Barang baru dari form
  $kodeBarangLama = $_GET['Kode_Brg']; // Kode Barang lama dari URL

  $ukuran = $_POST['ukuran'];
  $stock = $_POST['stock'];
  $barcode = $_POST['barcode'];

  // Inisialisasi $patternData sebagai null
  $patternData = null;

  // Cek apakah ada file baru diupload
  if ($_FILES['pattern']['error'] === UPLOAD_ERR_OK) {
    $patternTmp = $_FILES['pattern']['tmp_name'];
    $patternData = file_get_contents($patternTmp);
  }

  // Panggil fungsi update dengan parameter baru
  $result = updateDetailBarang($kodeBarangLama, $kodeBarangBaru, $produk_id, $ukuran, $patternData, $barcode, $stock);

  if ($result == 1) {
    echo "<script>alert('Detail barang berhasil diperbarui!');</script>";
    // Redirect dengan Kode Barang baru jika berubah
    $redirectKode = ($kodeBarangBaru != $kodeBarangLama) ? $kodeBarangBaru : $kodeBarangLama;
    echo "<script>window.location='detailBarang.php?id={$produk_id}';</script>";
  } else {
    echo "<script>alert('Gagal memperbarui detail barang!');</script>";
  }
}

$id = isset($_GET['produk_id']) ? $_GET['produk_id'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Fashion 24 - Edit Detail Barang</title>
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
          <h2 class="text-dark fw-bold m-0">Detail Barang</h2>
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
        <p class="text-muted">Lihat Data Detail Barang</p>
        <a class="btn btn-info d-flex align-items-center" href="detailBarang.php?id=<?= $id ?>" style="width: 100px;">
          <span class="material-symbols-rounded me-2">chevron_left</span>
          Back
        </a>
      </div>

      <div class="row">
        <div class="container">
          <h3 class="fw-bold mb-3">> Edit Detail Barang</h3>
          <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="produk_id" value="<?= $id ?>">
            <input type="hidden" name="barcode" id="barcodeInput">
            <div class="row mb-3">
              <div class="col">
                <label for="kodeBarang" class="form-label">Kode Barang</label>
                <input type="text" class="form-control" name="kodeBarang" id="kodeBarang" required value="<?= $data['Kode_Brg'] ?>">
              </div>
              <div class="col">
                <label for="ukuran" class="form-label">Ukuran</label>
                <input type="text" class="form-control" name="ukuran" required value="<?= $data['ukuran'] ?>">
              </div>
              <div class="col">
                <label for="stock" class="form-label">Stok</label>
                <input type="number" class="form-control" name="stock" required value="<?= $data['stock'] ?>">
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-12">
                <label for="barcode" class="form-label">Preview Barcode</label>
              </div>
              <div class="col barcode">
                <svg id="barcode"></svg>
              </div>
            </div>


            <div class="row mb-3 align-items-center">
              <div class="col-md-6">
                <label for="pattern" class="form-label">Pattern (Upload)</label>
                <input type="file" class="form-control" id="input-photo" name="pattern" accept="image/*">
              </div>
              <div class="col-md-6 text-center">
                <label class="form-label d-block">Preview Pattern</label>
                <img id="preview-pattern" alt="Gambar Barang" class="img-thumbnail" style="max-height: 200px;">
              </div>
            </div>

            <button class="btn btn-success d-flex align-items-center" type="submit" name="btnUpdate">
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
        document.getElementById('preview-pattern').src = canvas.toDataURL();

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
    function generateBarcode(value) {
      if (value && value.length > 0) {
        JsBarcode("#barcode", value, {
          format: "CODE128",
          displayValue: true,
          lineColor: "#000",
          width: 2,
          height: 50,
          fontSize: 14
        });
        document.getElementById("barcodeInput").value = value;
      }
    }

    // Generate barcode saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
      const kodeBarang = document.getElementById('kodeBarang').value;
      generateBarcode(kodeBarang);

      // Tetap pertahankan event listener untuk input perubahan
      document.getElementById('kodeBarang').addEventListener('input', function() {
        generateBarcode(this.value);
      });
    });

    function printBarcode() {
      // Dapatkan barcode yang dipilih
      const selectedBarcodes = [];
      document.querySelectorAll('.barcode-check:checked').forEach(checkbox => {
        selectedBarcodes.push(checkbox.dataset.barcode);
      });

      if (selectedBarcodes.length === 0) {
        alert('Pilih minimal satu barcode untuk dicetak!');
        return;
      }

      // Simpan konten asli
      const originalContent = document.body.innerHTML;

      // Buat konten untuk dicetak
      let printContent = '<div style="text-align:center;padding:20px;">';
      printContent += '<h2>Daftar Barcode</h2>';
      printContent += '<div style="display:flex;flex-wrap:wrap;justify-content:center;gap:20px;">';

      selectedBarcodes.forEach(barcode => {
        printContent += `
            <div style="margin:10px;text-align:center;">
                <svg id="print-barcode-${barcode}" width="200" height="100"></svg>
                <div style="margin-top:5px;">${barcode}</div>
            </div>`;
      });

      printContent += '</div></div>';

      // Ganti konten body sementara
      document.body.innerHTML = printContent;

      // Generate barcode setelah DOM diupdate
      setTimeout(() => {
        selectedBarcodes.forEach(barcode => {
          JsBarcode(`#print-barcode-${barcode}`, barcode, {
            format: "CODE128",
            displayValue: false,
            lineColor: "#000",
            width: 2,
            height: 50,
            fontSize: 14
          });
        });

        // Cetak dan kembalikan konten asli
        window.print();
        document.body.innerHTML = originalContent;
      }, 100);
    }

    // Fungsi untuk select all
    document.getElementById('selectAll').addEventListener('change', function() {
      document.querySelectorAll('.barcode-check').forEach(checkbox => {
        checkbox.checked = this.checked;
      });
    });


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

    document.getElementById("kodeBarang").addEventListener("input", function() {
      const kode = this.value;
      JsBarcode("#barcode", kode);
      document.getElementById("barcodeInput").value = kode;
    });
  </script>


</body>

</html>