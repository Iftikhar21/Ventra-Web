<?php
session_start();
include '../Model/crudBarang.php';
include '../Model/crudKategori.php';

if (!isset($_SESSION['username'])) {
  header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
  exit();
}

$username = $_SESSION['username'];
?>

<?php
// Get product code from URL parameter
$id = isset($_GET['id']) ? $_GET['id'] : '';

// If form is submitted for adding a new detail
if (isset($_POST['btnTambah'])) {
  $kodeBarang = $_POST['kodeBarang'];
  $produk_id = $_POST['produk_id'];
  $ukuran = $_POST['ukuran'];
  $pattern = $_FILES['pattern']['tmp_name'];
  $barcode = $_POST['barcode'];
  $stock = $_POST['stock'];

  // Call function to add product detail
  $result = addDetailBarang($kodeBarang, $produk_id, $ukuran, $pattern,  $barcode, $stock);

  if ($result == 1) {
    echo "<script>window.location='detailBarang.php?id=" . $id . "';</script>";
  } else {
    echo "<script>alert('Gagal menambahkan detail barang!');</script>";
  }
}

// Get product data
$data = getBarang($id);
$allBarang = getAllBarang();
$dataUkuran = getUkuranWhereID($id);

// If product not found, redirect to product list
if (empty($data)) {
  echo "<script>alert('Barang tidak ditemukan!');</script>";
  echo "<script>window.location='barang.php';</script>";
  exit;
}

