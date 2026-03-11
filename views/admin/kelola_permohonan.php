<?php 
include '../../functions/admin_auth.php'; 
include '../../config/database.php';

// --- 1. LOGIKA UPDATE STATUS ---
if (isset($_POST['update_status'])) {
    $id_izin = $_POST['id_izin'];
    $status_baru = $_POST['status_baru']; // isinya 'approved' atau 'rejected'
    
    // Ubah tabel menjadi 'permohonan'
    $query = "UPDATE permohonan SET status = '$status_baru' WHERE id = '$id_izin'";
    if (mysqli_query($conn, $query)) {
        header("Location: kelola_permohonan.php?msg=updated");
        exit();
    }
}

// --- 2. CONFIG PAGINATION & SEARCH ---
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Where Clause
$where_clause = "WHERE 1=1";
if(!empty($search)) {
    // Ubah pi.jenis_izin menjadi pi.jenis_permohonan
    $where_clause .= " AND (p.nama_lengkap LIKE '%$search%' OR k.nomor_kamar LIKE '%$search%' OR pi.jenis_permohonan LIKE '%$search%')";
}

// --- 3. QUERY DATA ---
// Ubah tabel dari permohonan_izin menjadi permohonan
$query_main = "SELECT pi.*, p.nama_lengkap, p.no_hp, k.nomor_kamar 
               FROM permohonan pi
               JOIN pendaftaran p ON pi.id_pendaftar = p.id
               LEFT JOIN kamar k ON p.id_kamar = k.id
               $where_clause
               ORDER BY field(pi.status, 'pending', 'approved', 'rejected'), pi.tanggal_permohonan DESC
               LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query_main);

// Hitung Total Data (Ubah tabel juga di sini)
$q_count = "SELECT COUNT(*) as total FROM permohonan pi JOIN pendaftaran p ON pi.id_pendaftar = p.id LEFT JOIN kamar k ON p.id_kamar = k.id $where_clause";
$res_count = mysqli_query($conn, $q_count);
$total_data = mysqli_fetch_assoc($res_count)['total'];
$total_pages = ceil($total_data / $limit);
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Permohonan Administrasi - SIMA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { --color-primary: #5A7863; }
        .dropdown-item:active { background-color: var(--color-primary); color: white; }
    </style>
</head>
<body>

    <?php include '../../layouts/sidebar_admin.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body" style="min-height: 100vh;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-primary-custom">
                    <i class="bi bi-envelope-paper-fill me-2"></i>Permohonan Administrasi
                </h2>
                <p class="text-muted small mt-1">Daftar pengajuan pindah kamar atau perpanjangan sewa.</p>
            </div>
            <button class="btn btn-sm btn-outline-secondary rounded-circle p-2" onclick="toggleTheme()">
                <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
            </button>
        </div>

        <div class="card shadow-sm border-0 mb-4 rounded-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama, kamar, atau jenis permohonan..." value="<?php echo htmlspecialchars($search); ?>">
                            <button class="btn btn-primary-custom" type="submit">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-card border-bottom py-3">
                <h5 class="fw-bold mb-0 text-primary-custom">Daftar Pengajuan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Penghuni</th>
                                <th>Jenis Permohonan</th>
                                <th>Keterangan</th>
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
                                            <div class="avatar-sm bg-primary-custom text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width:40px; height:40px;">
                                                <?php echo strtoupper(substr($row['nama_lengkap'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo $row['nama_lengkap']; ?></div>
                                                <small class="text-muted">
                                                    <?php echo $row['nomor_kamar'] ? 'Kamar '.$row['nomor_kamar'] : 'Belum Ada Kamar'; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border mb-1">
                                            <?php 
                                            if($row['jenis_permohonan'] == 'pindah_kamar') echo 'Pindah Kamar';
                                            else echo 'Perpanjangan Sewa';
                                            ?>
                                        </span>
                                        <div class="small text-muted">
                                            <?php echo date('d M Y', strtotime($row['tanggal_permohonan'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-muted fst-italic text-truncate" style="max-width: 250px;" title="<?php echo $row['keterangan']; ?>">
                                            "<?php echo $row['keterangan']; ?>"
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] == 'approved'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">Disetujui</span>
                                        <?php elseif ($row['status'] == 'rejected'): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3">Ditolak</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border rounded-pill" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical text-muted"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                <li><h6 class="dropdown-header">Aksi</h6></li>
                                                
                                                <li>
                                                    <a class="dropdown-item" href="https://wa.me/<?php echo $row['no_hp']; ?>" target="_blank">
                                                        <i class="bi bi-whatsapp me-2 text-success"></i> Hubungi via WA
                                                    </a>
                                                </li>
                                                
                                                <?php if($row['status'] == 'pending'): ?>
                                                <li><hr class="dropdown-divider"></li>
                                                
                                                <li>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="id_izin" value="<?php echo $row['id']; ?>">
                                                        <input type="hidden" name="status_baru" value="approved">
                                                        <button type="submit" name="update_status" class="dropdown-item">
                                                            <i class="bi bi-check-circle me-2 text-primary"></i> Setujui Pengajuan
                                                        </button>
                                                    </form>
                                                </li>
                                                
                                                <li>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="id_izin" value="<?php echo $row['id']; ?>">
                                                        <input type="hidden" name="status_baru" value="rejected">
                                                        <button type="submit" name="update_status" class="dropdown-item text-danger">
                                                            <i class="bi bi-x-circle me-2"></i> Tolak Pengajuan
                                                        </button>
                                                    </form>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>

                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                        Belum ada data permohonan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white py-3">
                <nav>
                    <ul class="pagination pagination-sm justify-content-end mb-0">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page-1; ?>">Sebelumnya</a>
                        </li>
                        <?php for($i=1; $i<=$total_pages; $i++): ?>
                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                <a class="page-link <?php echo ($page == $i) ? 'bg-success border-success' : 'text-success'; ?>" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page+1; ?>">Selanjutnya</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

    </main>

    <script src="../../assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('msg') === 'updated'){
            Swal.fire({
                icon: 'success',
                title: 'Status Diperbarui',
                text: 'Status permohonan berhasil diubah.',
                timer: 2000,
                confirmButtonColor: '#5A7863'
            });
        }
    </script>
</body>
</html>