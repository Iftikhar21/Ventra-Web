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
</body>
</html>