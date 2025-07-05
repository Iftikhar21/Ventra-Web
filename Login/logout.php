<?php
session_start();
include 'crud.php';

// Hapus semua data session
$_SESSION = array();
session_destroy();

// Hapus cookie remember me
clearRememberMeCookie();

header("Location: formLogin.php");
exit;
?>