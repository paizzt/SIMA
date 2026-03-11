<?php
include '../config/database.php';

if (isset($_POST['kirim_survei'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $hp   = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $tgl  = $_POST['tanggal'];
    $jam  = $_POST['jam'];
    $pesan= mysqli_real_escape_string($conn, $_POST['pesan']);

    $query = "INSERT INTO jadwal_survei (nama_lengkap, no_hp, tanggal_survei, jam_survei, pesan, status) 
              VALUES ('$nama', '$hp', '$tgl', '$jam', '$pesan', 'pending')";

    if (mysqli_query($conn, $query)) {
        header("Location: ../views/landing/survei.php?status=success");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>