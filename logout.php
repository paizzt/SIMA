<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - SIMA</title>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F4F7F5; /* Warna background SIMA */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
    </style>
</head>
<body>

    <script>
        Swal.fire({
            title: 'Berhasil Keluar!',
            text: 'Terima kasih, sampai jumpa kembali.',
            icon: 'success',
            timer: 2000, // Otomatis pindah dalam 2 detik
            timerProgressBar: true,
            showConfirmButton: false,
            background: '#fff',
            color: '#333',
            willClose: () => {
                // Redirect ke halaman Login setelah alert tutup
                window.location.href = 'login.php';
            }
        });
    </script>

</body>
</html>