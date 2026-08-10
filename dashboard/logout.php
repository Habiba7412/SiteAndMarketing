<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset session variables
$_SESSION = [];

// Destroy session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>
