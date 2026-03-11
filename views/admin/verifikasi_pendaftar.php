<?php 
include '../../functions/admin_auth.php'; 
include '../../config/database.php';

// --- CONFIG PAGINATION & SEARCH ---
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// URL Parameters
$url_params = "&search=" . urlencode($search);

// Where Clause (Hanya status Pending)
$where_clause = "WHERE status_verifikasi = 'pending'";
if(!empty($search)) {
    $where_clause .= " AND (nama_lengkap LIKE '%$search%' OR jurusan LIKE '%$search%' OR alamat_asal LIKE '%$search%')";
}

// Hitung Total Data
$query_count = "SELECT COUNT(*) as total FROM pendaftaran $where_clause";
$res_count = mysqli_query($conn, $query_count);
$total_data = mysqli_fetch_assoc($res_count)['total'];
$total_pages = ceil($total_data / $limit);

// Query Data Utama
$query = "SELECT * FROM pendaftaran 
          $where_clause
          ORDER BY tanggal_daftar DESC
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pendaftar - SIMA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { --color-primary: #5A7863; }
        .dropdown-item:active, .dropdown-item:focus {
            background-color: var(--color-primary); color: white !important;
        }
    </style>
</head>
<body>

    <?php include '../../layouts/sidebar_admin.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body" style="min-height: 100vh;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-primary-custom">
                    <i class="bi bi-person-check-fill me-2"></i>Verifikasi Pendaftar
                </h2>
                <p class="text-muted small mt-1">Terdapat <strong><?php echo $total_data; ?></strong> calon penghuni menunggu persetujuan.</p>
            </div>
            <button class="theme-toggle-btn border-0 shadow-sm" onclick="toggleTheme()">
                <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
            </button>
        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama atau jurusan..." value="<?php echo htmlspecialchars($search); ?>">
                            <button class="btn btn-primary-custom" type="submit">Cari</button>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <?php if($search): ?>
                            <a href="verifikasi_pendaftar.php" class="btn btn-outline-secondary me-2"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-card border-bottom py-3">
                <h5 class="fw-bold mb-0 text-primary-custom">Daftar Permintaan Baru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Calon Penghuni</th>
                                <th>Rencana Survei</th>
                                <th>Jurusan / Asal</th>
                                <th>Kontak</th>
                                <th>Tgl Daftar</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width:40px; height:40px;">
                                                <?php echo strtoupper(substr($row['nama_lengkap'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo $row['nama_lengkap']; ?></div>
                                                <small class="text-muted"><?php echo ($row['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <?php if($row['tanggal_survei']): ?>
                                            <?php 
                                                $tgl = strtotime($row['tanggal_survei']);
                                                $today = strtotime(date('Y-m-d'));
                                                $is_today = ($tgl == $today);
                                            ?>
                                            <?php if($is_today): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success mb-1">Hari Ini</span>
                                            <?php endif; ?>
                                            <div class="fw-medium text-dark"><?php echo date('d M Y', $tgl); ?></div>
                                        <?php else: ?>
                                            <span class="text-muted small fst-italic">- Belum diisi -</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="text-dark fw-medium"><?php echo $row['jurusan']; ?></div>
                                        <small class="text-muted"><?php echo substr($row['alamat_asal'], 0, 15) . '...'; ?></small>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <a href="https://wa.me/<?php echo $row['no_hp']; ?>" target="_blank" class="text-decoration-none text-dark small">
                                                <i class="bi bi-whatsapp text-success me-1"></i> <?php echo $row['no_hp']; ?>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted small"><?php echo date('d/m/Y', strtotime($row['tanggal_daftar'])); ?></span>
                                    </td>
                                    <td class="text-end pe-4">
                                        
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border rounded-pill" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical text-muted"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                <li><h6 class="dropdown-header">Aksi Verifikasi</h6></li>
                                                <li>
                                                    <a class="dropdown-item" href="detail_pendaftar.php?id=<?php echo $row['id']; ?>">
                                                        <i class="bi bi-file-earmark-person me-2 text-primary"></i> Lihat Detail & Proses
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="https://wa.me/<?php echo $row['no_hp']; ?>" target="_blank">
                                                        <i class="bi bi-chat-text me-2 text-success"></i> Hubungi WA
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                        Tidak ada pendaftar baru yang menunggu verifikasi.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white py-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm justify-content-end mb-0">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page-1; ?><?php echo $url_params; ?>">Sebelumnya</a>
                        </li>
                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                <a class="page-link <?php echo ($page == $i) ? 'bg-success border-success' : 'text-success'; ?>" href="?page=<?php echo $i; ?><?php echo $url_params; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page+1; ?><?php echo $url_params; ?>">Selanjutnya</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

    </main>

    <script src="../../assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>