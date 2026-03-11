<?php 
include '../../functions/admin_auth.php'; 
include '../../config/database.php';

// 1. Definisikan Judul Halaman sebelum include header
$page_title = "Dashboard Admin";

// 2. Include Header (Otomatis buka <html>, <head>, <body>)
include '../../layouts/header.php';

// ... (Logika PHP/Query Anda tetap disini) ...
$count_pending = 5; // Contoh data
?>

<?php include '../../layouts/sidebar_admin.php'; ?>

<main class="content-wrapper px-md-4 py-4 bg-body">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0 text-primary-custom">Dashboard Overview</h2>
        <button class="btn btn-sm btn-outline-secondary" onclick="toggleTheme()">
            <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
        </button>
    </div>

    <div class="alert alert-success">Contoh konten dashboard yang lebih bersih!</div>

</main>

<?php include '../../layouts/footer.php'; ?>