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

    $mail->setFrom("neorozatech@gmail.com", "Neoroza Tech");
    $mail->addAddress($email);
    $mail->Subject = "Permintaan Reset Password - Neoroza Tech";
    
    // Improved email content
    $mail->Body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { color: #2c3e50; font-size: 24px; margin-bottom: 20px; }
            .content { margin-bottom: 30px; }
            .button { 
                display: inline-block; 
                padding: 10px 20px; 
                background-color: #3498db; 
                color: white !important; 
                text-decoration: none; 
                border-radius: 5px; 
                margin: 15px 0;
            }
            .footer { 
                margin-top: 30px; 
                font-size: 12px; 
                color: #7f8c8d; 
                border-top: 1px solid #eee;
                padding-top: 10px;
            }
            .warning { color: #e74c3c; font-weight: bold; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>Reset Password Akun Fashion 24</div>
            
            <div class='content'>
                <p>Halo,</p>
                
                <p>Kami menerima permintaan untuk mereset password akun Fashion 24 Anda. 
                Silakan klik tombol di bawah ini untuk melanjutkan proses reset password:</p>
                
                <p><a href='https://backend24.site/Rian/XI/recode/ventra-web/Login/reset-pass.php?token=$token' class='button'>Reset Password Saya</a></p>
                
                <p>Atau salin dan tempel link berikut ke browser Anda:<br>
                <small>https://backend24.site/Rian/XI/recode/ventra-web/Login/reset-pass.php?token=$token</small></p>
                
                <p class='warning'>Link ini akan kadaluarsa dalam 30 menit. Jika Anda tidak meminta reset password, 
                Anda dapat mengabaikan email ini dan password Anda tidak akan berubah.</p>
                
                <p>Terima kasih,<br>
                Tim Neoroza Tech</p>
            </div>
            
            <div class='footer'>
                <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
                <p>&copy; " . date('Y') . " Neoroza Tech. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Set alternative plain text body for non-HTML email clients
    $mail->AltBody = "Reset Password Ventra Web\n\n"
        . "Halo,\n\n"
        . "Kami menerima permintaan untuk mereset password akun Ventra Web Anda. "
        . "Silakan kunjungi link berikut untuk melanjutkan proses reset password:\n\n"
        . "https://backend24.site/Rian/XI/recode/ventra-web/Login/reset-pass.php?token=$token\n\n"
        . "Link ini akan kadaluarsa dalam 30 menit. Jika Anda tidak meminta reset password, "
        . "Anda dapat mengabaikan email ini dan password Anda tidak akan berubah.\n\n"
        . "Terima kasih,\n"
        . "Tim Ventra Web";

    try {
        $mail->send();
        echo "<script>alert('Link reset password telah dikirim ke email Anda.'); window.location.href = '../Login/formLogin.php';</script>";
    } catch (Exception $e) {
        // Log error ke file atau tampilkan di konsol
        error_log("Gagal mengirim email: " . $e->getMessage());
        echo "<script>alert('Gagal mengirim email. Silakan coba lagi atau hubungi admin.'); window.location.href = 'forgot-pass.php';</script>";
    }
} else {
    echo "<script>alert('Email tidak ditemukan.'); window.location.href = '../Login/forgot-pass.php';</script>";
}