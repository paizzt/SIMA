<?php 
include '../../functions/admin_auth.php'; 
include '../../config/database.php';

// --- LOGIKA HITUNG DATA ---
$sql_pending = "SELECT COUNT(*) as total FROM pendaftaran WHERE status_verifikasi = 'pending'";
$res_pending = mysqli_query($conn, $sql_pending);
$count_pending = mysqli_fetch_assoc($res_pending)['total'];

$sql_active = "SELECT COUNT(*) as total FROM pendaftaran WHERE status_verifikasi = 'diterima'";
$res_active = mysqli_query($conn, $sql_active);
$count_active = mysqli_fetch_assoc($res_active)['total'];

$sql_kamar = "SELECT SUM(kapasitas) as total_kapasitas FROM kamar";
$res_kamar = mysqli_query($conn, $sql_kamar);
$total_kapasitas = mysqli_fetch_assoc($res_kamar)['total_kapasitas'];
$sisa_kamar = $total_kapasitas - $count_active;
if($sisa_kamar < 0) $sisa_kamar = 0;

$sql_laporan = "SELECT COUNT(*) as total FROM laporan_kerusakan WHERE status != 'selesai'"; // Sesuaikan dengan nilai enum db Anda (selesai)
$res_laporan = mysqli_query($conn, $sql_laporan);
$count_laporan = mysqli_fetch_assoc($res_laporan)['total'];

$sql_newest = "SELECT * FROM pendaftaran WHERE status_verifikasi = 'pending' ORDER BY tanggal_daftar DESC LIMIT 5";
$res_newest = mysqli_query($conn, $sql_newest);

