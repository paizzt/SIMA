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
                <form action="../../action_handlers/process_register.php" method="POST" enctype="multipart/form-data">
                    
                    <h6 class="text-success fw-bold text-uppercase mb-3 small ls-1">Data Pribadi</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" required placeholder="Sesuai KTP">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="" selected disabled>Pilih...</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nomor WhatsApp</label>
                            <input type="number" name="no_hp" class="form-control" required placeholder="08...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Email Aktif</label>
                            <input type="email" name="email" class="form-control" required placeholder="nama@email.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Alamat Asal</label>
                            <textarea name="alamat_asal" class="form-control" rows="2" required placeholder="Alamat lengkap sesuai KTP"></textarea>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-success"><i class="bi bi-calendar-check me-1"></i>Rencana Tanggal Survei / Masuk</label>
                            <input type="date" name="tanggal_survei" class="form-control bg-success bg-opacity-10 border-success" required>
                            <div class="form-text text-muted small">Pilih tanggal kapan Anda berencana mengecek lokasi atau mulai masuk asrama.</div>
                        </div>
                    </div>

                    <h6 class="text-success fw-bold text-uppercase mb-3 small ls-1">Data Akademik & Akun</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Jurusan</label>
                            <select name="jurusan" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Jurusan --</option>
                                <option value="Akuntansi (S1)">Akuntansi (S1)</option>
                                <option value="Aqidah dan Filsafat Islam (S1)">Aqidah dan Filsafat Islam (S1)</option>
                                <option value="Bahasa dan Sastra Arab (S1)">Bahasa dan Sastra Arab (S1)</option>
                                <option value="Bahasa dan Sastra Inggris (S1)">Bahasa dan Sastra Inggris (S1)</option>
                                <option value="Bimbingan dan Penyuluhan Islam (S1)">Bimbingan dan Penyuluhan Islam (S1)</option>
                                <option value="Biologi (S1)">Biologi (S1)</option>
                                <option value="Ekonomi Islam (S1)">Ekonomi Islam (S1)</option>
                                <option value="Ilmu Komunikasi (S1)">Ilmu Komunikasi (S1)</option>
                                <option value="Ilmu Perpustakaan (S1)">Ilmu Perpustakaan (S1)</option>
                                <option value="Ilmu Peternakan (S1)">Ilmu Peternakan (S1)</option>
                                <option value="Ilmu Politik (S1)">Ilmu Politik (S1)</option>
                                <option value="Jurnalistik (S1)">Jurnalistik (S1)</option>
                                <option value="Kesehatan Masyarakat (S1)">Kesehatan Masyarakat (S1)</option>
                                <option value="Kesejahteraan Sosial (S1)">Kesejahteraan Sosial (S1)</option>
                                <option value="Kimia (S1)">Kimia (S1)</option>
                                <option value="Komunikasi dan Penyiaran Islam (S1)">Komunikasi dan Penyiaran Islam (S1)</option>
                                <option value="Manajemen (S1)">Manajemen (S1)</option>
                                <option value="Manajemen Dakwah (S1)">Manajemen Dakwah (S1)</option>
                                <option value="Manajemen Haji dan Umrah (S1)">Manajemen Haji dan Umrah (S1)</option>
                                <option value="Manajemen Pendidikan Islam (S1)">Manajemen Pendidikan Islam (S1)</option>
                                <option value="Matematika (S1)">Matematika (S1)</option>
                                <option value="Pendidikan Agama Islam (S1)">Pendidikan Agama Islam (S1)</option>
                                <option value="Pendidikan Bahasa Arab (S1)">Pendidikan Bahasa Arab (S1)</option>
                                <option value="Pendidikan Bahasa Inggris (S1)">Pendidikan Bahasa Inggris (S1)</option>
                                <option value="Pendidikan Biologi (S1)">Pendidikan Biologi (S1)</option>
                                <option value="Pendidikan Fisika (S1)">Pendidikan Fisika (S1)</option>
                                <option value="Pendidikan Guru Madrasah Ibtidaiyah (PGMI) (S1)">Pendidikan Guru Madrasah Ibtidaiyah (PGMI) (S1)</option>
                                <option value="Pendidikan Islam Anak Usia Dini (S1)">Pendidikan Islam Anak Usia Dini (S1)</option>
                                <option value="Pendidikan Matematika (S1)">Pendidikan Matematika (S1)</option>
                                <option value="Pengembangan Masyarakat Islam (S1)">Pengembangan Masyarakat Islam (S1)</option>
                                <option value="Perbandingan Madzhab dan Hukum (S1)">Perbandingan Madzhab dan Hukum (S1)</option>
                                <option value="Perbankan Syariah (S1)">Perbankan Syariah (S1)</option>
                                <option value="Sejarah Peradaban Islam (S1)">Sejarah Peradaban Islam (S1)</option>
                                <option value="Sosiologi Agama (S1)">Sosiologi Agama (S1)</option>
                                <option value="Studi Agama-Agama (S1)">Studi Agama-Agama (S1)</option>
                                <option value="Teknik Arsitektur (S1)">Teknik Arsitektur (S1)</option>
                                <option value="Teknik Informatika (S1)">Teknik Informatika (S1)</option>
                                <option value="Teknik Perencanaan Wilayah dan Kota (S1)">Teknik Perencanaan Wilayah dan Kota (S1)</option>
                                <option value="Sistem Informasi (S1)">Sistem Informasi (S1)</option>
                                <option value="Ilmu Hukum (S1)">Ilmu Hukum (S1)</option>
                                <option value="Hukum Ekonomi Syariah / Muamalah (S1)">Hukum Ekonomi Syariah / Muamalah (S1)</option>
                                <option value="Hukum Keluarga Islam (Ahwal Syakhshiyyah) (S1)">Hukum Keluarga Islam (Ahwal Syakhshiyyah) (S1)</option>
                                <option value="Hukum Tata Negara (Siyasah Syariyyah) (S1)">Hukum Tata Negara (Siyasah Syariyyah) (S1)</option>
                                <option value="Ilmu Falak (S1)">Ilmu Falak (S1)</option>
                                <option value="Ilmu Hadis (S1)">Ilmu Hadis (S1)</option>
                                <option value="Ilmu Al-Qur’an dan Tafsir (S1)">Ilmu Al-Qur’an dan Tafsir (S1)</option>
                                <option value="Pendidikan Dokter (S1)">Pendidikan Dokter (S1)</option>
                                <option value="Farmasi (S1)">Farmasi (S1)</option>
                                <option value="Ilmu Keperawatan (S1)">Ilmu Keperawatan (S1)</option>
                                <option value="Kebidanan (S1)">Kebidanan (S1)</option>
                                <option value="Kebidanan (D3)">Kebidanan (D3)</option>
                                <option value="Ners (Profesi)">Ners (Profesi)</option>
                                <option value="Pendidikan Profesi Apoteker (Profesi)">Pendidikan Profesi Apoteker (Profesi)</option>
                                <option value="Pendidikan Profesi Bidan (Profesi)">Pendidikan Profesi Bidan (Profesi)</option>
                                <option value="Pendidikan Profesi Guru (Profesi)">Pendidikan Profesi Guru (Profesi)</option>
                                <option value="Profesi Dokter (Profesi)">Profesi Dokter (Profesi)</option>
                                <option value="Akuntansi Syariah (S2)">Akuntansi Syariah (S2)</option>
                                <option value="Dirasah Islamiyah (S2)">Dirasah Islamiyah (S2)</option>
                                <option value="Ekonomi Syariah (S2)">Ekonomi Syariah (S2)</option>
                                <option value="Ilmu Al-Qur’an dan Tafsir (S2)">Ilmu Al-Qur’an dan Tafsir (S2)</option>
                                <option value="Ilmu Hadis (S2)">Ilmu Hadis (S2)</option>
                                <option value="Hukum (S2)">Hukum (S2)</option>
                                <option value="Pendidikan Agama Islam (S2)">Pendidikan Agama Islam (S2)</option>
                                <option value="Manajemen Pendidikan Islam (S2)">Manajemen Pendidikan Islam (S2)</option>
                                <option value="Pendidikan Bahasa Arab (S2)">Pendidikan Bahasa Arab (S2)</option>
                                <option value="Pendidikan Bahasa Inggris (S2)">Pendidikan Bahasa Inggris (S2)</option>
                                <option value="Keperawatan (S2)">Keperawatan (S2)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Password Akun</label>
                            <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                        </div>
                    </div>

                    <h6 class="text-success fw-bold text-uppercase mb-3 small ls-1">Upload Berkas</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Foto KTP</label>
                            <input type="file" name="foto_ktp" class="form-control" accept="image/*" required>
                            <div class="form-text small">Format JPG/PNG, Max 2MB</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Foto KTM / Profil</label>
                            <input type="file" name="foto_ktm" class="form-control" accept="image/*" required>
                            <div class="form-text small">Format JPG/PNG, Max 2MB</div>
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
</body>
</html>