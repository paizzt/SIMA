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

    // 3. Upload Foto (Pisahkan logika KTP dan KTM karena KTM sekarang opsional)
    $upload_dir = '../uploads/dokumen_persyaratan/';
    if (!file_exists($upload_dir)) { mkdir($upload_dir, 0777, true); }

    $foto_ktp = '';
    $foto_ktm = ''; // Default kosong

    // Proses KTP (WAJIB)
    if (isset($_FILES['foto_ktp']) && $_FILES['foto_ktp']['error'] == 0) {
        $foto_ktp = uniqid() . '_ktp_' . $_FILES['foto_ktp']['name'];
        $tmp_ktp = $_FILES['foto_ktp']['tmp_name'];
        if (!move_uploaded_file($tmp_ktp, $upload_dir . $foto_ktp)) {
            $foto_ktp = ''; // Gagal pindah file
        }
    }

    // Proses KTM (OPSIONAL)
    // Cek apakah ada file yang dikirim dan tidak ada error
    if (isset($_FILES['foto_ktm']) && $_FILES['foto_ktm']['error'] == 0 && $_FILES['foto_ktm']['name'] != '') {
        $foto_ktm = uniqid() . '_ktm_' . $_FILES['foto_ktm']['name'];
        $tmp_ktm = $_FILES['foto_ktm']['tmp_name'];
        move_uploaded_file($tmp_ktm, $upload_dir . $foto_ktm);
    }

    // Pastikan KTP berhasil diupload (Karena KTP sifatnya wajib)
    if ($foto_ktp != '') {
        
        // 4. Insert ke Database
        $query = "INSERT INTO pendaftaran 
                  (nama_lengkap, jenis_kelamin, no_hp, email, alamat_asal, tanggal_survei, jurusan, password, foto_ktp, foto_ktm, status_verifikasi) 
                  VALUES 
                  ('$nama', '$gender', '$hp', '$email', '$alamat', '$tanggal_survei', '$jurusan', '$password', '$foto_ktp', '$foto_ktm', 'pending')";

        if (mysqli_query($conn, $query)) {
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
                        window.location = '../index.php';
                    }
                });
            </script>";
        } else {
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
        // Jika Foto KTP gagal/kosong
        echo "<script>
            Swal.fire({
                title: 'Gagal Upload!',
                text: 'Foto KTP wajib diunggah dan tidak boleh melebihi batas ukuran.',
                icon: 'warning',
                confirmButtonColor: '#d33'
            }).then(() => { window.history.back(); });
        </script>";
    }
}
?>
</body>
</html>