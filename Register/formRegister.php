<?php
session_start();
include 'crud.php';

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $kodeAkses = trim($_POST['kode_akses']);

    // Kode akses valid
    $kodeAksesValid = "bus123";

    // Validasi kode akses
    if ($kodeAkses !== $kodeAksesValid) {
        $_SESSION['error'] = "Kode akses salah!";
        header("Location: formRegister.php");
        exit;
    }

    // Validasi password
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Konfirmasi password tidak cocok!";
        header("Location: formRegister.php");
        exit;
    }

    if (strlen($password) < 8 || !preg_match('/^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};:<>|.?,\/]+$/', $password)) {
        $_SESSION['error'] = "Password minimal 8 karakter dan tidak mengandung karakter ilegal!";
        header("Location: formRegister.php");
        exit;
    }

    // Panggil fungsi register aman
    $result = Register($username, $password);

    if ($result["status"]) {
        $_SESSION['success'] = "Registrasi berhasil, silakan login.";
        header("Location: ../Login/formLogin.php");
        exit;
    } else {
        $_SESSION['error'] = $result["message"];
        header("Location: formRegister.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <link rel="icon" href="../Img/logoBusanaSatu.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../Style/login.css" />
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
            <h2 class="form-title">Buat Akun Baru</h2>
            <p class="text-muted">Silahkan Buat Akun Baru!</p>
            <form method="POST">
                <div class="mb-3">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Enter your Username" required />
                </div>
                
                <!-- Password -->
                <div class="mb-3">
                    <label for="password">Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" class="form-control" id="password" name="password" placeholder="********" required />
                        <button type="button" class="password-toggle" data-target="password">
                            <i class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="********" required />
                        <button type="button" class="password-toggle" data-target="confirm_password">
                            <i class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <!-- Kode Akses -->
                <div class="mb-3">
                    <label for="kode_akses">Kode Akses</label>
                    <div class="password-input-wrapper">
                        <input type="password" class="form-control" id="kode_akses" name="kode_akses" placeholder="********" required />
                        <button type="button" class="password-toggle" data-target="kode_akses">
                            <i class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" name="submit" class="btn btn-dark w-100">Daftar</button>
                <div class="text-center mt-3">
                    <a href="../Login/formLogin.php" class="text-center text-decoration-none">Sudah Punya Akun? Silahkan Login disini...</a>
                </div>
            </form>
        </div>
        <div class="image-section">
            <div class="overlay">
                <img src="../Img/model.jpg" alt="model" class="img-fluid" />
            </div>
        </div>
    </div>

    <!-- Modal Error -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-danger text-center">
                <div class="modal-header">
                    <h5 class="modal-title">Gagal Registrasi</h5>
                </div>
                <div class="modal-body">
                    <?= $_SESSION['error'] ?? '' ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
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
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

    <?php
    if (isset($_SESSION['error'])) {
        echo "<script>
                    const modal = new bootstrap.Modal(document.getElementById('errorModal'));
                    modal.show();
                </script>";
        unset($_SESSION['error']);
    }
    ?>
</body>

</html>