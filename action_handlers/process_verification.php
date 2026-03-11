<?php
session_start();
include '../config/database.php';
include '../functions/admin_auth.php'; // Pastikan hanya admin yang akses

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_pendaftaran'])) {
    
    $id = $_POST['id_pendaftaran'];
    $action = $_POST['action'];
    $status = '';

    if ($action == 'accept') {
        $status = 'diterima';
    } elseif ($action == 'reject') {
        $status = 'ditolak';
    } else {
        header("Location: ../views/admin/verifikasi_pendaftar.php");
        exit();
    }

    $query = "UPDATE pendaftaran SET status_verifikasi = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        // Redirect dengan pesan sukses
        header("Location: ../views/admin/verifikasi_pendaftar.php?status=success_update");
    } else {
        header("Location: ../views/admin/detail_pendaftar.php?id=$id&error=db_error");
    }
    
    $stmt->close();
    $conn->close();

} else {
    header("Location: ../views/admin/verifikasi_pendaftar.php");
    exit();
}
?>