// --- TAMBAHAN: QUERY JADWAL SURVEI TERDEKAT ---
$sql_survei = "SELECT * FROM jadwal_survei WHERE status = 'pending' AND tanggal_survei >= CURDATE() ORDER BY tanggal_survei ASC, jam_survei ASC LIMIT 4";
$res_survei = mysqli_query($conn, $sql_survei);
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIMA</title>
    <link rel="icon" type="image/png" href="../../assets/img/logo1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Setup Warna Dasar */
        :root {
            --color-primary: #5A7863;
        }

        /* Animasi Masuk */
        .animate-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
        }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        /* Card Styling Konsisten (Hijau) */
        .stat-card {
            transition: all 0.3s ease;
            border-left: 5px solid var(--color-primary) !important;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(90, 120, 99, 0.15) !important;
        }
        
        .icon-box {
            background-color: rgba(90, 120, 99, 0.1);
            color: var(--color-primary);
            transition: transform 0.3s ease;
        }
        .stat-card:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
            background-color: var(--color-primary);
            color: #fff;
        }

        /* Welcome Banner Gradient */
        .welcome-banner {
            background: linear-gradient(135deg, var(--color-primary) 0%, #3e5244 100%);
            position: relative;
            overflow: hidden;
        }
        .welcome-banner::after, .welcome-banner::before {
            content: ''; position: absolute; background: rgba(255,255,255,0.05); border-radius: 50%;
        }
        .welcome-banner::after { top: -50%; right: -10%; width: 300px; height: 300px; }
        .welcome-banner::before { bottom: -50%; left: -5%; width: 200px; height: 200px; }
    </style>
</head>
<body>

    <?php include '../../layouts/sidebar_admin.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body" style="min-height: 100vh;">
        
        <div class="welcome-banner text-white p-4 rounded-4 mb-4 shadow-sm animate-up d-flex align-items-center justify-content-between">
            <div style="z-index: 1;">
                <h2 class="fw-bold mb-1">Selamat Datang, Admin! </h2>
                <p class="mb-0 opacity-75">Sistem berjalan lancar. Berikut ringkasan hari ini.</p>
            </div>
            
            <div style="z-index: 1;">
                <button class="theme-toggle-btn border-0 shadow-lg bg-white bg-opacity-25 text-white" onclick="toggleTheme()" title="Ganti Mode Tampilan">
                    <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
                </button>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3 animate-up delay-1">
                <div class="card stat-card shadow-sm h-100 border-0 rounded-4">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Verifikasi Pending</p>
                            <h2 class="fw-bold mb-0 text-primary-custom counter-value" data-target="<?php echo $count_pending; ?>">0</h2>
                        </div>
                        <div class="icon-box p-3 rounded-circle"><i class="bi bi-person-exclamation fs-3"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3 animate-up delay-2">
                <div class="card stat-card shadow-sm h-100 border-0 rounded-4">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Penghuni Aktif</p>
                            <h2 class="fw-bold mb-0 text-primary-custom counter-value" data-target="<?php echo $count_active; ?>">0</h2>
                        </div>
                        <div class="icon-box p-3 rounded-circle"><i class="bi bi-people-fill fs-3"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3 animate-up delay-3">
                <div class="card stat-card shadow-sm h-100 border-0 rounded-4">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Slot Tersedia</p>
                            <h2 class="fw-bold mb-0 text-primary-custom counter-value" data-target="<?php echo $sisa_kamar; ?>">0</h2>
                        </div>
                        <div class="icon-box p-3 rounded-circle"><i class="bi bi-door-open fs-3"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3 animate-up delay-4">
                <div class="card stat-card shadow-sm h-100 border-0 rounded-4">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Laporan Masuk</p>
                            <h2 class="fw-bold mb-0 text-primary-custom counter-value" data-target="<?php echo $count_laporan; ?>">0</h2>
                        </div>
                        <div class="icon-box p-3 rounded-circle"><i class="bi bi-tools fs-3"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 animate-up delay-4">
            
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-card border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary-custom"><i class="bi bi-person-lines-fill me-2"></i>Pendaftar Terbaru</h5>
                        <a href="verifikasi_pendaftar.php" class="btn btn-sm btn-primary-custom rounded-pill px-3 shadow-sm">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="ps-4 py-3">Nama Lengkap</th>
                                        <th>Asal Daerah</th>
                                        <th>Jurusan</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($res_newest) > 0): ?>
                                        <?php while($p = mysqli_fetch_assoc($res_newest)): ?>
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary-custom text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width:40px; height:40px;">
                                                        <?php echo strtoupper(substr($p['nama_lengkap'], 0, 1)); ?>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold text-dark"><?php echo $p['nama_lengkap']; ?></h6>
                                                        <small class="text-muted"><?php echo date('d M Y', strtotime($p['tanggal_daftar'])); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center text-muted small">
                                                    <i class="bi bi-geo-alt me-1"></i><?php echo substr($p['alamat_asal'], 0, 20) . '...'; ?>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-light text-dark border"><?php echo $p['jurusan']; ?></span></td>
                                            <td class="text-end pe-4">
                                                <a href="detail_pendaftar.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">Cek</a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <i class="bi bi-inbox fs-1 text-muted opacity-25 d-block mb-2"></i>
                                                <span class="text-muted">Tidak ada pendaftar baru.</span>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-card border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary-custom"><i class="bi bi-calendar-check me-2"></i>Survei Terdekat</h5>
                        <a href="kelola_survei.php" class="btn btn-sm btn-primary-custom rounded-pill px-3 shadow-sm">
                            Semua
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if(mysqli_num_rows($res_survei) > 0): ?>
                                <?php while($survei = mysqli_fetch_assoc($res_survei)): 
                                    $tgl_survei = date('Y-m-d', strtotime($survei['tanggal_survei']));
                                    $hari_ini = date('Y-m-d');
                                    $is_today = ($tgl_survei == $hari_ini);
                                    
                                    $badge_class = $is_today ? 'bg-danger text-white border-0' : 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25';
                                    $teks_tanggal = $is_today ? 'Hari Ini' : date('d M Y', strtotime($survei['tanggal_survei']));
                                ?>
                                    <div class="list-group-item p-3 border-bottom border-light <?php echo $is_today ? 'bg-danger bg-opacity-10' : ''; ?>">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold text-dark"><?php echo $survei['nama_lengkap']; ?></h6>
                                            <span class="badge rounded-pill <?php echo $badge_class; ?>">
                                                <?php echo $teks_tanggal; ?>
                                            </span>
                                        </div>
                                        <div class="small text-muted mb-2">
                                            <i class="bi bi-clock me-1"></i> Jam <?php echo date('H:i', strtotime($survei['jam_survei'])); ?>
                                            <span class="mx-1">•</span>
                                            <i class="bi bi-telephone me-1"></i> <?php echo $survei['no_hp']; ?>
                                        </div>
                                        <?php if(!empty($survei['pesan'])): ?>
                                            <div class="small bg-white border p-2 rounded text-muted fst-italic">
                                                "<?php echo $survei['pesan']; ?>"
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-1 opacity-25 d-block mb-3"></i>
                                    <span class="text-muted">Tidak ada jadwal survei terdekat.</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <script src="../../assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const counters = document.querySelectorAll('.counter-value');
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const duration = 1500; 
                const increment = target / (duration / 16);
                let current = 0;
                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.innerText = Math.ceil(current);
                        requestAnimationFrame(updateCounter);
                    } else { counter.innerText = target; }
                };
                if(target > 0) updateCounter();
            });
        });
    </script>
</body>
</html>