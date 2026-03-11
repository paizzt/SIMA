<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Survei - SIMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <?php include '../../layouts/navbar_landing.php'; ?>

    <section class="py-5 mt-5">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                        <div class="row g-0">
                            <div class="col-md-5 bg-primary-custom d-flex align-items-center justify-content-center p-5 text-white text-center">
                                <div>
                                    <i class="bi bi-calendar-check display-1 mb-3"></i>
                                    <h3 class="fw-bold">Booking Jadwal</h3>
                                    <p class="small opacity-75">Datang dan buktikan sendiri kenyamanan asrama kami.</p>
                                    <hr class="border-white opacity-25">
                                    <div class="small text-start">
                                        <div class="mb-2"><i class="bi bi-geo-alt me-2"></i>Jl. H.M. Yasin Limpo No. 36</div>
                                        <div class="mb-2"><i class="bi bi-clock me-2"></i>08.00 - 16.00 WITA</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7 p-5 bg-body">
                                <h3 class="fw-bold text-primary-custom mb-4">Isi Data Kunjungan</h3>
                                
                                <form action="../../action_handlers/process_survey.php" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" name="nama" class="form-control bg-light" placeholder="Nama pengunjung" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nomor WhatsApp</label>
                                        <input type="number" name="no_hp" class="form-control bg-light" placeholder="08..." required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="form-label">Tanggal</label>
                                            <input type="date" name="tanggal" class="form-control bg-light" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Jam</label>
                                            <select name="jam" class="form-select bg-light" required>
                                                <option value="09:00">09:00 WITA</option>
                                                <option value="10:00">10:00 WITA</option>
                                                <option value="11:00">11:00 WITA</option>
                                                <option value="13:00">13:00 WITA</option>
                                                <option value="14:00">14:00 WITA</option>
                                                <option value="15:00">15:00 WITA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Pesan Tambahan (Opsional)</label>
                                        <textarea name="pesan" class="form-control bg-light" rows="2" placeholder="Misal: Saya datang bersama orang tua"></textarea>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" name="kirim_survei" class="btn btn-primary-custom shadow">Kirim Jadwal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="../../index.php" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda</a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/theme.js"></script>
    
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('status') === 'success'){
            Swal.fire({
                icon: 'success',
                title: 'Jadwal Terkirim!',
                text: 'Silakan datang sesuai jadwal. Kami akan menghubungi WA Anda jika ada perubahan.',
                confirmButtonColor: '#5A7863'
            }).then(() => {
                window.location.href = '../../index.php';
            });
        }
    </script>
</body>
</html>