<?php 
include '../../functions/admin_auth.php'; 
include '../../config/database.php';

$id_admin = $_SESSION['user_id'];
$msg = "";
$msg_type = "";

// --- LOGIKA UPDATE PROFIL ---
if (isset($_POST['update_profil'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_baru = $_POST['password_baru'];
    
    // Cek apakah email sudah dipakai admin lain
    $cek_email = mysqli_query($conn, "SELECT id FROM pengelola WHERE email = '$email' AND id != '$id_admin'");
    if(mysqli_num_rows($cek_email) > 0){
        $msg = "Email sudah digunakan oleh admin lain.";
        $msg_type = "error";
    } else {
        // Siapkan Query Update
        $query_pass = "";
        if(!empty($password_baru)){
            $hash = password_hash($password_baru, PASSWORD_DEFAULT);
            $query_pass = ", password = '$hash'";
        }

        $sql = "UPDATE pengelola SET nama_lengkap = '$nama', email = '$email' $query_pass WHERE id = '$id_admin'";
        
        if(mysqli_query($conn, $sql)){
            // Update Session juga agar nama di sidebar berubah langsung
            $_SESSION['nama'] = $nama;
            $_SESSION['email'] = $email;
            
            $msg = "Profil berhasil diperbarui.";
            $msg_type = "success";
        } else {
            $msg = "Terjadi kesalahan sistem.";
            $msg_type = "error";
        }
    }
}

// --- AMBIL DATA ADMIN TERBARU ---
$query = "SELECT * FROM pengelola WHERE id = '$id_admin'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin - SIMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <?php include '../../layouts/sidebar_admin.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body" style="min-height: 100vh;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-primary-custom">
                    <i class="bi bi-person-badge me-2"></i>Profil Saya
                </h2>
                <p class="text-muted small mt-1">Kelola informasi akun dan keamanan.</p>
            </div>
            <button class="theme-toggle-btn" onclick="toggleTheme()" title="Ganti Mode Tampilan">
                <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
            </button>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 text-center h-100 position-relative overflow-hidden">
                    <div class="card-body p-5">
                        <div class="avatar-xl bg-primary-custom text-white rounded-circle d-flex align-items-center justify-content-center shadow mx-auto mb-4" style="width:120px; height:120px; font-size: 3.5rem;">
                            <?php echo strtoupper(substr($data['nama_lengkap'], 0, 1)); ?>
                        </div>
                        
                        <h4 class="fw-bold mb-1"><?php echo $data['nama_lengkap']; ?></h4>
                        <div class="mb-4">
                            <?php if($data['role'] == 'admin'): ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-pill">Administrator</span>
                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-pill">Staff Pengelola</span>
                            <?php endif; ?>
                        </div>

                        <ul class="list-group list-group-flush text-start small">
                            <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                <span class="text-muted">ID Pengguna</span>
                                <span class="fw-bold text-dark">#<?php echo str_pad($data['id'], 3, '0', STR_PAD_LEFT); ?></span>
                            </li>
                            <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                <span class="text-muted">Email</span>
                                <span class="fw-bold text-dark text-truncate" style="max-width: 150px;"><?php echo $data['email']; ?></span>
                            </li>
                            <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                <span class="text-muted">Status</span>
                                <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0 text-primary-custom">Edit Informasi</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            
                            <h6 class="text-muted text-uppercase small fw-bold mb-3">Data Umum</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                        <input type="text" name="nama" class="form-control" value="<?php echo $data['nama_lengkap']; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <h6 class="text-muted text-uppercase small fw-bold mb-3">Keamanan</h6>
                            <div class="mb-4">
                                <label class="form-label">Ganti Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password_baru" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                                </div>
                                <div class="form-text text-muted small mt-1">
                                    <i class="bi bi-info-circle me-1"></i> Disarankan menggunakan kombinasi huruf dan angka.
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <button type="reset" class="btn btn-light border px-4">Batal</button>
                                <button type="submit" name="update_profil" class="btn btn-primary-custom px-4 shadow-sm">Simpan Perubahan</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <script src="../../assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // SweetAlert Notifikasi
        <?php if($msg_type == 'success'): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?php echo $msg; ?>',
                confirmButtonColor: '#5A7863',
                timer: 2000,
                showConfirmButton: false
            });
        <?php elseif($msg_type == 'error'): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '<?php echo $msg; ?>',
                confirmButtonColor: '#d33'
            });
        <?php endif; ?>
    </script>
</body>
</html>