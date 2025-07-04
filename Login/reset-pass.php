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

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password</h1>
    <form method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <div>
            <label for="password">Password Baru:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="confirm_password">Konfirmasi Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>