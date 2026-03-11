<?php 
include '../../functions/penghuni_auth.php'; 
include '../../config/database.php';

$id_pendaftar = $_SESSION['user_id'];

// Ambil status lunas dari session
$is_paid = isset($_SESSION['status_bayar_awal']) ? $_SESSION['status_bayar_awal'] : false;

// Cek apakah ada tagihan yang masih PENDING (sudah upload tapi belum di-acc admin)
$cek_pending = mysqli_query($conn, "SELECT id FROM pembayaran WHERE id_pendaftar = '$id_pendaftar' AND status = 'pending'");
$ada_pending = mysqli_num_rows($cek_pending) > 0;

// --- LOGIKA UPLOAD BUKTI BAYAR ---
if (isset($_POST['upload_bukti'])) {
    $jumlah = $_POST['jumlah_bayar'];
    $tgl_bayar = date('Y-m-d');
    
    $foto = $_FILES['bukti_bayar'];
    $nama_foto = uniqid() . '_' . $foto['name'];
    $target_dir = "../../uploads/bukti_pembayaran/";
    
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

    if (move_uploaded_file($foto['tmp_name'], $target_dir . $nama_foto)) {
        $query = "INSERT INTO pembayaran (id_pendaftar, tanggal_bayar, jumlah_bayar, bukti_bayar, status) 
                  VALUES ('$id_pendaftar', '$tgl_bayar', '$jumlah', '$nama_foto', 'pending')";
        
        if (mysqli_query($conn, $query)) {
            // PERUBAHAN: Jangan set $_SESSION jadi true. Biarkan menunggu acc Admin.
            header("Location: pembayaran.php?msg=success_pending"); 
            exit();
        }
    } else {
        echo "<script>alert('Gagal upload gambar.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - SIMA Penghuni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root { --color-primary: #5A7863; }
        .atm-card {
            background: linear-gradient(135deg, #ff7300 0%, #dab03e 100%); color: white; border-radius: 15px; position: relative; overflow: hidden; transition: transform 0.3s;
        }
        .atm-card:hover { transform: translateY(-5px); }
        .copy-btn { cursor: pointer; transition: all 0.2s; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; }
        .copy-btn:hover { background: white; color: #1e3c72; }
        
        .wajib-bayar-alert {
            border: 2px solid #dc3545 !important;
            animation: pulse-red 2s infinite;
        }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }
    </style>
</head>
<body>

    <?php include '../../layouts/sidebar_penghuni.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body" style="min-height: 100vh;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-primary-custom">
                    <i class="bi bi-wallet2 me-2"></i>Pembayaran Sewa
                </h2>
                <p class="text-muted small mt-1">Kelola tagihan dan upload bukti pembayaran Anda.</p>
            </div>
            
            <?php if(!$is_paid): ?>
                <?php if($ada_pending): ?>
                    <div class="alert alert-warning py-2 px-3 fw-bold mb-0 shadow-sm d-flex align-items-center">
                        <i class="bi bi-hourglass-split me-2"></i> Menunggu Konfirmasi Admin
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger py-2 px-3 fw-bold mb-0 shadow-sm d-flex align-items-center">
                        <i class="bi bi-lock-fill me-2"></i> Menu Lain Terkunci
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <button class="theme-toggle-btn border-0 shadow-sm" onclick="toggleTheme()">
                    <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
                </button>
            <?php endif; ?>
        </div>

        <div class="row g-4">
            <div class="col-lg-5">
                
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0">
                        <div class="atm-card p-4">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div><small class="text-white-50 text-uppercase fw-bold ls-1">Bank Transfer</small><h4 class="fw-bold mt-1 mb-0">BNI</h4></div>
                                <i class="bi bi-credit-card-2-front fs-1 text-white-50"></i>
                            </div>
                            <div class="mb-4">
                                <small class="text-white-50 d-block mb-1">Nomor Rekening</small>
                                <div class="d-flex align-items-center gap-2">
                                    <h3 class="fw-bold mb-0 font-monospace">83187854</h3>
                                    <button class="btn btn-sm copy-btn rounded-pill px-3" onclick="copyRekening()"><i class="bi bi-files me-1"></i> Salin</button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <div><small class="text-white-50 d-block">Atas Nama</small><div class="fw-medium fs-5">RPL 136 BLU UIN ALAUDDIN UNTUK PKD PEMERINTAH</div></div>
                            </div>
                        </div>
                        <div class="p-4 bg-light text-center border-top">
                            <small class="text-muted text-uppercase fw-bold">Nominal Sewa</small>
                            <h3 class="text-success fw-bold mb-0 mt-1">Rp 3.000.000 <span class="fs-6 text-muted fw-normal">/ Tahun</span></h3>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm rounded-4 <?php echo (!$is_paid && !$ada_pending) ? 'wajib-bayar-alert' : 'border-0'; ?>">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold mb-0 text-primary-custom"><i class="bi bi-upload me-2"></i>Upload Bukti Bayar</h6>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Jumlah Transfer</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="jumlah_bayar" class="form-control" placeholder="Contoh: 3000000" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Bukti Foto / Screenshot</label>
                                <input type="file" name="bukti_bayar" class="form-control" accept="image/*" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="upload_bukti" class="btn btn-primary-custom shadow-sm py-2">
                                    <i class=""></i> Kirim
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold mb-0 text-primary-custom"><i class="bi bi-clock-history me-2"></i>Riwayat Pembayaran</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr><th class="ps-4 py-3">Tanggal</th><th>Nominal</th><th>Status</th><th class="text-end pe-4">Bukti</th></tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $q = "SELECT * FROM pembayaran WHERE id_pendaftar = '$id_pendaftar' ORDER BY tanggal_bayar DESC";
                                    $res = mysqli_query($conn, $q);
                                    if (mysqli_num_rows($res) > 0): while ($row = mysqli_fetch_assoc($res)): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark"><?php echo date('d M Y', strtotime($row['tanggal_bayar'])); ?></div>
                                            <small class="text-muted"><?php echo date('H:i', strtotime($row['created_at'] ?? 'now')); ?> WIB</small>
                                        </td>
                                        <td><span class="fw-bold text-dark">Rp <?php echo number_format($row['jumlah_bayar'], 0, ',', '.'); ?></span></td>
                                        <td>
                                            <?php if ($row['status'] == 'lunas'): ?><span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">Lunas</span>
                                            <?php elseif ($row['status'] == 'ditolak'): ?><span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3">Ditolak</span>
                                            <?php else: ?><span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3">Pending</span><?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button onclick="lihatBukti('../../uploads/bukti_pembayaran/<?php echo $row['bukti_bayar']; ?>')" class="btn btn-sm btn-outline-secondary rounded-pill"><i class="bi bi-eye"></i> Lihat</button>
                                        </td>
                                    </tr>
                                    <?php endwhile; else: ?>
                                    <tr><td colspan="4" class="text-center py-5 text-muted"><i class="bi bi-receipt fs-1 d-block mb-3 opacity-25"></i>Belum ada riwayat pembayaran.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../../assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const msg = urlParams.get('msg');
        
        // Passing variabel PHP ke JS
        const isPaid = <?php echo $is_paid ? 'true' : 'false'; ?>;
        const adaPending = <?php echo $ada_pending ? 'true' : 'false'; ?>;

        // KONDISI 1: Belum bayar sama sekali
        if(msg === 'first_payment' && !adaPending && !isPaid){
            Swal.fire({
                icon: 'info',
                title: 'Akses Terkunci!',
                html: `
                    <div class="text-start">
                        <p>Untuk mengakses menu lainnya, silakan <strong>lakukan pembayaran sewa pertama</strong> Anda.</p>
                        <p class="mb-0">1. Transfer ke rekening yang tersedia.<br>2. Upload bukti transfer di halaman ini.</p>
                    </div>
                `,
                confirmButtonText: 'Saya Mengerti',
                confirmButtonColor: '#5A7863',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        }
        
        // KONDISI 2: Sudah bayar tapi status masih Pending
        else if (msg === 'first_payment' && adaPending && !isPaid) {
            Swal.fire({
                icon: 'warning',
                title: 'Menunggu Verifikasi',
                text: 'Bukti pembayaran Anda sedang dicek oleh Admin. Menu lain akan otomatis terbuka setelah Admin menyatakan LUNAS.',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#f0ad4e',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        }
        
        // KONDISI 3: Baru saja berhasil Upload
        else if(msg === 'success_pending'){
            Swal.fire({
                icon: 'success',
                title: 'Bukti Terkirim!',
                text: 'Terima kasih, mohon tunggu Admin memverifikasi pembayaran Anda untuk membuka kunci menu lainnya.',
                confirmButtonColor: '#5A7863'
            });
        }
        
        // KONDISI 4: Bayar sukses dan sudah lunas (Bayar bulan selanjutnya)
        else if(msg === 'success'){
            Swal.fire({icon: 'success', title: 'Berhasil', text: 'Bukti pembayaran terkirim.', timer: 2000, confirmButtonColor: '#5A7863'});
        }

        // Fungsi Lainnya
        function lihatBukti(url) { Swal.fire({ imageUrl: url, imageAlt: 'Bukti', width: 'auto', showConfirmButton: false, showCloseButton: true }); }
        function copyRekening() { 
            navigator.clipboard.writeText("1234567890"); 
            Swal.fire({icon: 'success', title: 'Disalin!', toast: true, position: 'top-end', timer: 1500, showConfirmButton: false}); 
        }
    </script>
</body>
</html>