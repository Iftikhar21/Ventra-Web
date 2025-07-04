<?php
session_start();
include 'crud.php';

// Cek cookie remember me saat halaman dimuat
if (isset($_COOKIE['remember_me']) && !isset($_SESSION['username'])) {
    $login_result = validateRememberMeCookie($_COOKIE['remember_me']);

    if ($login_result["status"]) {
        $_SESSION['username'] = $login_result["user"]['username'];
        $_SESSION['ID'] = $login_result["user"]['id'];
        header("Location: ../Dashboard/index.php");
        exit;
    }
}

// Proses form login
if (isset($_POST['submit'])) {
    $result = Login($_POST);

    if ($result["status"]) {
        $_SESSION['username'] = $result["user"]['username'];
        $_SESSION['ID'] = $result["user"]['id'];

        // Set cookie remember me jika dicentang
        if (isset($_POST['remember_me'])) {
            createRememberMeCookie($result["user"]['username'], $result["user"]['id']);
        }

        header("Location: ../Dashboard/index.php");
        exit;
    } else {
        $error_message = $result["message"];
    }
}

// Tampilkan modal success jika ada
if (isset($_SESSION['success'])) {
    echo "<script>
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            </script>";
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion 24 - Login</title>
    <link rel="icon" href="../Img/logoBusanaSatu.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../Style/login.css">
    <style>
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #495057;
        }

        .password-input-wrapper {
            position: relative;
        }

        .password-input-wrapper input {
            padding-right: 45px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="form-section">
            <h2 class="form-title">Selamat Datang di<br>Fashion 24!</h2>
            <p class="text-muted">Silahkan Login Sebagai Admin!</p>
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your Username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
                        <button type="button" class="password-toggle" data-target="password">
                            <i class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                    <label class="form-check-label" for="remember_me">Ingat saya selama 30 hari</label>
                </div>
                <div class="mb-3">
                    <a href="forgot-pass.php" class="text-center text-decoration-none">Forgot Password or Username?</a>
                </div>
                <button type="submit" name="submit" class="btn btn-black w-100 mb-3">Sign In</button>
                <div class="text-center">
                    <a href="../Register/formRegister.php" class="text-center text-decoration-none">Belum Punya Akun? Silahkan Register disini...</a>
                </div>
            </form>
        </div>
        <div class="image-section">
            <div class="overlay">
                <img src="../Img/model.jpg" alt="model" class="img-fluid">
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" role="alert">
                <div class="modal-header bg-danger text-light">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Login Gagal
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div class="error-icon-large">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="error-message">
                        <!-- PHP content would go here -->
                        <strong>Username atau password salah!</strong><br>
                        Silakan coba lagi.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="fas fa-redo me-2"></i>Coba Lagi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Success -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-success text-center">
                <div class="modal-header">
                    <h5 class="modal-title">Registrasi Berhasil</h5>
                </div>
                <div class="modal-body">
                    <?= $_SESSION['success'] ?? '' ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        });

        // Show error modal if there's an error
        <?php if (isset($error_message)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            });
        <?php endif; ?>
    </script>
</body>

</html>