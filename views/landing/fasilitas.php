<?php $page_title = "Fasilitas Asrama"; ?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fasilitas Lengkap - SIMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

    <?php include '../../layouts/navbar_landing.php'; ?>

    <section class="bg-primary-custom text-white py-5 mt-5">
        <div class="container py-5 text-center">
            <h1 class="fw-bold display-5">Fasilitas & Layanan</h1>
            <p class="lead opacity-75">Kenyamanan hunian standar apartemen dengan harga mahasiswa.</p>
        </div>
    </section>

    <div class="container py-5">
        
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                     class="img-fluid rounded-4 shadow-lg" alt="Interior Kamar">
            </div>
            <div class="col-lg-6 ps-lg-5">
                <h6 class="text-primary-custom fw-bold text-uppercase ls-2">Interior Kamar</h6>
                <h2 class="fw-bold mb-4">Ruang Hunian Nyaman</h2>
                <p class="text-muted">Setiap kamar didesain untuk ditempati 4 orang dengan tata letak yang memaksimalkan ruang gerak dan privasi.</p>
                
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                                <span>Ranjang Susun Kokoh</span>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                                <span>Kasur Busa Tebal</span>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                                <span>Lemari Pribadi (Kunci)</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                                <span>Kamar Mandi Dalam</span>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                                <span>Meja & Kursi Belajar</span>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                                <span>Ventilasi Udara Baik</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Fasilitas Gedung</h2>
            <p class="text-muted">Fasilitas penunjang untuk menudukung aktivitas harian Anda.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-facility h-100 p-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-custom text-white rounded-circle mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-wifi fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Wi-Fi Area</h5>
                    <p class="text-muted small">Akses internet tersedia di area lobi dan ruang belajar bersama.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-facility h-100 p-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-custom text-white rounded-circle mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-check fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Keamanan 24 Jam</h5>
                    <p class="text-muted small">Dilengkapi CCTV dan petugas keamanan yang berjaga siang malam.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-facility h-100 p-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-custom text-white rounded-circle mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-droplet fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Air Bersih & Listrik</h5>
                    <p class="text-muted small">Gratis biaya penggunaan air dan listrik (pemakaian wajar).</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-facility h-100 p-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-custom text-white rounded-circle mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-bicycle fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Area Parkir</h5>
                    <p class="text-muted small">Area parkir motor yang luas dan aman di dalam gerbang asrama.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-facility h-100 p-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-custom text-white rounded-circle mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-shop fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Dekat Minimarket</h5>
                    <p class="text-muted small">Lokasi strategis dekat dengan minimarket dan warung makan.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-facility h-100 p-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-custom text-white rounded-circle mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-people fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Ruang Bersama</h5>
                    <p class="text-muted small">Tempat untuk bersosialisasi, diskusi, dan kerja kelompok.</p>
                </div>
            </div>
        </div>

        <div class="card bg-primary-custom text-white rounded-4 p-5 mt-5 text-center shadow-lg border-0">
            <h2 class="fw-bold">Tertarik Melihat Langsung?</h2>
            <p class="mb-4 opacity-75">Kami menyediakan kamar contoh di Lantai 1 untuk Anda survei.</p>
            <div>
                <a href="survei.php" class="btn btn-light rounded-pill px-4 py-2 fw-bold text-primary-custom me-2">Jadwalkan Survei</a>
                <a href="daftar.php" class="btn btn-outline-light rounded-pill px-4 py-2">Daftar Sekarang</a>
            </div>
        </div>

    </div>

    <footer class="py-4 border-top bg-card text-center">
        <div class="container">
            <p class="mb-0 text-muted small">&copy; 2026 SIMA. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/theme.js"></script>
</body>
</html>