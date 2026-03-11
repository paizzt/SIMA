<?php 
include '../../functions/penghuni_auth.php'; 
include '../../config/database.php';

$id_user = $_SESSION['user_id'];
$msg = "";

// --- LOGIKA KIRIM PERMOHONAN ---
if (isset($_POST['kirim_permohonan'])) {
    $jenis = $_POST['jenis_permohonan'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    // Validasi sederhana
    if (!empty($jenis) && !empty($keterangan)) {
        $query = "INSERT INTO permohonan (id_pendaftar, jenis_permohonan, keterangan, status) 
                  VALUES ('$id_user', '$jenis', '$keterangan', 'pending')";
        
        if (mysqli_query($conn, $query)) {
            // Redirect untuk mencegah resubmit form saat refresh
            header("Location: permohonan.php?status=success");
            exit();
        } else {
            $msg = "error";
        }
    }
}

// --- LOGIKA AMBIL RIWAYAT ---
$query_history = "SELECT * FROM permohonan WHERE id_pendaftar = '$id_user' ORDER BY tanggal_permohonan DESC";
$result = mysqli_query($conn, $query_history);
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Administratif - SIMA</title>
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
                    <i class="bi bi-file-earmark-text me-2"></i>Administrasi & Permohonan
                </h2>
                <p class="text-muted small mt-1">Ajukan pindah kamar atau perpanjangan sewa disini.</p>
            </div>
            <button class="btn btn-sm btn-outline-secondary rounded-circle p-2" onclick="toggleTheme()">
                <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
            </button>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0 text-primary-custom">Buat Permohonan Baru</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Jenis Permohonan</label>
                                <select name="jenis_permohonan" class="form-select" required>
                                    <option value="" selected disabled>-- Pilih Opsi --</option>
                                    <option value="pindah_kamar">Pengajuan Pindah Kamar</option>
                                    <option value="perpanjangan">Perpanjangan Sewa Tahunan</option>
                                </select>
                                <div class="form-text small text-muted">
                                    <i class="bi bi-info-circle me-1"></i> Pindah kamar tergantung ketersediaan slot kosong.
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alasan / Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="5" placeholder="Contoh: Saya ingin pindah ke lantai 1 karena alasan kesehatan, atau Saya ingin memperpanjang sewa untuk tahun ajaran 2027..." required></textarea>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" name="kirim_permohonan" class="btn btn-primary-custom shadow-sm">
                                    <i class="bi bi-send me-2"></i>Kirim Pengajuan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="alert alert-info border-0 shadow-sm mt-3 d-flex align-items-center rounded-4" role="alert">
                    <i class="bi bi-whatsapp fs-1 me-3"></i>
                    <div>
                        <strong>Butuh Respon Cepat?</strong><br>
                        <span class="small">Setelah mengajukan di web, Anda juga bisa konfirmasi ke Admin via WhatsApp.</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0 text-primary-custom">Riwayat Pengajuan Saya</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <div class="list-group list-group-flush">
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <div class="list-group-item p-4 border-bottom-0 border-top">
                                        <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                            <h6 class="mb-1 fw-bold text-dark text-capitalize">
                                                <?php 
                                                    if($row['jenis_permohonan'] == 'pindah_kamar') 
                                                        echo '<i class="bi bi-arrow-left-right me-2 text-warning"></i>Pindah Kamar';
                                                    else 
                                                        echo '<i class="bi bi-calendar-plus me-2 text-success"></i>Perpanjangan Sewa';
                                                ?>
                                            </h6>
                                            <small class="text-muted"><?php echo date('d M Y, H:i', strtotime($row['tanggal_permohonan'])); ?></small>
                                        </div>
                                        
                                        <p class="mb-2 text-muted small bg-light p-3 rounded-3 fst-italic">
                                            "<?php echo $row['keterangan']; ?>"
                                        </p>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <span class="small text-muted">Status Pengajuan:</span>
                                            <?php 
                                            if($row['status'] == 'pending') 
                                                echo '<span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split me-1"></i>Menunggu Konfirmasi</span>';
                                            elseif($row['status'] == 'approved') 
                                                echo '<span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i>Disetujui</span>';
                                            else 
                                                echo '<span class="badge bg-danger px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i>Ditolak</span>';
                                            ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted opacity-25 mb-3 d-block"></i>
                                <h6 class="text-muted">Belum ada riwayat pengajuan.</h6>
                                <small class="text-muted">Permohonan Anda akan muncul di sini.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <script src="../../assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Cek parameter URL untuk notifikasi
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('status') === 'success'){
            Swal.fire({
                icon: 'success',
                title: 'Terkirim!',
                text: 'Permohonan Anda berhasil dikirim ke pengelola.',
                confirmButtonColor: '#5A7863'
            }).then(() => {
                // Bersihkan URL
                window.history.replaceState(null, null, window.location.pathname);
            });
        }
    </script>
</body>
</html>