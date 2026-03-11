<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMA - Sistem Informasi Manajemen Asrama</title>
    <link rel="icon" type="image/png" href="assets/img/logo1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --color-primary: #5A7863;
            --color-dark: #2F3E35;
            --color-light: #F4F7F5;
        }
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; background-color: #fff; }

        /* --- NAVBAR --- */
        .navbar { background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); padding: 15px 0; transition: all 0.3s; }
        .navbar-brand { font-weight: 800; color: var(--color-primary) !important; font-size: 1.5rem; }
        .nav-link { font-weight: 600; color: var(--color-dark) !important; margin: 0 10px; }
        .nav-link:hover { color: var(--color-primary) !important; }

        /* --- HERO SECTION --- */
        .hero-section {
            position: relative; padding: 160px 0 100px;
            background: url('assets/img/asrama.jpeg') no-repeat center center/cover;
            color: white; min-height: 90vh; display: flex; align-items: center;
        }
        .hero-section::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(47, 62, 53, 0.9) 0%, rgba(90, 120, 99, 0.8) 100%); z-index: 1;
        }
        .hero-content { position: relative; z-index: 2; }

        /* --- CARDS & BUTTONS --- */
        .feature-card { background: white; padding: 40px 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.05); height: 100%; }
        .feature-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(90, 120, 99, 0.15); border-color: var(--color-primary); }
        .icon-box { width: 60px; height: 60px; background: var(--color-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 20px; transition: all 0.3s; }
        .feature-card:hover .icon-box { background: var(--color-primary); color: white; }

        .btn-primary-custom { background-color: var(--color-primary); color: white; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 600; transition: all 0.3s; }
        .btn-primary-custom:hover { background-color: #46604e; transform: translateY(-2px); color: white; }
        .btn-outline-light-custom { border: 2px solid rgba(255,255,255,0.3); color: white; padding: 12px 30px; border-radius: 50px; font-weight: 600; transition: all 0.3s; }
        .btn-outline-light-custom:hover { background: white; color: var(--color-primary); border-color: white; }

        .status-box { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); padding: 30px; border-radius: 20px; color: white; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="assets/img/logo2.png" alt="Logo SIMA" class="me-2" width="40" height="40">
                <span>SIMA</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fasilitas">Fasilitas</a></li>
                    <li class="nav-item"><a class="nav-link" href="#alur">Alur Pendaftaran</a></li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="views/landing/status_seleksi.php" class="btn btn-outline-success rounded-pill px-4 fw-bold" style="border-color: var(--color-primary); color: var(--color-primary);">
                        Cek Kelulusan
                    </a>
                    <a href="login.php" class="btn btn-primary-custom shadow-sm">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <section id="beranda" class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-5 mb-lg-0">
                    <span class="badge bg-white text-success px-3 py-2 rounded-pill mb-3 fw-bold shadow-sm">
                        <i class=""></i> Pendaftaran Dibuka
                    </span>
                    <h1 class="display-3 fw-bold mb-4">Hunian Nyaman,<br>Belajar Tenang.</h1>
                    <p class="lead mb-4 opacity-75 pe-lg-5">SIMA menghadirkan pengalaman tinggal di asrama yang modern, aman, dan mendukung produktivitas akademik Anda.</p>
                    
                    <div class="d-flex flex-wrap gap-3">
                        <a href="views/landing/daftar.php" class="btn btn-primary-custom btn-lg shadow-lg">
                            Daftar <i class=""></i>
                        </a>
                        <a href="#fasilitas" class="btn btn-outline-light-custom btn-lg">
                            Jelajahi Fasilitas
                        </a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="status-box shadow-lg">
                        <h4 class="fw-bold mb-3"><i class="bi bi-search me-2"></i>Cek Status Seleksi</h4>
                        <p class="opacity-75 small mb-4">Sudah mendaftar? Klik tombol di bawah untuk melihat pengumuman kelulusan Anda.</p>
                        
                        <div class="d-grid">
                            <a href="views/landing/status_seleksi.php" class="btn btn-light fw-bold py-3 rounded-3 text-success text-decoration-none">
                                Cek status<i class=""></i>
                            </a>
                        </div>
                        
                        <div class="mt-4 pt-3 border-top border-white border-opacity-25 d-flex justify-content-between small opacity-75">
                            <span><i class="bi bi-info-circle me-1"></i> Kuota Tersisa:</span>
                            <span class="fw-bold text-warning">100 kamar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container" style="margin-top: -50px; position: relative; z-index: 10;">
        <div class="row g-4 justify-content-center">
            <div class="col-md-3 col-6">
                <div class="bg-white p-4 rounded-4 shadow text-center h-100">
                    <h2 class="fw-bold text-success mb-0">50+</h2>
                    <small class="text-muted fw-bold">Kamar Tersedia</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="bg-white p-4 rounded-4 shadow text-center h-100">
                    <h2 class="fw-bold text-success mb-0">24h</h2>
                    <small class="text-muted fw-bold">Keamanan & CCTV</small>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="bg-white p-4 rounded-4 shadow text-center h-100">
                    <h2 class="fw-bold text-success mb-0">FREE</h2>
                    <small class="text-muted fw-bold">High-Speed WiFi</small>
                </div>
            </div>
        </div>
    </div>

    <section id="fasilitas" class="py-5 my-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h6 class="text-success fw-bold text-uppercase ls-2">Fasilitas Unggulan</h6>
                <h2 class="fw-bold display-6">Kenyamanan Prioritas Kami</h2>
            </div>
             <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box"><i class="bi bi-wifi"></i></div>
                        <h5 class="fw-bold mb-3">Internet Cepat</h5>
                        <p class="text-muted mb-0">Akses WiFi dedicated di setiap lantai untuk menunjang kebutuhan belajar online dan tugas.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box"><i class="bi bi-shield-lock"></i></div>
                        <h5 class="fw-bold mb-3">Keamanan 24 Jam</h5>
                        <p class="text-muted mb-0">Dilengkapi CCTV di setiap sudut dan petugas keamanan yang berjaga siang malam.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box"><i class="bi bi-book"></i></div>
                        <h5 class="fw-bold mb-3">Ruang Belajar</h5>
                        <p class="text-muted mb-0">Area komunal yang tenang dan nyaman untuk diskusi kelompok atau belajar mandiri.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="alur" class="py-5 bg-light">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <h6 class="text-success fw-bold text-uppercase">Langkah Mudah</h6>
                    <h2 class="fw-bold display-6 mb-4">Bagaimana Cara Mendaftar?</h2>
                    <p class="text-muted mb-4">Ikuti 4 langkah sederhana untuk menjadi bagian dari keluarga besar Asrama SIMA.</p>
                    <a href="views/landing/daftar.php" class="btn btn-primary-custom shadow-sm">Mulai Pendaftaran</a>
                </div>
                <div class="col-lg-7">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="bg-white p-4 rounded-4 shadow-sm h-100 border-start border-4 border-success position-relative">
                                <h1 class="fw-bold text-light text-end mb-0 position-absolute end-0 top-0 me-3 mt-1" style="color: #eee !important; font-size: 4rem; z-index: 0;">01</h1>
                                <div class="position-relative" style="z-index: 1;">
                                    <h5 class="fw-bold">Buat Akun</h5>
                                    <p class="text-muted small mb-0">Isi formulir pendaftaran online dengan data diri yang valid.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-white p-4 rounded-4 shadow-sm h-100 border-start border-4 border-success position-relative">
                                <h1 class="fw-bold text-light text-end mb-0 position-absolute end-0 top-0 me-3 mt-1" style="color: #eee !important; font-size: 4rem; z-index: 0;">02</h1>
                                <div class="position-relative" style="z-index: 1;">
                                    <h5 class="fw-bold">Upload Berkas</h5>
                                    <p class="text-muted small mb-0">Unggah foto KTP, KTM (Kartu Mahasiswa), dan pas foto terbaru.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
             <div class="row g-4">
                <div class="col-md-4">
                    <h4 class="fw-bold mb-3 text-success">SIMA</h4>
                    <p class="text-white-50 small">Sistem Informasi Manajemen Asrama yang modern, transparan, dan memudahkan pengelolaan hunian mahasiswa.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Tautan Cepat</h5>
                    <ul class="list-unstyled text-white-50 small">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="views/landing/status_seleksi.php" class="text-white-50 text-decoration-none">Cek Kelulusan</a></li>
                        <li class="mb-2"><a href="login.php" class="text-white-50 text-decoration-none">Login Penghuni</a></li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="text-center text-white-50 small">
                &copy; 2026 SIMA - Sistem Informasi Manajemen Asrama. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) navbar.classList.add('shadow');
            else navbar.classList.remove('shadow');
        });

        // Notifikasi Notifikasi Sukses Pendaftaran & Batal
        const urlParamsIndex = new URLSearchParams(window.location.search);
        if(urlParamsIndex.get('msg') === 'batal_success'){
            Swal.fire({
                icon: 'success',
                title: 'Pendaftaran Dibatalkan',
                text: 'Terima kasih atas saran Anda. Pendaftaran berhasil dibatalkan secara permanen.',
                confirmButtonColor: '#5A7863'
            });
        } 
    </script>
</body>
</html>