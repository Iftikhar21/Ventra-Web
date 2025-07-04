<?php
session_start();
include '../Model/crudAdmin.php';

// Check if user is logged in
if (!isset($_SESSION['ID'])) {
    header('Location: ../Login/login.php');
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_SESSION['ID'];
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';

    // Validate input
    if (empty($username) || empty($email)) {
        header('Location: profile.php?status=error&message=Username dan email harus diisi');
        exit();
    }

    // Update data admin
    $success = updateAdminProfile($id, $username, $email);

    if ($success) {
        $_SESSION['username'] = $username;
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Profil berhasil diperbarui!';
        header('Location: profile.php');
        exit();
    } else {
        $_SESSION['status'] = 'failed';
        $_SESSION['message'] = 'Gagal mengupdate profile. Silakan coba lagi.';
        header('Location: profile.php');
        exit();
    }
} else {
    // If not a POST request, redirect back to profile page
    header('Location: profile.php');
    exit();
}
