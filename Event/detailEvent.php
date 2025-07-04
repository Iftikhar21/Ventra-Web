<?php
session_start();
include '../Model/crudEvent.php';
include '../Model/crudBarang.php';
include '../Model/crudKategori.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../Login/formLogin.php"); // Redirect kalau belum login
    exit();
}

$idEvent = isset($_GET['id_event']) ? $_GET['id_event'] : '';

$dataEvent = getEvent($idEvent);
$dataBarang = getBarangByEvent($idEvent);
$availableProducts = getAvailableProductsForEvent($idEvent);
$dataKategori = getAllKategori();


$username = $_SESSION['username'];

if (isset($_POST['id_event']) && isset($_POST['product_ids'])) {
    $idEvent = $_POST['id_event'];
    $productIds = $_POST['product_ids'];

    if (addProductsToEvent($idEvent, $productIds)) {
        $_SESSION['success_message'] = 'Produk berhasil ditambahkan ke event';
    } else {
        $_SESSION['error_message'] = 'Gagal menambahkan produk ke event';
    }

    header("Location: detailEvent.php?id_event=$idEvent");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fashion 24 - Detail Event</title>
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
                    <h2 class="text-dark fw-bold m-0">Detail Event</h2>
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

                                <div class="d-flex justify-content-between gap-2 mt-4">
                                    <div class="col">
                                        <span class="badge bg-secondary pt-3 pb-3 ps-5 pe-5 fs-6">
                                            <?= $dataEvent['total_barang'] ?> Produk
                                        </span>
                                    </div>
                                    <div class="col d-flex justify-content-end align-items-center">
                                        <div class="d-flex justify-content-end gap-2 text-end">
                                            <a href="editEvent.php?id_event=<?= $dataEvent['id_event']; ?>" class="btn btn-warning btn-sm d-flex align-items-center text-light">
                                                <i class="fa-solid fa-pen p-1"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm"
                                                onclick="confirmDeleteEvent(<?= $dataEvent['id_event']; ?>, '<?= addslashes($dataEvent['nama_event']); ?>')">
                                                <i class="material-symbols-rounded" style="margin-top: 2px;">delete</i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Filter Input di Luar Tabel -->
                    <div class="row mb-3 justify-content-between">
                        <div class="col text-end">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                <i class="fas fa-plus me-2"></i>Tambah Produk
                            </button>
                        </div>
                    </div>
                    <?php if (!empty($dataBarang)) : ?>
                        <div class="row g-4">
                            <?php foreach ($dataBarang as $barang) : ?>
                                <div class="col-lg-3">
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
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Tambah Produk ke Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="searchProduct" placeholder="Cari nama barang...">
                        </div>
                        <div class="col-md-4">
                            <select id="filterKategori" class="form-select" onchange="filterTable(5, this.value)">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($dataKategori as $kategori): ?>
                                    <option value="<?= $kategori['nama_kategori']; ?>"><?= $kategori['nama_kategori']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Product List -->
                    <div class="product-list-container" style="max-height: 400px; overflow-y: auto;">
                        <?php if (!empty($availableProducts)) : ?>
                            <?php foreach ($availableProducts as $product) : ?>
                                <div class="product-item border rounded p-3 mb-2 cursor-pointer"
                                    data-category="<?= $product['nama_kategori']; ?>"
                                    data-name="<?= strtolower($product['Nama_Brg']); ?>"
                                    onclick="toggleCheckbox(this)">
                                    <div class="row align-items-center">
                                        <div class="col-1">
                                            <input type="checkbox" class="form-check-input product-checkbox"
                                                value="<?= $product['id']; ?>"
                                                id="product_<?= $product['id']; ?>">
                                        </div>
                                        <div class="col-5">
                                            <div class="fw-bold"><?= $product['Nama_Brg']; ?></div>
                                            <small class="text-muted"><?= $product['nama_kategori']; ?></small>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="fw-bold text-primary">Rp <?= number_format($product['harga_jual'], 0, ',', '.'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="text-center py-5">
                                <div class="text-muted">Tidak ada produk yang tersedia untuk ditambahkan</div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- No Results Message -->
                    <div id="noResultsMessage" class="text-center py-5 d-none">
                        <div class="text-muted">Tidak ada produk yang sesuai dengan filter</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="addProductsBtn">Tambahkan Produk Terpilih</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteEventModal" tabindex="-1" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteEventModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Konfirmasi Hapus Event
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center py-4">
                        <i class="fas fa-trash-alt text-danger mb-3" style="font-size: 3rem;"></i>
                        <h5 class="fw-bold mb-2">Yakin ingin menghapus event ini?</h5>
                        <p id="eventName" class="text-muted mb-3"></p>
                        <p class="text-danger small">
                            <i class="fas fa-info-circle me-2"></i>
                            Data yang dihapus tidak dapat dikembalikan
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    <a id="confirmDeleteEvent" href="#" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i> Ya, Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="index.js"></script>
    <script src="../js/sidebar.js"></script>

    <!-- Add this JavaScript code before the closing </body> tag -->
    <script>
        function confirmDeleteEvent(idEvent, namaEvent) {
            // Pastikan modal ada di DOM
            const modalElement = document.getElementById('deleteEventModal');
            if (!modalElement) {
                console.error('Modal element not found');
                return;
            }

            const modal = new bootstrap.Modal(modalElement);

            // Update konten modal
            const eventNameElement = document.getElementById('eventName');
            const confirmBtn = document.getElementById('confirmDeleteEvent');

            if (eventNameElement && confirmBtn) {
                eventNameElement.textContent = `"${namaEvent}"`;
                confirmBtn.href = `hapusEvent.php?id_event=${idEvent}`;
                modal.show();
            } else {
                console.error('Required elements not found in modal');
            }
        }

        // Filter products in the modal
        document.getElementById('searchProduct').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const categoryFilter = document.getElementById('filterKategori').value.toLowerCase();

            const productItems = document.querySelectorAll('.product-item');
            let visibleItems = 0;

            productItems.forEach(item => {
                const productName = item.getAttribute('data-name');
                const productCategory = item.getAttribute('data-category').toLowerCase();

                const nameMatch = productName.includes(searchTerm);
                const categoryMatch = categoryFilter === '' || productCategory === categoryFilter;

                if (nameMatch && categoryMatch) {
                    item.style.display = 'block';
                    visibleItems++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide no results message
            const noResultsMessage = document.getElementById('noResultsMessage');
            if (visibleItems === 0) {
                noResultsMessage.classList.remove('d-none');
            } else {
                noResultsMessage.classList.add('d-none');
            }
        });

        // Handle category filter change
        document.getElementById('filterKategori').addEventListener('change', function() {
            document.getElementById('searchProduct').dispatchEvent(new Event('input'));
        });

        // Delete modal setup
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const idBarang = button.getAttribute('data-id');
                const productName = button.getAttribute('data-name');

                document.getElementById('productNameToDelete').textContent = productName;
                document.getElementById('confirmDeleteBtn').href = `hapusDetailEvent.php?id_event=<?= $idEvent ?>&id_produk=${idBarang}`;
            });
        }

        // Add selected products to event
        document.getElementById('addProductsBtn').addEventListener('click', function() {
            const selectedProducts = [];
            document.querySelectorAll('.product-checkbox:checked').forEach(checkbox => {
                selectedProducts.push(checkbox.value);
            });

            if (selectedProducts.length === 0) {
                alert('Pilih minimal satu produk untuk ditambahkan');
                return;
            }

            // Submit form with selected products
            const form = document.createElement('form');
            form.method = 'post';
            // form.action = 'tambahProdukEvent.php';

            const idEventInput = document.createElement('input');
            idEventInput.type = 'hidden';
            idEventInput.name = 'id_event';
            idEventInput.value = '<?= $idEvent ?>';
            form.appendChild(idEventInput);

            selectedProducts.forEach(productId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'product_ids[]';
                input.value = productId;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        });

        function toggleCheckbox(element) {
            const checkbox = element.querySelector('.product-checkbox');
            checkbox.checked = !checkbox.checked;

            // Tambahkan class untuk visual feedback
            if (checkbox.checked) {
                element.classList.add('bg-light');
            } else {
                element.classList.remove('bg-light');
            }
        }
    </script>

</body>

</html>