<?php
session_start();
include '../config/database.php';

if (isset($_POST['lapor'])) {
    $id_user = $_SESSION['user_id'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Setup Upload
    $target_dir = "../uploads/laporan_kerusakan/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

    $filename = "laporan_" . $id_user . "_" . time() . ".jpg"; // Rename file agar unik
    $target_file = $target_dir . $filename;
    
    // Cek upload
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        // Insert DB
        $query = "INSERT INTO laporan_kerusakan (id_pendaftar, judul_laporan, deskripsi, foto_bukti, status) 
                  VALUES ('$id_user', '$judul', '$deskripsi', '$filename', 'baru')";
        
        if (mysqli_query($conn, $query)) {
            header("Location: ../views/penghuni/lapor_kerusakan.php?status=success");
        } else {
            echo "Error DB: " . mysqli_error($conn);
        }
    } else {
        echo "Gagal upload gambar.";
    }
}
?>