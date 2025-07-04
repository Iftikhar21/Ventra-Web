<?php
session_start();
include '../Model/crudAdmin.php';

// Check if user is logged in
if (!isset($_SESSION['ID'])) {
    header('Location: ../Login/login.php');
    exit();
}

// Get current user data
$id = $_SESSION['ID'];
$data = getAdminById($id);

if (!$data) {
    // Handle error if user data not found
    die('User data not found');
}

$username = $data['username'];
$email = $data['email'];

$status = $_SESSION['status'] ?? null;
$message = $_SESSION['message'] ?? null;

// Hapus session status setelah digunakan
unset($_SESSION['status'], $_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion 24 - Profile</title>
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
                <li class="nav-item profile-item">
                    <a class="nav-link active" href="../Profile/profile.php">
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
                    <h2 class="text-dark fw-bold m-0">Profile</h2>
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
                <p class="text-muted">Update Profile Kamu</p>
            </div>
            <div class="row">
                <div class="profile-container">
                    <!-- Profile Header -->
                    <div class="profile-header">
                        <div class="profile-icon">
                            <i class="fas fa-user-edit p-2"></i>
                        </div>
                        <div class="profile-title">
                            <h2>Edit Profile</h2>
                            <p>Update Informasi Akun Kamu</p>
                        </div>
                    </div>

                    <!-- Status Messages -->
                    <?php if ($status === 'success'): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                                successModal.show();
                            });
                        </script>
                    <?php elseif ($status === 'error'): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                                document.getElementById('errorModalBody').innerHTML = '<?= addslashes($message) ?>';
                                errorModal.show();
                            });
                        </script>
                    <?php endif; ?>

                    <!-- Profile Form -->
                    <div class="profile-form-section">
                        <form id="updateProfileForm" action="updateProfile.php" method="POST" class="needs-validation" novalidate>
                            <!-- Username Field -->
                            <div class="form-group">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
                                <div class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>

                            <!-- Email Field -->
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                                <div class="invalid-feedback">
                                    Please provide a valid email.
                                </div>
                            </div>

                            <!-- Info Box -->
                            <div class="profile-info-box">
                                <i class="fa-solid fa-circle-info"></i>
                                <span><strong>Username & Email</strong> yang diubah akan memengaruhi login kamu. Pastikan kamu mengingat <strong>Username & Email</strong> kamu.</span>
                            </div>

                            <!-- Form Buttons -->
                            <div class="profile-actions">
                                <button type="submit" class="btn btn-save" id="updateProfileBtn">
                                    <i class="fas fa-save me-2"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmSaveModal" tabindex="-1" aria-labelledby="confirmSaveModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fs-5 fw-bold" id="confirmSaveModalLabel">
                            <i class="fas fa-question-circle me-2"></i>Konfirmasi Perubahan
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle text-warning fs-3 me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-2">Anda akan menyimpan perubahan profil</h6>
                                <p class="mb-0 text-muted">Pastikan semua informasi yang Anda masukkan sudah benar sebelum menyimpan.</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="button" class="btn btn-primary px-4" id="confirmSaveBtn">
                            <i class="fas fa-save me-2"></i>Ya, Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title fs-5 fw-bold" id="successModalLabel">
                            <i class="fas fa-check-circle me-2"></i>Berhasil!
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-success fs-3 me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-2">Profil berhasil diperbarui!</h6>
                                <p class="mb-0 text-muted">Perubahan Anda telah disimpan dengan sukses.</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">
                            <i class="fas fa-thumbs-up me-2"></i>Mengerti
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include your JavaScript files here -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../js/sidebar.js"></script>

        <script>
            // Form validation and confirmation modal
            (function() {
                'use strict';

                const form = document.getElementById('updateProfileForm');
                const confirmSaveModal = new bootstrap.Modal(document.getElementById('confirmSaveModal'));
                const confirmSaveBtn = document.getElementById('confirmSaveBtn');
                let formValid = false;

                // Handle form submission
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    form.classList.add('was-validated');

                    if (form.checkValidity()) {
                        formValid = true;
                        confirmSaveModal.show();
                    }
                }, false);

                // Handle confirmation button click
                confirmSaveBtn.addEventListener('click', function() {
                    if (formValid) {
                        form.submit();
                    }
                });
            })();
        </script>
    </main>
</body>

</html>