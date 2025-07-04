<?php 
require_once('../Connection/connection.php');
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
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>reset pass bro</h1>
    <form action="update-pass.php" method="POST">
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