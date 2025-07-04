<?php 
require_once('../Connection/connection.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan konfirmasi password tidak cocok.'); window.history.back();</script>";
        exit();
    }
    
    $token_hash = hash('sha256', $token);
    $conn = Connection();
    
    // Check token validity
    $sql = "SELECT * FROM admin WHERE reset_token_hash = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token_hash);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user === null) {
        echo "<script>alert('Token tidak valid atau telah kedaluwarsa.'); window.location.href = '../Login/formLogin.php';</script>";
        exit();
    }
    
    // Update password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE admin SET password = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $user['id']);
    
    if ($stmt->execute()) {
        echo "<script>alert('Password berhasil diupdate.'); window.location.href = '../Login/formLogin.php';</script>";
        exit();
    } else {
        echo "<script>alert('Password gagal diupdate.'); window.location.href = '../Login/formLogin.php';</script>";
        exit();
    }
}

// Handle GET request (show form)
$token = $_GET['token'] ?? '';
$token_hash = hash('sha256', $token);
$conn = Connection();

$sql = "SELECT * FROM admin WHERE reset_token_hash = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user === null) {
    echo "<script>alert('Token tidak valid atau telah kedaluwarsa.'); window.location.href = '../Login/formLogin.php';</script>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fashion 24 - Reset Password</title>
    <link rel="icon" href="../Img/logoBusanaSatu.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../Style/register.css" />
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
            <h2 class="form-title">Reset Password</h2>
            <p class="text-muted">Silahkan Masukkan Password yang Baru!</p>
            <form method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">   
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
                
                <button type="submit" name="submit" class="btn btn-dark w-100">Reset Password</button>
            </form>
        </div>
        <div class="image-section">
            <div class="overlay">
                <img src="../Img/model.jpg" alt="model" class="img-fluid" />
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