<?php 
include '../../functions/admin_auth.php'; 
include '../../config/database.php';

// --- LOGIKA UPDATE STATUS ---
if (isset($_POST['update_status'])) {
    $id_laporan = $_POST['id_laporan'];
    $status_baru = $_POST['status_baru']; // Akan berisi 'diproses' atau 'selesai'
    
    $query_update = "UPDATE laporan_kerusakan SET status = '$status_baru' WHERE id = '$id_laporan'";
    
    if (mysqli_query($conn, $query_update)) {
        header("Location: laporan_kerusakan.php?msg=updated");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// --- LOGIKA AMBIL DATA ---
$query = "SELECT l.*, p.nama_lengkap, k.nomor_kamar 
          FROM laporan_kerusakan l
          JOIN pendaftaran p ON l.id_pendaftar = p.id
          LEFT JOIN kamar k ON p.id_kamar = k.id
          ORDER BY FIELD(l.status, 'baru', 'diproses', 'selesai'), l.tanggal_lapor DESC";
$result = mysqli_query($conn, $query);

// Hitung Statistik (Disesuaikan dengan DB: 'baru' dan 'diproses')
$q_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_kerusakan WHERE status='baru'");
$total_pending = mysqli_fetch_assoc($q_pending)['total'];

$q_process = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_kerusakan WHERE status='diproses'");
$total_process = mysqli_fetch_assoc($q_process)['total'];
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kerusakan - SIMA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --color-primary: #5A7863;
        }
        
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
    </style>
</head>
<body>

    <?php include '../../layouts/sidebar_admin.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body" style="min-height: 100vh;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-primary-custom">
                    <i class="bi bi-tools me-2"></i>Laporan Kerusakan
                </h2>
                <p class="text-muted small mt-1">Kelola keluhan fasilitas dan perbaikan aset asrama.</p>
            </div>
            
            <div class="d-flex align-items-center">
                <button class="theme-toggle-btn border-0 shadow-sm" onclick="toggleTheme()" title="Ganti Mode Tampilan">
                    <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
                </button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            
            <div class="col-md-6 col-xl-3">
                <div class="card stat-card shadow-sm h-100 border-0 rounded-4">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Perlu Tindakan</small>
                            <h2 class="fw-bold mb-0 text-primary-custom"><?php echo $total_pending; ?></h2>
                        </div>
                        <div class="icon-box p-3 rounded-circle">
                            <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card stat-card shadow-sm h-100 border-0 rounded-4">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Sedang Dikerjakan</small>
                            <h2 class="fw-bold mb-0 text-primary-custom"><?php echo $total_process; ?></h2>
                        </div>
                        <div class="icon-box p-3 rounded-circle">
                            <i class="bi bi-gear-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-card border-bottom py-3">
                <h5 class="fw-bold mb-0 text-primary-custom">Daftar Keluhan Masuk</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Pelapor & Kamar</th>
                                <th>Kerusakan</th>
                                <th>Foto</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width:35px; height:35px;">
                                                <?php echo strtoupper(substr($row['nama_lengkap'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo $row['nama_lengkap']; ?></div>
                                                <small class="text-muted">
                                                    <i class="bi bi-door-closed me-1"></i>Kamar <?php echo $row['nomor_kamar'] ? $row['nomor_kamar'] : '-'; ?>
                                                </small>
                                                <div class="text-muted" style="font-size: 0.75rem;">
                                                    <?php echo date('d M Y', strtotime($row['tanggal_lapor'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="max-width: 250px;">
                                        <div class="fw-medium text-dark"><?php echo $row['judul_laporan']; ?></div>
                                        <small class="text-muted text-truncate d-block">
                                            "<?php echo substr($row['deskripsi'], 0, 50) . '...'; ?>"
                                        </small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" 
                                                onclick="Swal.fire({
                                                    imageUrl: '../../uploads/kerusakan/<?php echo $row['foto_bukti']; ?>', 
                                                    imageAlt: 'Foto Kerusakan',
                                                    title: 'Bukti Foto',
                                                    width: '400px',
                                                    confirmButtonColor: '#5A7863',
                                                    background: '#fff'
                                                })">
                                            <i class="bi bi-image me-1"></i> Lihat
                                        </button>
                                    </td>
                                    <td>
                                        <?php 
                                        // PERBAIKAN: Sesuaikan dengan status database (baru, diproses, selesai)
                                        if($row['status'] == 'baru') 
                                            echo '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill">Baru</span>';
                                        elseif($row['status'] == 'diproses') 
                                            echo '<span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-pill">Diproses</span>';
                                        else 
                                            echo '<span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill">Selesai</span>';
                                        ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Aksi
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                <li><h6 class="dropdown-header">Ubah Status</h6></li>
                                                <li>
                                                    <form method="POST">
                                                        <input type="hidden" name="id_laporan" value="<?php echo $row['id']; ?>">
                                                        <input type="hidden" name="status_baru" value="diproses">
                                                        <button type="submit" name="update_status" class="dropdown-item text-primary">
                                                            <i class="bi bi-tools me-2"></i> Sedang Dikerjakan
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST">
                                                        <input type="hidden" name="id_laporan" value="<?php echo $row['id']; ?>">
                                                        <input type="hidden" name="status_baru" value="selesai">
                                                        <button type="submit" name="update_status" class="dropdown-item text-success">
                                                            <i class="bi bi-check-circle-fill me-2"></i> Tandai Selesai
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-clipboard-check fs-1 d-block mb-3 opacity-25"></i>
                                        Tidak ada laporan kerusakan baru.
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
    
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('msg') === 'updated'){
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({ icon: 'success', title: 'Status laporan diperbarui' });
        }
    </script>
</body>
</html>