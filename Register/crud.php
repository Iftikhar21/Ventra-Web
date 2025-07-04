<?php
    require_once('../Connection/connection.php');
    function Register($username, $password, $email) {
        $conn = Connection();

        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["status" => false, "message" => "Format email tidak valid"];
        }

        // Gunakan prepared statement untuk mencegah SQL Injection
        $stmt = $conn->prepare("SELECT id FROM admin WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        // Username atau email sudah ada
        if ($stmt->num_rows > 0) {
            return ["status" => false, "message" => "Username atau email sudah digunakan"];
        }

        $stmt->close();

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $conn->prepare("INSERT INTO admin (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashedPassword, $email);
        $success = $stmt->execute();

        $stmt->close();
        $conn->close();

        if ($success) {
            return ["status" => true];
        } else {
            return ["status" => false, "message" => "Gagal mendaftarkan user"];
        }
    }
?>