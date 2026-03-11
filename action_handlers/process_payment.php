<?php
session_start();
include '../config/database.php';

if (isset($_POST['kirim_bukti'])) {
    $id_user = $_SESSION['user_id'];
    $tgl     = $_POST['tanggal_bayar'];
    $jumlah  = $_POST['jumlah'];
    $ket     = mysqli_real_escape_string($conn, $_POST['keterangan']);

    // Setup Upload
    $target_dir = "../uploads/bukti_pembayaran/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

    $filename = "bayar_" . $id_user . "_" . time() . ".jpg";
    $target_file = $target_dir . $filename;
    
    // Validasi & Upload
    $allowed = ['jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($_FILES["bukti_bayar"]["name"], PATHINFO_EXTENSION));

    if(in_array($ext, $allowed)){
        if (move_uploaded_file($_FILES["bukti_bayar"]["tmp_name"], $target_file)) {
            // Insert ke DB
            $query = "INSERT INTO pembayaran (id_pendaftar, tanggal_bayar, jumlah, bukti_bayar, keterangan, status) 
                      VALUES ('$id_user', '$tgl', '$jumlah', '$filename', '$ket', 'pending')";
            
            if (mysqli_query($conn, $query)) {
                header("Location: ../views/penghuni/pembayaran.php?status=success");
            } else {
                header("Location: ../views/penghuni/pembayaran.php?status=error_db");
            }
        } else {
            header("Location: ../views/penghuni/pembayaran.php?status=error_upload");
        }
    } else {
        header("Location: ../views/penghuni/pembayaran.php?status=invalid_format");
    }
}
?>