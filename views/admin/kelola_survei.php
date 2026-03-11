<?php 
include '../../functions/admin_auth.php'; 
include '../../config/database.php';

// --- LOGIKA QUERY ---
// Ambil semua pendaftar yang memiliki tanggal survei
// Urutkan dari tanggal yang paling dekat (ASC)
$query = "SELECT * FROM pendaftaran 
          WHERE tanggal_survei IS NOT NULL 
          AND tanggal_survei != '0000-00-00' 
          ORDER BY tanggal_survei ASC";
$result = mysqli_query($conn, $query);

// Hitung Statistik
$hari_ini = date('Y-m-d');

// Query hitung survei hari ini
$q_today = "SELECT COUNT(*) as total FROM pendaftaran WHERE tanggal_survei = '$hari_ini'";
$res_today = mysqli_query($conn, $q_today);
$count_today = mysqli_fetch_assoc($res_today)['total'];

// Query hitung survei akan datang
$q_upcoming = "SELECT COUNT(*) as total FROM pendaftaran WHERE tanggal_survei > '$hari_ini'";
$res_upcoming = mysqli_query($conn, $q_upcoming);
$count_upcoming = mysqli_fetch_assoc($res_upcoming)['total'];
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Survei - SIMA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { --color-primary: #5A7863; }
        
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
        }
    </style>
</head>
<body>

    <?php include '../../layouts/sidebar_admin.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body" style="min-height: 100vh;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-primary-custom">
                    <i class="bi bi-calendar-check me-2"></i>Jadwal Survei
                </h2>
                <p class="text-muted small mt-1">Daftar calon penghuni yang berencana meninjau lokasi.</p>
            </div>
            
            <div class="d-flex align-items-center">
                <button class="theme-toggle-btn border-0 shadow-sm" onclick="toggleTheme()" title="Ganti Mode Tampilan">
                    <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
                </button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card stat-card shadow-sm h-100 border-0 rounded-4">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Survei Hari Ini</small>
                            <h2 class="fw-bold mb-0 text-primary-custom"><?php echo $count_today; ?></h2>
                        </div>
                        <div class="icon-box p-3 rounded-circle">
                            <i class="bi bi-calendar-event fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card stat-card shadow-sm h-100 border-0 rounded-4">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Akan Datang</small>
                            <h2 class="fw-bold mb-0 text-primary-custom"><?php echo $count_upcoming; ?></h2>
                        </div>
                        <div class="icon-box p-3 rounded-circle">
                            <i class="bi bi-calendar-plus fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-card border-bottom py-3">
                <h5 class="fw-bold mb-0 text-primary-custom">Daftar Kunjungan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Calon Penghuni</th>
                                <th>Rencana Tanggal</th>
                                <th>Status Jadwal</th>
                                <th>Kontak</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <?php 
                                        // Logic Status Waktu
                                        $tgl_survei = $row['tanggal_survei'];
                                        $badge_class = "";
                                        $status_text = "";
                                        
                                        if ($tgl_survei == $hari_ini) {
                                            $badge_class = "bg-success bg-opacity-10 text-success border border-success";
                                            $status_text = "HARI INI";
                                        } elseif ($tgl_survei > $hari_ini) {
                                            $badge_class = "bg-primary bg-opacity-10 text-primary border border-primary";
                                            // Hitung selisih hari
                                            $diff = (strtotime($tgl_survei) - strtotime($hari_ini)) / 60 / 60 / 24;
                                            $status_text = $diff . " Hari Lagi";
                                        } else {
                                            $badge_class = "bg-secondary bg-opacity-10 text-secondary border border-secondary";
                                            $status_text = "Sudah Lewat";
                                        }
                                    ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width:40px; height:40px;">
                                                <?php echo strtoupper(substr($row['nama_lengkap'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo $row['nama_lengkap']; ?></div>
                                                <small class="text-muted"><?php echo $row['jurusan']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark"><?php echo date('d F Y', strtotime($tgl_survei)); ?></div>
                                        <small class="text-muted">
                                            <?php echo date('l', strtotime($tgl_survei)); // Menampilkan nama hari ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-3 py-2 <?php echo $badge_class; ?>">
                                            <?php echo $status_text; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="https://wa.me/<?php echo $row['no_hp']; ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                            <i class="bi bi-whatsapp me-1"></i> Hubungi
                                        </a>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="detail_pendaftar.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light border rounded-pill" title="Lihat Detail Pendaftar">
                                            <i class="bi bi-person-lines-fill"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-calendar-x fs-1 d-block mb-3 opacity-25"></i>
                                        Tidak ada jadwal survei dalam waktu dekat.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <script src="../../assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>