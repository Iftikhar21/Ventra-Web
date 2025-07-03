<?php
    session_start();
    include 'crud.php';

    if (isset($_POST['submit'])) {
        $result = Login($_POST);

        if ($result["status"]) {
            $_SESSION['username'] = $result["user"]['username'];
            $_SESSION['ID'] = $result["user"]['id'];
            header("Location: ../Dashboard/index.php");
            exit;
        } else {
            echo "<script>alert('{$result["message"]}'); window.location='formLogin.php';</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
</body>
</html>