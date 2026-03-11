<?php 
include '../../functions/penghuni_auth.php'; 
include '../../config/database.php';

$id_user = $_SESSION['user_id'];
$msg = "";

// --- LOGIKA UPDATE PROFIL ---
if (isset($_POST['update_profil'])) {
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_baru = $_POST['password_baru'];
    
    // 1. Update Password jika diisi
    $pass_query = "";
    if(!empty($password_baru)){
        $pass_hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $pass_query = ", password = '$pass_hash'";
    }

    // 2. Update Foto Profil jika ada upload
    $foto_query = "";
    if(!empty($_FILES['foto_profil']['name'])){
        $target_dir = "../../uploads/profil/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $filename = "profil_" . $id_user . "_" . time() . ".jpg";
        $target_file = $target_dir . $filename;
        
        if(move_uploaded_file($_FILES['foto_profil']['tmp_name'], $target_file)){
            $foto_query = ", foto_profil = '$filename'"; // Asumsi ada kolom foto_profil di tabel pendaftaran (perlu ditambah jika belum ada)
            // Atau kita pakai foto_ktm sebagai foto profil sementara jika tidak ingin ubah DB
        }
    }

    // Query Update Utama
    // Catatan: Kolom 'foto_profil' harus ditambahkan ke tabel database jika ingin fitur ganti foto
    // Untuk saat ini kita update data dasar dulu
    $query = "UPDATE pendaftaran SET no_hp = '$no_hp', email = '$email' $pass_query WHERE id = '$id_user'";
    
    if(mysqli_query($conn, $query)){
        $msg = "success";
    } else {
        $msg = "error";
    }
}

// Ambil Data Terbaru
$query = "SELECT * FROM pendaftaran WHERE id = '$id_user'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - SIMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <?php include '../../layouts/sidebar_penghuni.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-primary-custom">
                    <i class="bi bi-person-circle me-2"></i>Profil & Akun
                </h2>
                <p class="text-muted small mt-1">Kelola informasi pribadi dan keamanan akun.</p>
            </div>
            <button class="btn btn-sm btn-outline-secondary rounded-circle p-2" onclick="toggleTheme()">
                <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
            </button>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4 text-center h-100">
                    <div class="card-body p-4">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="avatar-xl bg-primary-custom text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width:120px; height:120px; font-size: 3rem; margin: 0 auto;">
                                <?php echo strtoupper(substr($data['nama_lengkap'], 0, 1)); ?>
                            </div>
                            <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success border border-white">
                                Aktif
                            </span>
                        </div>
                        
                        <h4 class="fw-bold mb-1"><?php echo $data['nama_lengkap']; ?></h4>
                        <p class="text-muted mb-3"><?php echo $data['jurusan']; ?></p>
                        
                        <div class="d-flex justify-content-center gap-2 mb-4">
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-geo-alt me-1"></i> <?php echo substr($data['alamat_asal'], 0, 15); ?>..
                            </span>
                        </div>

                        <hr>
                        
                        <div class="text-start small">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Bergabung</span>
                                <span class="fw-bold"><?php echo date('d M Y', strtotime($data['tanggal_daftar'])); ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Status Akun</span>
                                <span class="text-success fw-bold">Terverifikasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0 text-primary-custom">Edit Informasi</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" enctype="multipart/form-data">
                            
                            <h6 class="text-muted mb-3 text-uppercase small fw-bold">Data Kontak</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nomor WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-whatsapp"></i></span>
                                        <input type="number" name="no_hp" class="form-control" value="<?php echo $data['no_hp']; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <h6 class="text-muted mb-3 text-uppercase small fw-bold">Data Pribadi (Read Only)</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control bg-light" value="<?php echo $data['nama_lengkap']; ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jurusan</label>
                                    <input type="text" class="form-control bg-light" value="<?php echo $data['jurusan']; ?>" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Alamat Asal</label>
                                    <textarea class="form-control bg-light" rows="2" readonly><?php echo $data['alamat_asal']; ?></textarea>
                                </div>
                            </div>

                            <h6 class="text-muted mb-3 text-uppercase small fw-bold">Keamanan (Isi jika ingin ubah)</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Password Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                        <input type="password" name="password_baru" class="form-control" placeholder="Biarkan kosong jika tetap">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ganti Foto (Opsional)</label>
                                    <input type="file" name="foto_profil" class="form-control" disabled title="Fitur ini akan segera aktif">
                                    <div class="form-text small">Saat ini gunakan inisial default.</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="reset" class="btn btn-light border">Batal</button>
                                <button type="submit" name="update_profil" class="btn btn-primary-custom px-4">Simpan Perubahan</button>
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
        // Notifikasi Sukses/Gagal
        <?php if($msg == 'success'): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Profil Anda telah diperbarui.',
                confirmButtonColor: '#5A7863'
            });
        <?php elseif($msg == 'error'): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat menyimpan data.',
                confirmButtonColor: '#d33'
            });
        <?php endif; ?>
    </script>
</body>
</html>