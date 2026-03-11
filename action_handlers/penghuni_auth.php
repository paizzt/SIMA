<?php
session_start();

// 1. Cek Login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penghuni') {
    header("Location: ../../login.php");
    exit();
}

// 2. Cek Pembayaran Awal (Fitur Baru)
include '../../config/database.php';
$id_user = $_SESSION['user_id'];
$current_page = basename($_SERVER['PHP_SELF']);

// Cek apakah ada pembayaran dengan status 'pending' atau 'lunas'
// (Artinya penghuni sudah ada itikad baik upload bukti)
$cek_bayar = mysqli_query($conn, "SELECT id FROM pembayaran WHERE id_pendaftar = '$id_user' AND (status = 'pending' OR status = 'lunas') LIMIT 1");
$sudah_bayar = mysqli_num_rows($cek_bayar) > 0;

// Simpan status bayar ke session agar bisa dipakai di Sidebar tanpa query ulang
$_SESSION['status_bayar_awal'] = $sudah_bayar;

// LOGIKA PEMBATASAN AKSES:
// Jika BELUM BAYAR dan halaman yang dibuka BUKAN 'pembayaran.php'
if (!$sudah_bayar && $current_page != 'pembayaran.php') {
    // Paksa redirect ke halaman pembayaran dengan pesan khusus
    header("Location: pembayaran.php?msg=first_payment");
    exit();
}
?>