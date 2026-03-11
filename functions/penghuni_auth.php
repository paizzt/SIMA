<?php
session_start();

// 1. Cek Login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penghuni') {
    header("Location: ../../login.php");
    exit();
}

// 2. Cek Pembayaran Awal (Fitur Baru yang Diperketat)
include '../../config/database.php';
$id_user = $_SESSION['user_id'];
$current_page = basename($_SERVER['PHP_SELF']);

// CEK STATUS: Hanya yang statusnya "LUNAS" (Dikonfirmasi Admin) yang membuka akses
$cek_bayar = mysqli_query($conn, "SELECT id FROM pembayaran WHERE id_pendaftar = '$id_user' AND status = 'verified' LIMIT 1");
$sudah_lunas = mysqli_num_rows($cek_bayar) > 0;

// Simpan status lunas ke session agar bisa dipakai di Sidebar
$_SESSION['status_bayar_awal'] = $sudah_lunas;

// LOGIKA PEMBATASAN AKSES:
// Jika BELUM LUNAS dan halaman yang dibuka BUKAN 'pembayaran.php'
if (!$sudah_lunas && $current_page != 'pembayaran.php') {
    // Paksa redirect ke halaman pembayaran
    header("Location: pembayaran.php?msg=first_payment");
    exit();
}
?>