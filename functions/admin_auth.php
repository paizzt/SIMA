<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Jika bukan admin, tendang ke login
    header("Location: ../../login.php?error=access_denied");
    exit();
}
?>