<?php
include '../../config/database.php';

$data_status = null;
$not_found = false;

// PROSES 1: Pencarian Status
if (isset($_POST['cari'])) {
    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);
    
    $query = "SELECT * FROM pendaftaran WHERE email = '$keyword' OR id = '$keyword'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $data_status = mysqli_fetch_assoc($result);
    } else {
        $not_found = true;
    }
}

// PROSES 2: Pembatalan Pendaftaran
if (isset($_POST['batal_daftar'])) {
    $id_batal = mysqli_real_escape_string($conn, $_POST['id_pendaftar']);
    $saran = mysqli_real_escape_string($conn, $_POST['saran']);

    // Update status menjadi 'dibatalkan', simpan saran, dan kosongkan ID kamar jika sudah di-assign
    $query_batal = "UPDATE pendaftaran 
                    SET status_verifikasi = 'dibatalkan', 
                        saran_batal = '$saran',
                        id_kamar = NULL 
                    WHERE id = '$id_batal'";

    if (mysqli_query($conn, $query_batal)) {
        // Berhasil batal -> Redirect ke index.php membawa pesan batal_sukses
        header("Location: ../../index.php?msg=batal_success");
        exit();
    } else {
        echo "<script>alert('Gagal membatalkan pendaftaran. Kesalahan Database.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Seleksi - SIMA</title>
    <link rel="icon" type="image/png" href="../../assets/img/logo1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { --color-primary: #5A7863; --color-bg: #F4F7F5; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--color-bg); min-height: 100vh; display: flex; flex-direction: column; }
        .navbar { background-color: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .bg-primary-custom { background-color: var(--color-primary) !important; }
        .btn-primary-custom { background-color: var(--color-primary); color: white; transition: all 0.3s; }
        .btn-primary-custom:hover { background-color: #46604e; color: white; transform: translateY(-2px); }
        .card-status { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .form-batal { background-color: #fff5f5; border: 1px solid #ffcaca; padding: 20px; border-radius: 15px; margin-top: 20px; display: none; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="../../index.php">
                <img src="../../assets/img/logo1.png" alt="Logo SIMA" width="40" height="40" class="me-2">
                <span class="fw-bold text-success">SIMA</span>
            </a>
            <a href="../../index.php" class="btn btn-outline-secondary rounded-pill btn-sm fw-bold px-3">
                <i class=""></i> Kembali
            </a>
        </div>
    </nav>

    <div class="container py-5 mt-4 flex-grow-1">
        <div class="row justify-content-center g-4">
            
            <div class="col-lg-5">
                <div class="card card-status p-4 h-100">
                    <div class="text-center mb-4">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-search fs-2"></i>
                        </div>
                        <h4 class="fw-bold">Cek Status Anda</h4>
                        <p class="text-muted small">Masukkan Email atau ID Pendaftaran untuk melihat pengumuman kelulusan.</p>
                    </div>

                    <form method="POST" action="">
                        <div class="form-floating mb-4">
                            <input type="text" name="keyword" class="form-control rounded-4" id="inputKeyword" placeholder="nama@email.com" required value="<?php echo isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : ''; ?>">
                            <label for="inputKeyword">Email / ID Pendaftaran</label>
                        </div>
                        <button type="submit" name="cari" class="btn btn-primary-custom w-100 rounded-pill py-3 fw-bold shadow-sm">
                            Lihat <i class=""></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-status p-4 h-100 d-flex flex-column justify-content-center" style="overflow-y: auto;">
                    
                    <?php if ($data_status): 
                        $status = $data_status['status_verifikasi'];
                    ?>
                        <div class="text-center">
                            <div class="bg-primary-custom text-white fw-bold rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px; font-size: 2rem;">
                                <?php echo strtoupper(substr($data_status['nama_lengkap'], 0, 1)); ?>
                            </div>
                            
                            <h4 class="fw-bold mb-1 text-dark"><?php echo $data_status['nama_lengkap']; ?></h4>
                            <p class="text-muted mb-4"><?php echo htmlspecialchars($data_status['jurusan']); ?></p>

                            <?php if ($status == 'pending'): ?>
                                <div class="alert alert-warning border-0 py-3 rounded-4 mb-4 text-start d-flex align-items-center gap-3">
                                    <i class="bi bi-hourglass-split fs-1"></i>
                                    <div>
                                        <h5 class="fw-bold mb-1">Menunggu Verifikasi</h5>
                                        <p class="mb-0 small">Berkas pendaftaran Anda sedang dalam antrean pengecekan admin.</p>
                                    </div>
                                </div>
                            <?php elseif ($status == 'diterima'): ?>
                                <div class="alert alert-success border-0 py-3 rounded-4 mb-4 text-start d-flex align-items-center gap-3">
                                    <i class="bi bi-check-circle-fill fs-1"></i>
                                    <div>
                                        <h5 class="fw-bold mb-1">Selamat! Anda Diterima</h5>
                                        <p class="mb-0 small">Silakan masuk ke sistem untuk melihat tagihan dan kamar Anda.</p>
                                    </div>
                                </div>
                                <a href="../../login.php" class="btn btn-success rounded-pill px-4 shadow-sm mb-3">Login ke Dashboard</a>
                            <?php elseif ($status == 'dibatalkan'): ?>
                                <div class="alert alert-secondary border-0 py-3 rounded-4 mb-4 text-start d-flex align-items-center gap-3">
                                    <i class="bi bi-x-octagon-fill fs-1"></i>
                                    <div>
                                        <h5 class="fw-bold mb-1">Pendaftaran Dibatalkan</h5>
                                        <p class="mb-0 small">Anda telah membatalkan proses pendaftaran asrama ini.</p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-danger border-0 py-3 rounded-4 mb-4 text-start d-flex align-items-center gap-3">
                                    <i class="bi bi-x-circle-fill fs-1"></i>
                                    <div>
                                        <h5 class="fw-bold mb-1">Mohon Maaf</h5>
                                        <p class="mb-0 small">Pendaftaran Anda tidak dapat kami setujui saat ini.</p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($status == 'pending' || $status == 'diterima'): ?>
                                <hr class="my-4 opacity-10">
                                <p class="small text-muted mb-2">Berubah pikiran? Anda bisa membatalkan pendaftaran ini.</p>
                                
                                <button type="button" class="btn btn-outline-danger rounded-pill px-4 fw-bold" onclick="toggleFormBatal()">
                                    Batalkan Pendaftaran
                                </button>

                                <div id="boxBatal" class="form-batal text-start">
                                    <h6 class="fw-bold text-danger mb-3"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Pembatalan</h6>
                                    
                                    <form id="formBatal" method="POST" action="">
                                        <input type="hidden" name="id_pendaftar" value="<?php echo $data_status['id']; ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Alasan Batal / Saran Perbaikan (Wajib)</label>
                                            <textarea id="inputSaran" name="saran" class="form-control" rows="3" placeholder="Ceritakan kenapa Anda batal..." required></textarea>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-light btn-sm w-50 fw-bold" onclick="toggleFormBatal()">Tutup</button>
                                            <button type="button" class="btn btn-danger btn-sm w-50 fw-bold" onclick="prosesBatal()">Kirim & Batal</button>
                                        </div>
                                        <input type="hidden" name="batal_daftar" value="1">
                                    </form>
                                </div>

                            <?php endif; ?>

                        </div>

                    <?php elseif ($not_found): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-search display-1 text-muted opacity-25 mb-3 d-block"></i>
                            <h5 class="fw-bold text-muted">Data Tidak Ditemukan</h5>
                            <p class="text-muted mb-0">Pastikan Email atau ID Pendaftaran sudah diketik dengan benar.</p>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted opacity-25 mb-3 d-block"></i>
                            <h5 class="fw-bold text-muted">Hasil Seleksi</h5>
                            <p class="text-muted mb-0">Silakan masukkan data pada form di sebelah kiri.</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>

    <script>
        // Memunculkan atau menyembunyikan box pembatalan
        function toggleFormBatal() {
            var box = document.getElementById('boxBatal');
            if (box.style.display === "none" || box.style.display === "") {
                box.style.display = "block";
                box.scrollIntoView({behavior: "smooth"}); // Scroll halus ke bawah
            } else {
                box.style.display = "none";
            }
        }

        // Fungsi Validasi & Konfirmasi Batal dengan SweetAlert
        function prosesBatal() {
            var saran = document.getElementById('inputSaran').value;
            var form = document.getElementById('formBatal');

            if (saran.trim() === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Wajib Diisi',
                    text: 'Mohon tuliskan alasan pembatalan Anda terlebih dahulu.'
                });
                return;
            }

            Swal.fire({
                title: 'Batalkan Pendaftaran?',
                text: "Anda yakin? Data pendaftaran Anda tidak akan diproses lebih lanjut.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit form jika tombol 'Ya' ditekan
                }
            });
        }
    </script>
</body>
</html>