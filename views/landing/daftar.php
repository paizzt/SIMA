<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penghuni Baru - SIMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-primary: #5A7863;
            --color-bg: #F4F7F5;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--color-bg);
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(90, 120, 99, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
        }

        .form-header {
            background: linear-gradient(135deg, var(--color-primary) 0%, #3e5244 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .form-header::after {
            content: ''; position: absolute; top: -50%; right: -10%; width: 200px; height: 200px;
            background: rgba(255,255,255,0.1); border-radius: 50%;
        }

        .btn-primary-custom {
            background-color: var(--color-primary);
            border: none; padding: 12px; border-radius: 10px;
            font-weight: 600; color: white; transition: all 0.3s;
        }
        .btn-primary-custom:hover {
            background-color: #46604e; transform: translateY(-2px);
        }

        .form-control, .form-select {
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #dee2e6;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(90, 120, 99, 0.1);
        }
        
        /* CSS Tambahan untuk Validasi Merah */
        .was-validated .form-control:invalid, .was-validated .form-select:invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(.375em + .1875rem) center;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="auth-card">
            
            <div class="form-header">
                <a href="../../index.php" class="text-white text-decoration-none position-absolute top-0 start-0 m-4">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <h2 class="fw-bold mb-1">Formulir Pendaftaran</h2>
                <p class="mb-0 opacity-75">Isi data diri Anda dengan lengkap dan benar.</p>
            </div>

            <div class="p-4 p-md-5">
                <form class="needs-validation" novalidate action="../../action_handlers/process_register.php" method="POST" enctype="multipart/form-data">
                    
                    <h6 class="text-success fw-bold text-uppercase mb-3 small ls-1">Data Pribadi</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" required placeholder="Sesuai KTP">
                            <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="" selected disabled>Pilih...</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <div class="invalid-feedback">Pilih jenis kelamin.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nomor WhatsApp <span class="text-danger">*</span></label>
                            <input type="number" name="no_hp" class="form-control" required placeholder="08...">
                            <div class="invalid-feedback">Nomor WhatsApp wajib diisi.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Email Aktif <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required placeholder="nama@email.com">
                            <div class="invalid-feedback">Email aktif wajib diisi.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Alamat Asal <span class="text-danger">*</span></label>
                            <textarea name="alamat_asal" class="form-control" rows="2" required placeholder="Alamat lengkap sesuai KTP"></textarea>
                            <div class="invalid-feedback">Alamat asal wajib diisi.</div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-success"><i class="bi bi-calendar-check me-1"></i>Rencana Tanggal Survei / Masuk <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_survei" class="form-control bg-success bg-opacity-10 border-success" required>
                            <div class="invalid-feedback">Pilih rencana tanggal masuk/survei.</div>
                            <div class="form-text text-muted small">Pilih tanggal kapan Anda berencana mengecek lokasi atau mulai masuk asrama.</div>
                        </div>
                    </div>

                    <h6 class="text-success fw-bold text-uppercase mb-3 small ls-1">Data Akademik & Akun</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Jurusan <span class="text-danger">*</span></label>
                            <select name="jurusan" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Jurusan --</option>
                                <option value="Teknik Informatika (S1)">Teknik Informatika (S1)</option>
                                <option value="Sistem Informasi (S1)">Sistem Informasi (S1)</option>
                                <option value="Ilmu Hukum (S1)">Ilmu Hukum (S1)</option>
                                <option value="Manajemen (S1)">Manajemen (S1)</option>
                                <option value="Akuntansi (S1)">Akuntansi (S1)</option>
                                <option value="Lainnya">Lainnya...</option>
                                </select>
                            <div class="invalid-feedback">Silakan pilih jurusan Anda.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Password Akun <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                            <div class="invalid-feedback">Password wajib diisi.</div>
                        </div>
                    </div>

                    <h6 class="text-success fw-bold text-uppercase mb-3 small ls-1">Upload Berkas</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Foto KTP <span class="text-danger">*</span></label>
                            <input type="file" name="foto_ktp" class="form-control" accept="image/*" required>
                            <div class="invalid-feedback">Foto KTP wajib diunggah.</div>
                            <div class="form-text small">Format JPG/PNG, Max 2MB</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Foto KTM / Profil <span class="text-secondary fw-normal fst-italic">(Opsional)</span></label>
                            <input type="file" name="foto_ktm" class="form-control" accept="image/*">
                            <div class="form-text small">Format JPG/PNG, Max 2MB. (Tidak wajib diisi)</div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid">
                        <button type="submit" name="daftar" class="btn btn-primary-custom py-3">
                            Kirim<i class=""></i>
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <small class="text-muted">Sudah punya akun? <a href="../../login.php" class="text-success fw-bold text-decoration-none">Login disini</a></small>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        (() => {
            'use strict'
            // Ambil form yang memiliki class needs-validation
            const forms = document.querySelectorAll('.needs-validation')

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                        
                        // Opsional: Tampilkan alert kecil jika ada yang kosong
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Ada kolom wajib yang belum diisi!',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                    // Tambahkan class was-validated agar warna merahnya muncul
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>