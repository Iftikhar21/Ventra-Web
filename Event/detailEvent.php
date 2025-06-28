<?php
session_start();
include '../Model/crudEvent.php';
include '../Model/crudBarang.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
    exit();
}

$idEvent = isset($_GET['id_event']) ? $_GET['id_event'] : '';

$dataEvent = getEvent($idEvent);
$dataBarang = getBarangByEvent($idEvent);
$availableProducts = getAllBarang();

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

    <button class="toggle-btn">
        <span class="material-symbols-rounded">menu</span>
    </button>

    <!-- Main Content -->
    <main class="main-content position-relative border-radius-lg ps-5 pt-3">
        <div class="container-fluid">
            <div class="mb-4">
                <nav class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-dark fw-bold m-0">Detail Event</h2>
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
                <p class="text-muted">Lihat Data Detail Event</p>
                <a class="btn btn-info d-flex align-items-center" href="event.php" style="width: 100px;">
                    <span class="material-symbols-rounded me-2">chevron_left</span>
                    Back
                </a>
            </div>

            <div class="row">
                <div class="container">
                    <h3 class="fw-bold mb-3">> Daftar Produk Event</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-sm p-4 mb-4 rounded" style="background: #003366;">
                                <div class="d-flex justify-content-between align-items-start mb-3 text-light">
                                    <h4 class="mb-1 fw-bold"><?= $dataEvent['nama_event'] ?></h4>
                                    <span class="badge bg-danger fs-6"><?= $dataEvent['total_diskon'] ?>% OFF</span>
                                </div>
    
                                <div class="d-flex align-items-center text-light">
                                    <span class="material-symbols-rounded me-2">calendar_today</span>
                                    <span class="fw-semibold">
                                        <?= date('d M Y', strtotime($dataEvent['waktu_aktif'])) ?> -
                                        <?= date('d M Y', strtotime($dataEvent['waktu_non_aktif'])) ?>
                                    </span>
                                </div>
    
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="editEvent.php?id_event=<?= $dataEvent['id_event']; ?>" class="btn btn-warning btn-sm d-flex align-items-center text-light">
                                        <i class="fa-solid fa-pen p-1"></i>
                                    </a>
                                    <a href="hapusEvent.php?id_event=<?= $dataEvent['id_event']; ?>" class="btn btn-danger btn-sm d-flex align-items-center">
                                        <i class="fa-solid fa-trash p-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Filter Input di Luar Tabel -->
                    <div class="row mb-3 justify-content-between">
                        <div class="col-md-3">
                            <input type="text" id="filterNama" class="form-control" placeholder="Cari Nama Barang" onkeyup="filterTable(2, this.value)">
                        </div>
                        <div class="col-md-3 text-end">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                <i class="fas fa-plus me-2"></i>Tambah Produk
                            </button>
                        </div>
                    </div>
                    <?php if (!empty($dataBarang)) : ?>
                        <div class="row g-4">
                            <?php foreach ($dataBarang as $barang) : ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card shadow-sm border-0 h-100 product-card">
                                        <div class="position-relative overflow-hidden rounded-top d-flex justify-content-center align-items-center p-2">
                                            <img src="data:image/jpeg;base64,<?= base64_encode($barang['gambar']); ?>"
                                                class="card-img-top product-image"
                                                alt="<?= htmlspecialchars($barang['nama_barang']); ?>"
                                                style="height: 200px; width: 200px; object-fit: cover; transition: transform 0.3s ease;">
    
                                            <!-- Discount Badge -->
                                            <?php if ($dataEvent['total_diskon'] > 0) : ?>
                                                <span class="badge badge-card bg-danger position-absolute top-0 end-0 m-2 px-2 py-1">
                                                    -<?= $dataEvent['total_diskon']; ?>%
                                                </span>
                                            <?php endif; ?>
                                        </div>
    
                                        <div class="card-body d-flex flex-column p-4">
                                            <h6 class="card-title fw-bold mb-2 text-truncate" title="<?= htmlspecialchars($barang['nama_barang']); ?>">
                                                <?= htmlspecialchars($barang['nama_barang']); ?>
                                            </h6>
    
                                            <small class="text-muted mb-3">
                                                <i class="fas fa-tag me-1"></i><?= htmlspecialchars($barang['kategori']); ?>
                                            </small>
    
                                            <div class="price-section mt-auto">
                                                <?php
                                                $originalPrice = $barang['harga'];
                                                $discountedPrice = $originalPrice * (1 - $dataEvent['total_diskon'] / 100);
                                                ?>
    
                                                <?php if ($dataEvent['total_diskon'] > 0) : ?>
                                                    <div class="d-flex align-items-center gap-2 mb-3">
                                                        <span class="text-decoration-line-through text-muted small">
                                                            Rp <?= number_format($originalPrice, 0, ',', '.'); ?>
                                                        </span>
                                                    </div>
                                                    <div class="h6 text-primary fw-bold mb-0">
                                                        Rp <?= number_format($discountedPrice, 0, ',', '.'); ?>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="h6 text-dark fw-bold mb-0">
                                                        Rp <?= number_format($originalPrice, 0, ',', '.'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
    
                                        <div class="card-footer bg-transparent border-0 p-4 pt-0">
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm w-100 delete-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal"
                                                data-id="<?= $barang['id_barang']; ?>"
                                                data-name="<?= htmlspecialchars($barang['nama_barang']); ?>">
                                                <i class="fas fa-trash-alt me-2"></i>Hapus dari Event
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
    
                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header border-0 pb-0">
                                        <h6 class="modal-title text-danger" id="deleteModalLabel">
                                            <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                                        </h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body pt-0">
                                        <p class="mb-1">Apakah Anda yakin ingin menghapus barang:</p>
                                        <strong id="productNameToDelete" class="text-primary"></strong>
                                        <p class="text-muted small mt-2 mb-0">Barang akan dihapus dari event ini.</p>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                        <a href="#" id="confirmDeleteBtn" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt me-1"></i>Hapus
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                    <?php else : ?>
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-box-open text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                            <h6 class="text-muted mb-2">Belum Ada Barang</h6>
                            <p class="text-muted small mb-0">Tidak ada barang yang ditambahkan ke event ini</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Tambah Produk ke Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="availableProductTable">
                            <thead>
                                <tr>
                                    <th scope="col">Pilih</th>
                                    <th scope="col">Kode Barang</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($availableProducts)) : ?>
                                    <?php foreach ($availableProducts as $product) : ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input product-checkbox" value="<?= $product['id']; ?>">
                                            </td>
                                            <td><?= $product['id']; ?></td>
                                            <td><?= $product['Nama_Brg']; ?></td>
                                            <td>Rp <?= number_format($product['harga_jual'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada produk yang tersedia untuk ditambahkan</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="addProductsBtn">Tambahkan Produk Terpilih</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="index.js"></script>
    <script src="../js/sidebar.js"></script>

    <script>
        // Filter table function
        function filterTable(columnIndex, value) {
            const table = document.getElementById("productTable");
            const rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                const cell = rows[i].getElementsByTagName("td")[columnIndex - 1];
                if (cell) {
                    const cellValue = cell.textContent || cell.innerText;
                    if (cellValue.toUpperCase().indexOf(value.toUpperCase()) > -1) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            }
        }

        // Add products to event
        document.getElementById('addProductsBtn').addEventListener('click', function() {
            const selectedProducts = [];
            document.querySelectorAll('.product-checkbox:checked').forEach(checkbox => {
                selectedProducts.push(checkbox.value);
            });

            if (selectedProducts.length === 0) {
                alert('Pilih setidaknya satu produk untuk ditambahkan');
                return;
            }

            // Send AJAX request to add products
            fetch('../Model/addProductsToEvent.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id_event=<?= $idEvent ?>&products=${JSON.stringify(selectedProducts)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Produk berhasil ditambahkan ke event');
                        location.reload();
                    } else {
                        alert('Gagal menambahkan produk: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambahkan produk');
                });
        });
    </script>

</body>

</html>