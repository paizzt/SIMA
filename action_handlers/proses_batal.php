<?php
include '../config/database.php';

if (isset($_POST['id_pendaftaran']) && isset($_POST['saran'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id_pendaftaran']);
    $saran = mysqli_real_escape_string($conn, $_POST['saran']);

    // Update status jadi dibatalkan, simpan saran, dan kosongkan kamar (jika sebelumnya sudah dapat kamar)
    $query = "UPDATE pendaftaran 
              SET status_verifikasi = 'dibatalkan', 
                  saran_batal = '$saran',
                  id_kamar = NULL 
              WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        // Redirect kembali ke index dengan pesan sukses
        header("Location: ../index.php?msg=batal_success");
        exit();
    } else {
        echo "Gagal membatalkan: " . mysqli_error($conn);
    }
} else {
    header("Location: ../index.php");
}
?>