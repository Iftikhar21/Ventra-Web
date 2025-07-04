<?php

use Dba\Connection;

require_once('../Connection/connection.php');

$email = $_POST['email'];

$token = bin2hex(random_bytes(16));

$token_hash = hash('sha256', $token);

$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$conn = Connection();

$sql = "UPDATE admin
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("sss", $token_hash, $expiry, $email);

$stmt->execute();

if ($conn->affected_rows) {
    $mail = require __DIR__ . '/mailer.php';

    $mail->setFrom("iftikharazharchaudhry@gmail.com", "Iftikhar");
    $mail->addAddress($email);
    $mail->Subject = "Reset Password";
    $mail->Body = "Klik link berikut untuk reset password Anda: <a href='http://localhost/Fashion24/Login/reset-pass.php?token=$token'>Reset Password</a>";

    try {
        $mail->send();
        echo "<script>alert('Link reset password telah dikirim ke email Anda.'); window.location.href = '../Login/formLogin.php';</script>";
    } catch (Exception $e) {
        // Log error ke file atau tampilkan di konsol
        error_log("Gagal mengirim email: " . $e->getMessage());
        echo "<script>alert('Gagal mengirim email. Silakan coba lagi atau hubungi admin.'); window.location.href = 'forgot-pass.php';</script>";
    }
} else {
    echo "<script>alert('Email tidak ditemukan.'); window.location.href = '../Login/formLogin.php';</script>";
}