<?php
session_start();
include '../Model/crudBarang.php';
include '../Model/crudKategori.php';

if (!isset($_SESSION['username'])) {
  header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
  exit();
}

$data = getAllBarang();
$allBarangDetail = getAllBarangDanDetail();
$dataKategori = getAllKategori();
$username = $_SESSION['username'];
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
      </div>

      <div class="row">
        <div class="container">
          <h3 class="fw-bold mb-3">> Data Barang</h3>
          <a class="btn btn-success d-flex align-items-center mb-4" href="addBarang.php" style="width: 200px;">
            <span class="material-symbols-rounded me-2">add</span>
            Tambah Barang
          </a>
          <!-- Filter Input di Luar Tabel -->
          <div class="row mb-3">
            <div class="col-md-3">
              <input type="text" id="filterKode" class="form-control" placeholder="Cari Kode Barang" onkeyup="filterTable(0, this.value)">
            </div>
            <div class="col-md-3">
              <input type="text" id="filterNama" class="form-control" placeholder="Cari Nama Barang" onkeyup="filterTable(2, this.value)">
            </div>
            <div class="col-md-3">
              <select id="filterKategori" class="form-select" onchange="filterTable(5, this.value)">
                <option value="">Semua Kategori</option>
                <?php foreach ($dataKategori as $kategori): ?>
                  <option value="<?= $kategori['nama_kategori']; ?>"><?= $kategori['nama_kategori']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="table-card table-responsive">
            <table class="table table-striped table-borderless table-hover">
              <thead class="table-primary">
                <tr class="text-center">
                  <th>ID</th>
                  <th>Gambar</th>
                  <th>Nama Barang</th>
                  <th>Bahan</th>
                  <th>Harga</th>
                  <th>Kategori</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="myTable">
                <?php if (empty($data)) : ?>
                  <tr class="text-center">
                    <td colspan="7">Data Tidak Ada</td>
                  </tr>
                <?php endif; ?>
                <?php $no = 1;
                foreach ($data as $barang): ?>
                  <tr class="text-center">
                    <td><?= $barang['id']; ?></td>
                    <td>
                      <img src="data:image/jpeg;base64,<?= base64_encode($barang['Gambar']); ?>" alt="Image Product">
                    </td>
                    <td><?= $barang['Nama_Brg']; ?></td>
                    <td><?= $barang['Bahan']; ?></td>
                    <td>Rp <?= number_format($barang['harga_jual'], 0, ',', '.') ?></td>
                    <td><?= $barang['nama_kategori']; ?></td>
                    <td class="text-center">
                      <a href="editBarang.php?id=<?= $barang['id']; ?>" class="btn btn-warning btn-sm">
                        <i class="material-symbols-rounded" style="color: #fff; margin-top: 2px;">edit</i>
                      </a>
                      <a href="#" class="btn btn-danger btn-sm"
                        onclick="confirmDeleteBarang(<?= $barang['id']; ?>, '<?= addslashes($barang['Nama_Brg']); ?>')">
                        <i class="material-symbols-rounded" style="margin-top: 2px;">delete</i>
                      </a>
                      <a href="detailBarang.php?id=<?= $barang['id']; ?>" class="btn btn-info btn-sm">
                        <i class="material-symbols-rounded" style="color: #fff; margin-top: 2px;">info</i>
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
          <h3 class="fw-bold mb-3 mt-3">> Kategori</h3>
          <a class="btn btn-success d-flex align-items-center mb-4" href="addKategori.php" style="width: 200px;">
            <span class="material-symbols-rounded me-2">add</span>
            Tambah Kategori
          </a>
          <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
              <thead class="table-primary">
                <tr>
                  <th>#</th>
                  <th>Nama Kategori</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody id="myTable">
                <?php if (empty($dataKategori)) : ?>
                  <tr class="text-center">
                    <td colspan="4">Data Tidak Ada</td>
                  </tr>
                <?php endif; ?>
                <?php $no = 1;
                foreach ($dataKategori as $kategori): ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $kategori['nama_kategori']; ?></td>
                    <td class="text-center">
                      <a href="editKategori.php?id_kategori=<?= $kategori['id_kategori']; ?>" class="btn btn-warning btn-sm">
                        <i class="material-symbols-rounded" style="color: #fff; margin-top: 2px;">edit</i>
                      </a>
                      <a href="#" class="btn btn-danger btn-sm"
                        onclick="confirmDeleteKategori(<?= $kategori['id_kategori']; ?>, '<?= addslashes($kategori['nama_kategori']); ?>')">
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
  </main>

  <!-- Delete Confirmation Modal for Barang -->
  <div class="modal fade" id="deleteBarangModal" tabindex="-1" aria-labelledby="deleteBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteBarangModalLabel">
            <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>
            Konfirmasi Hapus Barang
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <i class="material-symbols-rounded text-danger mb-3" style="font-size: 4rem;">delete_forever</i>
            <p class="fw-bold">Yakin ingin menghapus barang ini?</p>
            <p id="barangName" class="text-muted"></p>
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
          <a id="confirmDeleteBarangBtn" href="#" class="btn btn-danger">
            <i class="fa-solid fa-trash me-2"></i>
            Ya, Hapus
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal for Kategori -->
  <div class="modal fade" id="deleteKategoriModal" tabindex="-1" aria-labelledby="deleteKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteKategoriModalLabel">
            <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>
            Konfirmasi Hapus Kategori
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <i class="material-symbols-rounded text-danger mb-3" style="font-size: 4rem;">delete_forever</i>
            <p class="fw-bold">Yakin ingin menghapus kategori ini?</p>
            <p id="kategoriName" class="text-muted"></p>
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
          <a id="confirmDeleteKategoriBtn" href="#" class="btn btn-danger">
            <i class="fa-solid fa-trash me-2"></i>
            Ya, Hapus
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal untuk Pesan -->
  <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-primary" id="messageModalLabel">
            <i class="fa-solid fa-circle-info me-2"></i>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <i class="material-symbols-rounded text-primary mb-3" style="font-size: 4rem;">info</i>
            <div id="messageModalBody" class="fw-bold"></div>
            <p class="text-muted small mt-3">
              <i class="fa-solid fa-circle-exclamation me-2"></i>
              Tekan tombol Tutup untuk melanjutkan
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
            <i class="fa-solid fa-check me-2"></i>
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="index.js"></script>
  <script src="../js/sidebar.js"></script>

  <script>
    // Ventra POS Barang - JavaScript Functions

    // Global variables for pagination
    let currentPage = 1;
    let rowsPerPage = 10;
    let filteredRows = [];

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
      updateDateTime();
      setInterval(updateDateTime, 1000);
      initializePagination();
    });

    // Update date and time display
    function updateDateTime() {
      const now = new Date();
      const timeOptions = {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
      };
      const dateOptions = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      };

      const clockElement = document.getElementById('clock');
      const dateElement = document.getElementById('date');

      if (clockElement) {
        clockElement.textContent = now.toLocaleTimeString('id-ID', timeOptions);
      }
      if (dateElement) {
        dateElement.textContent = now.toLocaleDateString('id-ID', dateOptions);
      }
    }

    // Filter table function
    function filterTable(columnIndex, filterValue) {
      const table = document.getElementById('myTable');
      const rows = table.getElementsByTagName('tr');

      // Reset to first page when filtering
      currentPage = 1;

      // Clear filtered rows array
      filteredRows = [];

      for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');

        if (cells.length > 0) {
          let cellText = '';

          // Get text from specific column
          if (cells[columnIndex]) {
            cellText = cells[columnIndex].textContent || cells[columnIndex].innerText;
          }

          // Check if filter value matches
          if (filterValue === '' || cellText.toLowerCase().includes(filterValue.toLowerCase())) {
            filteredRows.push(rows[i]);
          }
        }
      }

      // Apply pagination to filtered results
      displayPage(currentPage);
    }

    // Initialize pagination
    function initializePagination() {
      const table = document.getElementById('myTable');
      const rows = table.getElementsByTagName('tr');

      // Store all rows initially
      filteredRows = Array.from(rows).filter(row => row.getElementsByTagName('td').length > 0);

      displayPage(currentPage);
    }

    // Display specific page
    function displayPage(page) {
      const table = document.getElementById('myTable');
      const allRows = table.getElementsByTagName('tr');

      // Hide all rows first
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

    // Confirm delete barang
    function confirmDeleteBarang(id, namaBarang) {
      const modal = new bootstrap.Modal(document.getElementById('deleteBarangModal'));
      const barangNameElement = document.getElementById('barangName');

      if (barangNameElement) {
        barangNameElement.textContent = `"${namaBarang}"`;
      }

      // Set onclick handler untuk tombol konfirmasi
      const confirmBtn = document.getElementById('confirmDeleteBarangBtn');
      if (confirmBtn) {
        confirmBtn.onclick = function() {
          checkAndDeleteBarang(id, namaBarang);
        };
      }

      modal.show();
    }

    function checkAndDeleteBarang(id, namaBarang) {
      // Kirim request AJAX untuk mengecek dan menghapus
      fetch(`hapusBarang.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
          const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
          const messageBody = document.getElementById('messageModalBody');
          const messageTitle = document.querySelector('#messageModalLabel');

          if (data.success) {
            messageTitle.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i> Berhasil';
            messageBody.innerHTML = `Barang <span class="text-primary">${namaBarang}</span> berhasil dihapus.`;

            // Tutup modal delete
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteBarangModal'));
            deleteModal.hide();

            // Tampilkan modal pesan
            messageModal.show();

            // Reload halaman setelah 2 detik
            setTimeout(() => {
              window.location.reload();
            }, 2000);
          } else {
            messageTitle.innerHTML = '<i class="fa-solid fa-circle-exclamation me-2"></i> Gagal';
            messageBody.innerHTML = `Barang <span class="text-primary">${namaBarang}</span> tidak bisa dihapus karena: <br><span class="text-danger">${data.message}</span>`;

            // Tutup modal delete
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteBarangModal'));
            deleteModal.hide();

            // Tampilkan modal pesan
            messageModal.show();
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }

    // Confirm delete kategori
    function confirmDeleteKategori(idKategori, namaKategori) {
      const modal = new bootstrap.Modal(document.getElementById('deleteKategoriModal'));
      const kategoriNameElement = document.getElementById('kategoriName');
      const confirmBtn = document.getElementById('confirmDeleteKategoriBtn');

      if (kategoriNameElement) {
        kategoriNameElement.textContent = `"${namaKategori}"`;
      }

      if (confirmBtn) {
        confirmBtn.href = `hapusKategori.php?id_kategori=${idKategori}`;
      }

      modal.show();
    }

    // Clear all filters
    function clearAllFilters() {
      document.getElementById('filterKode').value = '';
      document.getElementById('filterNama').value = '';
      document.getElementById('filterKategori').value = '';

      // Reset to show all data
      initializePagination();
    }

    // Search function for real-time filtering
    function searchTable() {
      const searchInput = document.getElementById('globalSearch');
      if (!searchInput) return;

      const searchValue = searchInput.value.toLowerCase();
      const table = document.getElementById('myTable');
      const rows = table.getElementsByTagName('tr');

      filteredRows = [];

      for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;

        if (cells.length > 0) {
          for (let j = 0; j < cells.length; j++) {
            const cellText = cells[j].textContent || cells[j].innerText;
            if (cellText.toLowerCase().includes(searchValue)) {
              found = true;
              break;
            }
          }

          if (found) {
            filteredRows.push(rows[i]);
          }
        }
      }

      currentPage = 1;
      displayPage(currentPage);
    }

    // Show loading indicator
    function showLoading() {
      const loadingHtml = `
        <div class="d-flex justify-content-center align-items-center p-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span class="ms-2">Memuat data...</span>
        </div>
    `;

      const tableBody = document.getElementById('myTable');
      if (tableBody) {
        tableBody.innerHTML = loadingHtml;
      }
    }

    // Show success message
    function showSuccessMessage(message) {
      const alertHtml = `
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="material-symbols-rounded me-2">check_circle</i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

      const container = document.querySelector('.container-fluid');
      if (container) {
        container.insertAdjacentHTML('afterbegin', alertHtml);

        // Auto dismiss after 5 seconds
        setTimeout(() => {
          const alert = container.querySelector('.alert');
          if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
          }
        }, 5000);
      }
    }

    // Show error message
    function showErrorMessage(message) {
      const alertHtml = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="material-symbols-rounded me-2">error</i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

      const container = document.querySelector('.container-fluid');
      if (container) {
        container.insertAdjacentHTML('afterbegin', alertHtml);

        // Auto dismiss after 5 seconds
        setTimeout(() => {
          const alert = container.querySelector('.alert');
          if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
          }
        }, 5000);
      }
    }

    // Validate image file
    function validateImageFile(input) {
      const file = input.files[0];
      const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
      const maxSize = 5 * 1024 * 1024; // 5MB

      if (file) {
        if (!allowedTypes.includes(file.type)) {
          showErrorMessage('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
          input.value = '';
          return false;
        }

        if (file.size > maxSize) {
          showErrorMessage('Ukuran file terlalu besar. Maksimal 5MB.');
          input.value = '';
          return false;
        }

        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
          const preview = document.getElementById('imagePreview');
          if (preview) {
            preview.src = e.target.result;
            preview.style.display = 'block';
          }
        };
        reader.readAsDataURL(file);
      }

      return true;
    }

    // Format currency input
    function formatCurrency(input) {
      let value = input.value.replace(/[^\d]/g, '');

      if (value) {
        value = parseInt(value).toLocaleString('id-ID');
        input.value = 'Rp ' + value;
      }
    }

    // Remove currency formatting for form submission
    function removeCurrencyFormat(input) {
      let value = input.value.replace(/[^\d]/g, '');
      input.value = value;
    }

    // Print table
    function printTable() {
      const printWindow = window.open('', '_blank');
      const tableContent = document.querySelector('.table-card').innerHTML;

      printWindow.document.write(`
        <html>
            <head>
                <title>Data Barang - Ventra POS</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
                <style>
                    body { font-family: Arial, sans-serif; }
                    .table img { max-width: 50px; height: auto; }
                    @media print {
                        .btn { display: none; }
                        .pagination { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h2 class="text-center mb-4">Data Barang - Ventra POS</h2>
                    <p class="text-center text-muted mb-4">Dicetak pada: ${new Date().toLocaleDateString('id-ID')}</p>
                    ${tableContent}
                </div>
            </body>
        </html>
    `);

      printWindow.document.close();
      printWindow.print();
    }

    // Export to CSV
    function exportToCSV() {
      const table = document.querySelector('.table');
      const rows = table.querySelectorAll('tr');
      let csv = [];

      for (let i = 0; i < rows.length; i++) {
        const row = [];
        const cols = rows[i].querySelectorAll('td, th');

        for (let j = 0; j < cols.length - 1; j++) { // Exclude action column
          if (j === 1) { // Skip image column
            row.push('');
          } else {
            let cellText = cols[j].innerText;
            row.push('"' + cellText.replace(/"/g, '""') + '"');
          }
        }
        csv.push(row.join(','));
      }

      const csvContent = csv.join('\n');
      const blob = new Blob([csvContent], {
        type: 'text/csv;charset=utf-8;'
      });
      const link = document.createElement('a');

      if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `data_barang_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      }
    }
  </script>

  <script>
    // Fungsi untuk menampilkan modal pesan
    function showMessageModal(title, message) {
      const modal = new bootstrap.Modal(document.getElementById('messageModal'));
      const modalHeader = document.querySelector('#messageModal .modal-header');

      document.getElementById('messageModalLabel').textContent = title;
      document.getElementById('messageModalBody').textContent = message;

      // Ubah warna header berdasarkan status
      if (title === 'Sukses') {
        modalHeader.classList.remove('bg-danger');
        modalHeader.classList.add('bg-success');
      } else {
        modalHeader.classList.remove('bg-success');
        modalHeader.classList.add('bg-danger');
      }

      modal.show();
    }

    // Cek parameter URL saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      const status = urlParams.get('status');
      const message = urlParams.get('message');

      if (status && message) {
        const title = status === 'success' ? 'Sukses' : 'Error';
        showMessageModal(title, decodeURIComponent(message));

        // Hapus parameter dari URL tanpa reload
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
      }
    });
  </script>
</body>

</html>