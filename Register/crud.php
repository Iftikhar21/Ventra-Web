<?php
    require_once('../Connection/connection.php');
    function Register($username, $password) {
        $conn = Connection();

        // Gunakan prepared statement untuk mencegah SQL Injection
        $stmt = $conn->prepare("SELECT id FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Username sudah ada
        if ($stmt->num_rows > 0) {
            return ["status" => false, "message" => "Username sudah digunakan"];
        }

        $stmt->close();

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashedPassword);
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