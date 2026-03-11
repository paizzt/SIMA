<?php 
include '../../functions/penghuni_auth.php'; 
include '../../config/database.php';

$id_user = $_SESSION['user_id'];

// 1. AMBIL DATA PENGHUNI + INFO KAMAR (JOIN)
$query = "SELECT p.*, k.nomor_kamar, k.lantai, k.kapasitas 
          FROM pendaftaran p 
          LEFT JOIN kamar k ON p.id_kamar = k.id 
          WHERE p.id = '$id_user'";
$result = mysqli_query($conn, $query);
$me = mysqli_fetch_assoc($result);

// 2. AMBIL DATA TEMAN SEKAMAR (ROOMMATES)
$id_kamar_saya = $me['id_kamar'];
$roommates = [];
if ($id_kamar_saya) {
    $q_roommate = "SELECT nama_lengkap, jurusan, no_hp FROM pendaftaran 
                   WHERE id_kamar = '$id_kamar_saya' 
                   AND id != '$id_user' 
                   AND status_verifikasi = 'diterima'";
    $res_roommate = mysqli_query($conn, $q_roommate);
    while($r = mysqli_fetch_assoc($res_roommate)){
        $roommates[] = $r;
    }
}

// 3. CEK STATUS PEMBAYARAN BULAN INI
$bulan_ini = date('Y-m'); 
$q_bayar = "SELECT status FROM pembayaran WHERE id_pendaftar = '$id_user' AND tanggal_bayar LIKE '$bulan_ini%'";
$res_bayar = mysqli_query($conn, $q_bayar);

$status_bayar = 'belum_bayar';
if($res_bayar && mysqli_num_rows($res_bayar) > 0){
    $status_bayar = mysqli_fetch_assoc($res_bayar)['status'];
}

// Teks Status Pembayaran untuk Tampilan
$txt_bayar = "Belum Bayar";
if($status_bayar == 'verified') {
    $txt_bayar = "Lunas";
} elseif($status_bayar == 'pending') {
    $txt_bayar = "Diproses";
}

// 4. HITUNG LAPORAN AKTIF
$q_lapor = "SELECT COUNT(*) as total FROM laporan_kerusakan WHERE id_pendaftar = '$id_user' AND status != 'done'";
$res_lapor = mysqli_query($conn, $q_lapor);
$lapor_aktif = ($res_lapor) ? mysqli_fetch_assoc($res_lapor)['total'] : 0;
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penghuni - SIMA</title>
    <link rel="icon" type="image/png" href="../../assets/img/logo1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* CSS KHUSUS DASHBOARD (SAMA DENGAN ADMIN) */
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

        /* Card Styling Konsisten (Hijau) */
        .stat-card {
            transition: all 0.3s ease;
            border-left: 5px solid var(--color-primary) !important;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(90, 120, 99, 0.15) !important;
        }
        
        /* Icon Box Styling */
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

    <?php include '../../layouts/sidebar_penghuni.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body" style="min-height: 100vh;">
        
        <div class="welcome-banner text-white p-4 rounded-4 mb-4 shadow-sm animate-up d-flex align-items-center justify-content-between">
            <div style="z-index: 1;">
                <h2 class="fw-bold mb-1">Halo, <?php echo explode(' ', $me['nama_lengkap'])[0]; ?>! </h2>
                <p class="mb-0 opacity-75">Selamat datang di rumah keduamu.</p>
            </div>
            
            <div style="z-index: 1;">
                <button class="theme-toggle-btn border-0 shadow-lg bg-white bg-opacity-25 text-white" onclick="toggleTheme()" title="Ganti Mode Tampilan">
                    <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
                </button>
            </div>
        </div>

        <div class="row g-4 mb-4">
            
            <div class="col-12 col-md-4 animate-up delay-1">
                <div class="card stat-card shadow-sm h-100 border-0 rounded-4">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Kamar Saya</p>
                            <?php if ($me['nomor_kamar']): ?>
                                <h2 class="fw-bold mb-0 text-primary-custom">No. <?php echo $me['nomor_kamar']; ?></h2>
                                <small class="text-muted">Lantai <?php echo $me['lantai']; ?></small>
                            <?php else: ?>
                                <h4 class="fw-bold mb-0 text-primary-custom">Pending</h4>
                                <small class="text-muted">Menunggu Admin</small>
                            <?php endif; ?>
                        </div>
                        <div class="icon-box p-3 rounded-circle">
                            <i class="bi bi-door-open-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 animate-up delay-2">
                <div class="card stat-card shadow-sm h-100 border-0 rounded-4">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Tagihan Bulan Ini</p>
                            <h2 class="fw-bold mb-0 text-primary-custom"><?php echo $txt_bayar; ?></h2>
                            <a href="pembayaran.php" class="text-decoration-none small text-muted stretched-link">Lihat Riwayat &rarr;</a>
                        </div>
                        <div class="icon-box p-3 rounded-circle">
                            <i class="bi bi-wallet2 fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 animate-up delay-3">
                <div class="card stat-card shadow-sm h-100 border-0 rounded-4">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Laporan Aktif</p>
                            <h2 class="fw-bold mb-0 text-primary-custom counter-value" data-target="<?php echo $lapor_aktif; ?>">0</h2>
                            <a href="lapor_kerusakan.php" class="text-decoration-none small text-muted stretched-link">Buat Laporan &rarr;</a>
                        </div>
                        <div class="icon-box p-3 rounded-circle">
                            <i class="bi bi-tools fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 animate-up delay-3">
            
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-card border-bottom py-3">
                        <h6 class="fw-bold mb-0 text-primary-custom"><i class="bi bi-people me-2"></i>Teman Sekamar</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php if (!empty($roommates)): ?>
                                <?php foreach($roommates as $rm): ?>
                                <li class="list-group-item border-light d-flex align-items-center p-3">
                                    <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width:45px; height:45px;">
                                        <?php echo strtoupper(substr($rm['nama_lengkap'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark"><?php echo $rm['nama_lengkap']; ?></h6>
                                        <small class="text-muted"><?php echo $rm['jurusan']; ?></small>
                                    </div>
                                    <a href="https://wa.me/<?php echo $rm['no_hp']; ?>" target="_blank" class="btn btn-sm btn-light text-success rounded-circle ms-auto shadow-sm">
                                        <i class="bi bi-whatsapp fs-5"></i>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="list-group-item text-center py-5">
                                    <i class="bi bi-emoji-smile fs-1 text-muted opacity-25 mb-2 d-block"></i>
                                    <span class="text-muted small">Belum ada teman sekamar.</span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-card border-bottom py-3">
                        <h6 class="fw-bold mb-0 text-primary-custom"><i class="bi bi-shield-exclamation me-2"></i>Aturan Penting</h6>
                    </div>
                    <div class="card-body">
                        <ul class="ps-3 small text-muted mb-0" style="line-height: 2;">
                            <li>Jam malam berlaku pukul <strong class="text-dark">22.00 WITA</strong>.</li>
                            <li>Dilarang membawa tamu menginap di kamar.</li>
                            <li>Dilarang merokok di dalam gedung asrama.</li>
                            <li>Wajib menjaga kebersihan fasilitas bersama.</li>
                        </ul>
                        <div class="mt-4 pt-3 border-top text-center">
                            <span class="badge bg-light text-muted border">Patuhi demi kenyamanan bersama</span>
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
                const duration = 1000; 
                const increment = target / (duration / 16);
                let current = 0;
                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.innerText = Math.ceil(current);
                        requestAnimationFrame(updateCounter);
                    } else { counter.innerText = target; }
                };
                updateCounter();
            });
        });
    </script>
</body>
</html>