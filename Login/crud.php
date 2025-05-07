<?php
    require_once('../Connection/connection.php');

    function login($data) {
        $conn = Connection();
        $username = mysqli_real_escape_string($conn, $data['username']);
        $password = mysqli_real_escape_string($conn, $data['password']);

        $query_user = "SELECT * FROM admin WHERE username = '$username'";
        $result_user = mysqli_query($conn, $query_user);
        $user = mysqli_fetch_assoc($result_user);

        if ($user) {
            if ($password === $user['password']) {
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
?>