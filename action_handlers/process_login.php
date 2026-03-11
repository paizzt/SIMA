<?php
session_start();
include '../config/database.php';

// Set header agar browser tahu ini adalah respon JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // --- 1. CEK SEBAGAI ADMIN ATAU STAFF (Tabel PENGELOLA) ---
    // Pastikan tabel 'pengelola' memiliki kolom 'role' (misal isinya: 'admin', 'staff')
    $q_admin = "SELECT * FROM pengelola WHERE email = '$email'";
    $res_admin = mysqli_query($conn, $q_admin);

    if (mysqli_num_rows($res_admin) > 0) {
        $row = mysqli_fetch_assoc($res_admin);
        
        if (password_verify($password, $row['password'])) {
            // Set Session Data
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nama'] = $row['nama_lengkap'];
            $_SESSION['role'] = $row['role']; // Menangkap role dari database (admin atau staff)

            // Tentukan URL Redirect berdasarkan Role
            $redirect_url = '';
            $role_name = '';


            $redirect_url = 'views/admin/dashboard.php';
            $role_name = 'Admin';

            echo json_encode([
                'status' => 'success',
                'role' => $role_name,
                'redirect' => $redirect_url 
            ]);
            exit();
        }
    }

    // --- 2. CEK SEBAGAI PENGHUNI/PENDAFTAR (Tabel PENDAFTARAN) ---
    $q_user = "SELECT * FROM pendaftaran WHERE email = '$email'";
    $res_user = mysqli_query($conn, $q_user);

    if (mysqli_num_rows($res_user) > 0) {
        $row = mysqli_fetch_assoc($res_user);
        
        if (password_verify($password, $row['password'])) {
            
            // Cek Status Akun untuk Penghuni
            if ($row['status_verifikasi'] == 'pending') {
                echo json_encode([
                    'status' => 'info',
                    'title' => 'Akun Belum Aktif',
                    'message' => 'Pendaftaran Anda masih menunggu verifikasi admin.'
                ]);
                exit();
            } 
            elseif ($row['status_verifikasi'] == 'ditolak') {
                echo json_encode([
                    'status' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Mohon maaf, pendaftaran Anda tidak disetujui.'
                ]);
                exit();
            }

            // Login Berhasil untuk Penghuni
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nama'] = $row['nama_lengkap'];
            $_SESSION['role'] = 'penghuni'; // Hardcode role untuk penghuni

            echo json_encode([
                'status' => 'success',
                'role' => 'Penghuni',
                'redirect' => 'views/penghuni/dashboard.php'
            ]);
            exit();
        }
    }

    // --- 3. JIKA GAGAL LOGIN (Keduanya tidak cocok atau password salah) ---
    echo json_encode([
        'status' => 'error',
        'title' => 'Login Gagal',
        'message' => 'Email atau Kata Sandi salah. Silakan coba lagi.'
    ]);
    exit();
}
?>