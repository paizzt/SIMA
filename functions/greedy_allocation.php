<?php

function rekomendasiKamarGreedy($conn) {

    $query = "SELECT * FROM kamar
              WHERE terisi < kapasitas
              AND status != 'perbaikan'
              ORDER BY terisi DESC, nomor_kamar ASC 
              LIMIT 1";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false; 
    }
}

function tetapkanKamar($conn, $id_pendaftar, $id_kamar) {
    
    $sql_user = "UPDATE pendaftaran SET id_kamar = $id_kamar WHERE id = $id_pendaftar";
    
    $sql_kamar = "UPDATE kamar SET terisi = terisi + 1 WHERE id = $id_kamar";
    
    $sql_status = "UPDATE kamar SET status = 'penuh' WHERE id = $id_kamar AND terisi >= kapasitas";

    mysqli_begin_transaction($conn);
    try {
        mysqli_query($conn, $sql_user);
        mysqli_query($conn, $sql_kamar);
        mysqli_query($conn, $sql_status);
        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        return false;
    }
}
?>