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

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="index.js"></script>
  <script src="../js/sidebar.js"></script>

  <script>
    // Variabel global untuk paginasi
    let currentPage = 1;
    const rowsPerPage = 10;

    // Fungsi untuk konfirmasi hapus barang
    function confirmDeleteBarang(id, namaBarang) {
      document.getElementById('barangName').textContent = namaBarang;
      document.getElementById('confirmDeleteBarangBtn').href = 'hapusBarang.php?id=' + id;
      new bootstrap.Modal(document.getElementById('deleteBarangModal')).show();
    }

    // Fungsi untuk konfirmasi hapus kategori
    function confirmDeleteKategori(idKategori, namaKategori) {
      document.getElementById('kategoriName').textContent = namaKategori;
      document.getElementById('confirmDeleteKategoriBtn').href = 'hapusKategori.php?id_kategori=' + idKategori;
      new bootstrap.Modal(document.getElementById('deleteKategoriModal')).show();
    }

    // Fungsi untuk memfilter tabel
    function filterTable(columnIndex, value) {
      const table = document.querySelector('.table-responsive table');
      const rows = table.querySelectorAll('tbody tr');

      rows.forEach(row => {
        const cell = row.cells[columnIndex]; // Fixed: use columnIndex directly
        const cellText = cell.textContent.toLowerCase();
        const searchText = value.toLowerCase();

        if (cellText.includes(searchText)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });

      currentPage = 1; // Reset ke halaman pertama setelah filter
      updatePagination();
    }

    // Fungsi untuk paginasi
    function updatePagination() {
      const table = document.querySelector('.table-responsive table');
      const rows = Array.from(table.querySelectorAll('tbody tr:not([style*="display: none"])'));
      const totalPages = Math.ceil(rows.length / rowsPerPage);

      document.getElementById('pageInfo').textContent = `Halaman ${currentPage} dari ${totalPages}`;

      // Sembunyikan semua baris
      rows.forEach(row => row.style.display = 'none');

      // Tampilkan baris untuk halaman saat ini
      const start = (currentPage - 1) * rowsPerPage;
      const end = start + rowsPerPage;

      rows.slice(start, end).forEach(row => row.style.display = '');

      // Nonaktifkan tombol jika diperlukan
      document.querySelector('.btn-primary.btn-sm').disabled = currentPage === 1;
      document.querySelector('.btn-success.btn-sm').disabled = currentPage === totalPages || totalPages === 0;
    }

    function prevPage() {
      if (currentPage > 1) {
        currentPage--;
        updatePagination();
      }
    }

    function nextPage() {
      const table = document.querySelector('.table-responsive table');
      const visibleRows = Array.from(table.querySelectorAll('tbody tr:not([style*="display: none"])')).length;

      if (currentPage * rowsPerPage < visibleRows) {
        currentPage++;
        updatePagination();
      }
    }

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
      updatePagination();

      // Event listener untuk filter
      document.getElementById('filterKode').addEventListener('input', function() {
        filterTable(0, this.value); // Kolom ID (index 0)
      });

      document.getElementById('filterNama').addEventListener('input', function() {
        filterTable(2, this.value); // Kolom Nama Barang (index 2)
      });

      document.getElementById('filterKategori').addEventListener('change', function() {
        filterTable(5, this.value); // Kolom Kategori (index 5)
      });
    });
  </script>

</body>

</html>