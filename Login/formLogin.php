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
    <link rel="stylesheet" href="../Style/login.css">
</head>
<body>
    <div class="login-container">
        <div class="form-section">
            <h2 class="form-title">Selamat Datang di Ventra!</h2>
            <p class="text-muted">Silahkan Login Sebagai Admin!</p>
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your Username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" onclick="showPasswd()">
                        <label class="form-check-label" for="rememberMe">Show Password</label>
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
    function showPasswd() {
      const x = document.getElementById("password");
      x.type = x.type === "password" ? "text" : "password";
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>