// Get existing details for this product
$sqlDetail = getDetailBarangByProduk($id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Fashion 24 - Detail Barang</title>
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
        <a class="btn btn-info d-flex align-items-center" href="barang.php" style="width: 100px;">
          <span class="material-symbols-rounded me-2">chevron_left</span>
          Back
        </a>
      </div>

      <div class="row">
        <div class="container">
          <h3 class="fw-bold mb-3">> Data Detail Barang</h3>
          <div class="row">
            <div class="col-md-12">
              <div class="card shadow-sm p-4 mb-4 rounded" style="background: #003366;">
                <div class="d-flex justify-content-between align-items-start mb-3 text-light">
                  <span class="badge bg-secondary pt-3 pb-3 ps-5 pe-5 fs-6">
                    <h4 class="mb-1 fw-bold"><?= $data['Nama_Brg'] ?></h4>
                  </span>
                  <span class="badge bg-secondary fs-6"><?= $id ?></span>
                </div>

                <div class="d-flex align-items-center text-light mb-2">
                  <span class="material-symbols-rounded me-2">texture</span>
                  <span class="fw-semibold"> Bahan :
                    <?= $data['Bahan'] ?>
                  </span>
                </div>
                <div class="d-flex align-items-center text-light">
                  <span class="material-symbols-rounded me-2">sell</span>
                  <span class="fw-semibold">Kategori :
                    <?= $data['nama_kategori'] ?>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <!-- Filter Input di Luar Tabel -->
          <div class="row mb-3">
            <div class="col-md-3">
              <input type="text" id="filterKode" class="form-control" placeholder="Cari Kode Barang" onkeyup="filterTable(2, this.value)">
            </div>
            <div class="col-md-3">
              <select id="filterUkuran" class="form-select" onchange="filterTable(3, this.value)">
                <option value="">Semua Ukuran</option>
                <?php foreach ($dataUkuran as $ukuran): ?>
                  <option value="<?= $ukuran['ukuran']; ?>"><?= $ukuran['ukuran']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <button onclick="printBarcode()" class="btn btn-primary d-flex align-items-center w-100 no-print">
                <span class="material-symbols-rounded me-2">print</span>Print
              </button>
            </div>
            <div class="col-md-3">
              <button onclick="exportToPDF()" class="btn btn-danger d-flex align-items-center w-100 no-print">
                <span class="material-symbols-rounded me-2">print</span>Export PDF
              </button>
            </div>
          </div>

          <div class="table-card table-responsive">
            <table class="table table-striped table-borderless table-hover">
              <thead class="table-primary">
                <tr class="text-center">
                  <th class="no-print"><input type="checkbox" id="selectAll"></th>
                  <th>ID Produk</th>
                  <th>Kode Barang</th>
                  <th>Ukuran</th>
                  <th>Pattern</th>
                  <th>Barcode</th>
                  <th>Stock</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="myTable">
                <?php if (empty($sqlDetail)): ?>
                  <tr class="text-center">
                    <td colspan="8">Data Tidak Ada</td>
                  </tr>
                <?php endif; ?>
                <?php foreach ($sqlDetail as $barang): ?>
                  <tr class="text-center">
                    <td class="no-print"><input type="checkbox" class="barcode-check" data-barcode="<?= $barang['barcode'] ?>"></td>
                    <td><?= $barang['produk_id']; ?></td>
                    <td><?= $barang['Kode_Brg']; ?></td>
                    <td><?= $barang['ukuran']; ?></td>
                    <td><img src="data:image/jpeg;base64,<?= base64_encode($barang['pattern']); ?>" alt="Produk Busana"></td>
                    <td><?= $barang['barcode']; ?></td>
                    <td class="text-center">
                      <?php if ($barang['stock'] < 5): ?>
                        <span class="badge bg-danger"><?= $barang['stock']; ?></span>
                      <?php elseif ($barang['stock'] < 10): ?>
                        <span class="badge bg-warning"><?= $barang['stock']; ?></span>
                      <?php else: ?>
                        <span class="badge bg-success"><?= $barang['stock']; ?></span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <a href="editDetailBarang.php?Kode_Brg=<?= $barang['Kode_Brg']; ?>&&produk_id=<?= $barang['produk_id']; ?>" class="btn btn-warning btn-sm">
                        <i class="material-symbols-rounded" style="color: #fff; margin-top: 2px;">edit</i>
                      </a>
                      <a href="#" class="btn btn-danger btn-sm"
                        onclick="confirmDeleteDetailBarang('<?= $barang['Kode_Brg']; ?>', '<?= $barang['Kode_Brg']; ?>')">
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

        <div class="container mt-4">
          <h3 class="fw-bold mb-3">> Tambah Detail Barang</h3>
          <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="produk_id" value="<?= $id ?>">
            <input type="hidden" name="barcode" id="barcodeInput">
            <div class="row mb-3">
              <div class="col">
                <label for="kodeBarang" class="form-label">Kode Barang</label>
                <input type="text" class="form-control" name="kodeBarang" id="kodeBarang" required>
              </div>
              <div class="col">
                <label for="ukuran" class="form-label">Ukuran</label>
                <input type="text" class="form-control" name="ukuran" required>
              </div>
              <div class="col">
                <label for="stock" class="form-label">Stok</label>
                <input type="number" class="form-control" name="stock" required>
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

            <button class="btn btn-success d-flex align-items-center" type="submit" name="btnTambah">
              <span class="material-symbols-rounded me-2">add</span>
              Tambah
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

  <div class="modal fade" id="deleteDetailBarang" tabindex="-1" aria-labelledby="deleteDetailBarangLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteDetailBarangLabel">
            <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>
            Konfirmasi Hapus Detail Barang
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <i class="material-symbols-rounded text-danger mb-3" style="font-size: 4rem;">delete_forever</i>
            <p class="fw-bold">Yakin ingin menghapus detail barang ini?</p>
            <p id="detailBarangKodeBarang" class="text-muted"></p>
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
          <a id="confirmDeleteDetailBarang" href="#" class="btn btn-danger">
            <i class="fa-solid fa-trash me-2"></i>
            Ya, Hapus
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Tambahkan modal ini di bagian sebelum penutup </body> -->
  <div class="modal fade" id="copyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Jumlah Copy</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="copyInput" class="form-label">Masukkan jumlah copy per barcode:</label>
            <input type="number" class="form-control" id="copyInput" value="1" min="1">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" id="confirmCopy">Konfirmasi</button>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/docx/7.8.2/docx.min.js"></script>


  <!-- Add this script right after the other script tags -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

  <script>
    function confirmDeleteDetailBarang(Kode_Brg, namaBarang) {
      const modal = new bootstrap.Modal(document.getElementById('deleteDetailBarang'));
      const confirmBtn = document.getElementById('confirmDeleteDetailBarang');

      // Dapatkan produk_id dari URL atau dari data
      const urlParams = new URLSearchParams(window.location.search);
      const produk_id = urlParams.get('id');

      // Update link hapus dengan menyertakan produk_id
      confirmBtn.href = `hapusDetailBarang.php?Kode_Brg=${Kode_Brg}&produk_id=${produk_id}`;

      // Tampilkan nama barang di modal
      document.getElementById('detailBarangKodeBarang').textContent = namaBarang;

      modal.show();
    }

    async function showCopyModal() {
      return new Promise((resolve) => {
        const modal = new bootstrap.Modal(document.getElementById('copyModal'));
        const confirmBtn = document.getElementById('confirmCopy');

        // Tampilkan modal
        modal.show();

        // Handler untuk tombol konfirmasi
        const handleConfirm = () => {
          const inputValue = document.getElementById('copyInput').value;
          const copies = parseInt(inputValue) || 1;
          modal.hide();
          resolve(copies);
          confirmBtn.removeEventListener('click', handleConfirm);
        };

        confirmBtn.addEventListener('click', handleConfirm);

        // Handler untuk ketika modal ditutup
        const handleHide = () => {
          if (modal._element.classList.contains('show')) {
            resolve(0); // Return 0 jika dibatalkan
            confirmBtn.removeEventListener('click', handleConfirm);
            modal._element.removeEventListener('hidden.bs.modal', handleHide);
          }
        };

        modal._element.addEventListener('hidden.bs.modal', handleHide);
      });
    }

    // Function to export selected barcodes to PDF
    async function exportToPDF() {
      const selectedBarcodes = [];
      document.querySelectorAll('.barcode-check:checked').forEach(checkbox => {
        selectedBarcodes.push(checkbox.dataset.barcode);
      });

      if (selectedBarcodes.length === 0) {
        alert('Pilih minimal satu barcode untuk diproses!');
        return;
      }

      // Gunakan modal baru untuk input jumlah copy
      const copies = await showCopyModal();

      if (copies <= 0) return; // Jika user membatalkan

      // PDF Configuration
      const pdf = new jspdf.jsPDF({
        orientation: 'landscape',
        unit: 'mm'
      });

      // Barcode settings
      const barcodeWidth = 50; // mm
      const barcodeHeight = 25; // mm
      const margin = 10; // mm
      const maxPerRow = 4;

      let x = margin;
      let y = margin;
      let currentRow = 0;

      // Generate each barcode
      selectedBarcodes.forEach(barcode => {
        for (let i = 0; i < copies; i++) {
          // Create temporary canvas for each barcode
          const canvas = document.createElement('canvas');
          canvas.width = barcodeWidth * 5; // Convert mm to pixels (approx)
          canvas.height = barcodeHeight * 5;
          document.body.appendChild(canvas);

          // Generate barcode
          JsBarcode(canvas, barcode, {
            format: "CODE128",
            displayValue: false,
            lineColor: "#000",
            width: 2,
            height: 30,
            fontSize: 12,
            margin: 5
          });

          // Add to PDF
          const imgData = canvas.toDataURL('image/png');
          pdf.addImage(imgData, 'PNG', x, y, barcodeWidth, barcodeHeight);

          // Add barcode text below
          pdf.text(barcode, x + barcodeWidth / 2, y + barcodeHeight + 5, {
            align: 'center'
          });

          // Position for next barcode
          x += barcodeWidth + margin;
          currentRow++;

          // Move to next row if current row is full
          if (currentRow >= maxPerRow) {
            x = margin;
            y += barcodeHeight + margin + 10; // Extra space for text
            currentRow = 0;

            // Add new page if needed
            if (y > pdf.internal.pageSize.height - margin - barcodeHeight) {
              pdf.addPage();
              y = margin;
            }
          }

          // Clean up
          document.body.removeChild(canvas);
        }
      });

      pdf.save('barcode_products_fashion24.pdf');
    }

    // Modified printBarcode function to handle multiple copies
    function printBarcode(exportToWord = false) {
      const selectedBarcodes = [];
      document.querySelectorAll('.barcode-check:checked').forEach(checkbox => {
        selectedBarcodes.push(checkbox.dataset.barcode);
      });

      if (selectedBarcodes.length === 0) {
        alert('Pilih minimal satu barcode untuk diproses!');
        return;
      }

      // Ask for number of copies if only one barcode is selected
      let copies = 1;
      if (selectedBarcodes.length === 1) {
        const input = prompt('Masukkan jumlah copy yang ingin dicetak:', '1');
        if (input === null) return; // User canceled
        copies = parseInt(input) || 1;
      }

      const originalContent = document.body.innerHTML;
      let printContent = '<div style="text-align:center;padding:20px;">';
      printContent += '<h2>Daftar Barcode</h2>';
      printContent += '<div style="display:flex;flex-wrap:wrap;justify-content:center;gap:20px;">';

      selectedBarcodes.forEach(barcode => {
        for (let i = 0; i < copies; i++) {
          printContent += `
                    <div style="margin:10px;text-align:center;">
                        <svg id="print-barcode-${barcode}-${i}" width="200" height="100"></svg>
                        <div style="margin-top:5px;">${barcode}</div>
                    </div>`;
        }
      });

      printContent += '</div></div>';
      document.body.innerHTML = printContent;

      setTimeout(() => {
        selectedBarcodes.forEach(barcode => {
          for (let i = 0; i < copies; i++) {
            JsBarcode(`#print-barcode-${barcode}-${i}`, barcode, {
              format: "CODE128",
              displayValue: false,
              lineColor: "#000",
              width: 2,
              height: 50,
              fontSize: 14
            });
          }
        });

        if (exportToWord) {
          exportBarcodesToWord(selectedBarcodes, copies).then(() => {
            document.body.innerHTML = originalContent;
          });
        } else {
          window.print();
          document.body.innerHTML = originalContent;
        }
      }, 100);
    }

    // Modified export to Word function to handle copies
    async function exportBarcodesToWord(barcodes, copies = 1) {
      const tempCanvas = document.createElement('canvas');
      tempCanvas.width = 200;
      tempCanvas.height = 100;
      document.body.appendChild(tempCanvas);

      const {
        Document,
        Paragraph,
        Packer,
        ImageRun,
        TextRun,
        HeadingLevel
      } = docx;

      const children = [
        new Paragraph({
          text: "Daftar Barcode",
          heading: HeadingLevel.HEADING_1,
          alignment: docx.AlignmentType.CENTER
        })
      ];

      for (const barcode of barcodes) {
        for (let i = 0; i < copies; i++) {
          JsBarcode(tempCanvas, barcode, {
            format: "CODE128",
            displayValue: false,
            lineColor: "#000",
            width: 2,
            height: 50
          });

          const dataUrl = tempCanvas.toDataURL('image/png');
          const base64Data = dataUrl.split(',')[1];

          children.push(
            new Paragraph({
              children: [
                new ImageRun({
                  data: base64Data,
                  transformation: {
                    width: 200,
                    height: 100
                  }
                })
              ],
              alignment: docx.AlignmentType.CENTER
            }),
            new Paragraph({
              children: [
                new TextRun({
                  text: barcode,
                  bold: true
                })
              ],
              alignment: docx.AlignmentType.CENTER
            }),
            new Paragraph({
              text: ""
            })
          );
        }
      }

      document.body.removeChild(tempCanvas);

      const doc = new Document({
        sections: [{
          properties: {},
          children: children
        }]
      });

      const blob = await Packer.toBlob(doc);
      const link = document.createElement('a');
      link.href = URL.createObjectURL(blob);
      link.download = 'barcodes.docx';
      link.click();
    }
  </script>

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
    function printBarcode(exportToWord = false) {
      // Dapatkan barcode yang dipilih
      const selectedBarcodes = [];
      document.querySelectorAll('.barcode-check:checked').forEach(checkbox => {
        selectedBarcodes.push(checkbox.dataset.barcode);
      });

      if (selectedBarcodes.length === 0) {
        alert('Pilih minimal satu barcode untuk diproses!');
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

        if (exportToWord) {
          // Export ke Word
          exportBarcodesToWord(selectedBarcodes).then(() => {
            document.body.innerHTML = originalContent;
          });
        } else {
          // Cetak seperti biasa
          window.print();
          document.body.innerHTML = originalContent;
        }
      }, 100);
    }

    // Fungsi baru untuk export ke Word
    async function exportBarcodesToWord(barcodes) {
      // Buat canvas sementara
      const tempCanvas = document.createElement('canvas');
      tempCanvas.width = 200;
      tempCanvas.height = 100;
      document.body.appendChild(tempCanvas);

      const {
        Document,
        Paragraph,
        Packer,
        ImageRun,
        TextRun,
        HeadingLevel
      } = docx;

      const children = [
        new Paragraph({
          text: "Daftar Barcode",
          heading: HeadingLevel.HEADING_1,
          alignment: docx.AlignmentType.CENTER
        })
      ];

      for (const barcode of barcodes) {
        // Render barcode ke canvas
        JsBarcode(tempCanvas, barcode, {
          format: "CODE128",
          displayValue: false,
          lineColor: "#000",
          width: 2,
          height: 50
        });

        // Konversi ke base64
        const dataUrl = tempCanvas.toDataURL('image/png');
        const base64Data = dataUrl.split(',')[1];

        children.push(
          new Paragraph({
            children: [
              new ImageRun({
                data: base64Data,
                transformation: {
                  width: 200,
                  height: 100
                }
              })
            ],
            alignment: docx.AlignmentType.CENTER
          }),
          new Paragraph({
            children: [
              new TextRun({
                text: barcode,
                bold: true
              })
            ],
            alignment: docx.AlignmentType.CENTER
          }),
          new Paragraph({
            text: ""
          })
        );
      }

      // Hapus canvas sementara
      document.body.removeChild(tempCanvas);

      // Buat dan download dokumen Word
      const doc = new Document({
        sections: [{
          properties: {},
          children: children
        }]
      });

      const blob = await Packer.toBlob(doc);
      const link = document.createElement('a');
      link.href = URL.createObjectURL(blob);
      link.download = 'barcodes.docx';
      link.click();
    }

    // Fungsi untuk select all (tetap sama)
    document.getElementById('selectAll').addEventListener('change', function() {
      document.querySelectorAll('.barcode-check').forEach(checkbox => {
        checkbox.checked = this.checked;
      });
    });

    // Fungsi untuk generate barcode preview (tetap sama)
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

  <script>
    // Pagination variables
    let currentPage = 1;
    const rowsPerPage = 10;
    let filteredRows = [];

    // Initialize pagination
    function initializePagination() {
      const table = document.getElementById('myTable');
      const rows = table.getElementsByTagName('tr');

      // Store all rows initially (excluding header row if any)
      filteredRows = Array.from(rows).filter(row => row.getElementsByTagName('td').length > 0);

      displayPage(currentPage);
    }

    // Display specific page
    function displayPage(page) {
      const table = document.getElementById('myTable');
      const allRows = table.getElementsByTagName('tr');

      // Hide all rows first (excluding header row if any)
      for (let i = 0; i < allRows.length; i++) {
        if (allRows[i].getElementsByTagName('td').length > 0) {
          allRows[i].style.display = 'none';
        }
      }

      // Calculate start and end index
      const startIndex = (page - 1) * rowsPerPage;
      const endIndex = startIndex + rowsPerPage;

      // Show rows for current page
      for (let i = startIndex; i < endIndex && i < filteredRows.length; i++) {
        filteredRows[i].style.display = '';
      }

      // Update page info
      updatePageInfo();
    }

    // Update page information
    function updatePageInfo() {
      const totalRows = filteredRows.length;
      const totalPages = Math.ceil(totalRows / rowsPerPage);
      const pageInfoElement = document.getElementById('pageInfo');

      if (pageInfoElement) {
        if (totalRows === 0) {
          pageInfoElement.textContent = 'Tidak ada data';
        } else {
          const startItem = (currentPage - 1) * rowsPerPage + 1;
          const endItem = Math.min(currentPage * rowsPerPage, totalRows);
          pageInfoElement.textContent = `Halaman ${currentPage} dari ${totalPages} (${startItem}-${endItem} dari ${totalRows} item)`;
        }
      }

      // Disable/enable navigation buttons
      const prevBtn = document.querySelector('button[onclick="prevPage()"]');
      const nextBtn = document.querySelector('button[onclick="nextPage()"]');

      if (prevBtn) {
        prevBtn.disabled = currentPage <= 1;
      }
      if (nextBtn) {
        nextBtn.disabled = currentPage >= Math.ceil(totalRows / rowsPerPage);
      }
    }

    // Previous page function
    function prevPage() {
      if (currentPage > 1) {
        currentPage--;
        displayPage(currentPage);
      }
    }

    // Next page function
    function nextPage() {
      const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
      if (currentPage < totalPages) {
        currentPage++;
        displayPage(currentPage);
      }
    }

    // Filter table function (assuming you have one)
    function filterTable(columnIndex, filterValue) {
      const table = document.getElementById('myTable');
      const rows = table.getElementsByTagName('tr');

      filteredRows = Array.from(rows).filter(row => {
        const cells = row.getElementsByTagName('td');
        if (cells.length === 0) return false; // Skip header row if any

        const cellValue = cells[columnIndex].textContent || cells[columnIndex].innerText;
        return cellValue.toUpperCase().includes(filterValue.toUpperCase());
      });

      currentPage = 1;
      displayPage(currentPage);
    }

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
      initializePagination();

      // Add event listeners to filter inputs
      document.getElementById('filterKode').addEventListener('input', function() {
        currentPage = 1;
        filterTable(2, this.value);
      });

      document.getElementById('filterUkuran').addEventListener('change', function() {
        currentPage = 1;
        filterTable(3, this.value);
      });
    });
  </script>


</body>

</html>