<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: main.php");
    exit(); // Always include exit after header redirect
} else {
    $uname = $_SESSION['username'];
}
?>


