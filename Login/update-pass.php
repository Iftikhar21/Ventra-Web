<?php 

require_once '../Connection/connection.php';

$token = $_GET['token'];

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

if (isset($_POST['submit'])) {
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE admin SET password = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $user['id']);
    $stmt->execute();

    if ($conn->affected_rows) {
        echo "<script>alert('Password berhasil diupdate.'); window.location.href = '../Login/formLogin.php';</script>";
        exit();
    } else {
        echo "<script>alert('Password gagal diupdate.'); window.location.href = '../Login/formLogin.php';</script>";
        exit();
    }
}
?>