<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Pendaftaran...</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: sans-serif; background: #f4f7f5; }
    </style>
</head>
<body>

<?php
include '../config/database.php';

if (isset($_POST['daftar'])) {
    // 1. Ambil Data Input
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $gender = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat_asal']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $tanggal_survei = mysqli_real_escape_string($conn, $_POST['tanggal_survei']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 2. Cek Email Ganda
    $cek = mysqli_query($conn, "SELECT email FROM pendaftaran WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        // POPUP EMAIL SUDAH ADA
        echo "<script>
            Swal.fire({
                title: 'Gagal Mendaftar!',
                text: 'Email tersebut sudah terdaftar. Gunakan email lain.',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Coba Lagi'
            }).then(() => {
                window.history.back();
            });
        </script>";
        exit();
    }

    // 3. Upload Foto
    $upload_dir = '../uploads/dokumen_persyaratan/';
    if (!file_exists($upload_dir)) { mkdir($upload_dir, 0777, true); }

    $foto_ktp = uniqid() . '_ktp_' . $_FILES['foto_ktp']['name'];
    $foto_ktm = uniqid() . '_ktm_' . $_FILES['foto_ktm']['name'];

    $tmp_ktp = $_FILES['foto_ktp']['tmp_name'];
    $tmp_ktm = $_FILES['foto_ktm']['tmp_name'];

    if (move_uploaded_file($tmp_ktp, $upload_dir . $foto_ktp) && move_uploaded_file($tmp_ktm, $upload_dir . $foto_ktm)) {
        
        // 4. Insert ke Database
        $query = "INSERT INTO pendaftaran 
                  (nama_lengkap, jenis_kelamin, no_hp, email, alamat_asal, tanggal_survei, jurusan, password, foto_ktp, foto_ktm, status_verifikasi) 
                  VALUES 
                  ('$nama', '$gender', '$hp', '$email', '$alamat', '$tanggal_survei', '$jurusan', '$password', '$foto_ktp', '$foto_ktm', 'pending')";

        if (mysqli_query($conn, $query)) {
            // POPUP SUKSES - PERUBAHAN DI SINI
            echo "<script>
                Swal.fire({
                    title: 'Pendaftaran Berhasil!',
                    text: 'Data Anda telah terkirim. Silakan cek status seleksi secara berkala.',
                    icon: 'success',
                    confirmButtonColor: '#5A7863',
                    confirmButtonText: 'Kembali ke Beranda',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Kembali ke index.php di folder utama (Landing Page)
                        window.location = '../index.php';
                    }
                });
            </script>";
        } else {
            // POPUP ERROR DB
            echo "<script>
                Swal.fire({
                    title: 'Terjadi Kesalahan!',
                    text: 'Gagal menyimpan ke database: " . mysqli_error($conn) . "',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                }).then(() => { window.history.back(); });
            </script>";
        }
    } else {
        // POPUP GAGAL UPLOAD
        echo "<script>
            Swal.fire({
                title: 'Gagal Upload!',
                text: 'Terjadi masalah saat mengunggah foto. Pastikan ukuran tidak terlalu besar.',
                icon: 'warning',
                confirmButtonColor: '#d33'
            }).then(() => { window.history.back(); });
        </script>";
    }
}
?>
</body>
</html>