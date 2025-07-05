<?php
require_once('../Connection/connection.php');

// Kunci enkripsi - GANTI dengan kunci unik Anda!
define('REMEMBER_ME_SECRET', 'fas24-log-in');
define('COOKIE_EXPIRY_DAYS', 30);

function Login($data) {
    $conn = Connection();
    $username = mysqli_real_escape_string($conn, $data['username']);
    $password = mysqli_real_escape_string($conn, $data['password']);

    $query_user = "SELECT * FROM admin WHERE username = '$username'";
    $result_user = mysqli_query($conn, $query_user);
    $user = mysqli_fetch_assoc($result_user);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            return [
                "status" => true,
                "user" => $user
            ];
        }
    }

    return [
        "status" => false,
        "message" => "Username atau Password salah!"
    ];
}

function createRememberMeCookie($username, $user_id) {
    $expiry_time = time() + (COOKIE_EXPIRY_DAYS * 24 * 60 * 60);
    
    // Data yang akan disimpan di cookie
    $cookie_data = [
        'username' => $username,
        'user_id' => $user_id,
        'expiry' => $expiry_time,
        'hash' => createCookieHash($username, $user_id, $expiry_time)
    ];
    
    // Enkripsi data
    $encrypted_data = encryptCookieData($cookie_data);
    
    // Set cookie untuk 30 hari
    setcookie(
        'remember_me', 
        $encrypted_data, 
        $expiry_time, 
        "/", 
        "", 
        false,  // Hanya HTTPS jika true
        true    // HttpOnly
    );
}

function validateRememberMeCookie($cookie_value) {
    $cookie_data = decryptCookieData($cookie_value);
    
    // Validasi dasar
    if (!$cookie_data || !isset($cookie_data['username']) || !isset($cookie_data['user_id']) || 
        !isset($cookie_data['expiry']) || !isset($cookie_data['hash'])) {
        clearRememberMeCookie();
        return ["status" => false];
    }
    
    // Cek masa berlaku
    if ($cookie_data['expiry'] < time()) {
        clearRememberMeCookie();
        return ["status" => false];
    }
    
    // Validasi hash
    $expected_hash = createCookieHash($cookie_data['username'], $cookie_data['user_id'], $cookie_data['expiry']);
    if (!hash_equals($expected_hash, $cookie_data['hash'])) {
        clearRememberMeCookie();
        return ["status" => false];
    }
    
    // Verifikasi user masih ada di database
    $conn = Connection();
    $user_id = mysqli_real_escape_string($conn, $cookie_data['user_id']);
    $query = "SELECT * FROM admin WHERE id = '$user_id' AND username = '{$cookie_data['username']}'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    
    if ($user) {
        return [
            "status" => true,
            "user" => $user
        ];
    }
    
    clearRememberMeCookie();
    return ["status" => false];
}

function clearRememberMeCookie() {
    setcookie('remember_me', '', time() - 3600, "/");
}

// Fungsi pembantu
function createCookieHash($username, $user_id, $expiry) {
    return hash_hmac('sha256', $username . $user_id . $expiry, REMEMBER_ME_SECRET);
}

function encryptCookieData($data) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt(json_encode($data), 'aes-256-cbc', REMEMBER_ME_SECRET, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decryptCookieData($data) {
    try {
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        $decrypted = openssl_decrypt($encrypted_data, 'aes-256-cbc', REMEMBER_ME_SECRET, 0, $iv);
        return json_decode($decrypted, true);
    } catch (Exception $e) {
        return false;
    }
}
?>