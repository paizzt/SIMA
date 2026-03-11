<?php 
include '../../functions/admin_auth.php'; 
include '../../config/database.php';

// --- 1. LOGIKA PINDAH KAMAR ---
if (isset($_POST['pindah_kamar'])) {
    $id_penghuni = $_POST['id_penghuni'];
    $id_kamar_baru = $_POST['id_kamar_baru'];
    
    $query_update = "UPDATE pendaftaran SET id_kamar = '$id_kamar_baru' WHERE id = '$id_penghuni'";
    
    if (mysqli_query($conn, $query_update)) {
        header("Location: kelola_penghuni.php?msg=moved");
        exit();
    } else {
        echo "<script>alert('Gagal memindahkan: " . mysqli_error($conn) . "');</script>";
    }
}

// --- 2. LOGIKA HAPUS PENGHUNI ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $q_del = "UPDATE pendaftaran SET status_verifikasi = 'ditolak', id_kamar = NULL WHERE id = '$id'";
    if(mysqli_query($conn, $q_del)){
        header("Location: kelola_penghuni.php?msg=deleted");
        exit();
    }
}

// --- 3. CONFIG PAGINATION & PENCARIAN ---
// Ambil parameter halaman dan limit
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Buat URL parameter untuk pagination agar search tidak hilang
$url_params = "&search=" . urlencode($search) . "&limit=" . $limit;

// Where Clause
$where_clause = "WHERE p.status_verifikasi = 'diterima'";
if(!empty($search)) {
    $where_clause .= " AND (p.nama_lengkap LIKE '%$search%' OR p.jurusan LIKE '%$search%' OR k.nomor_kamar LIKE '%$search%')";
}

// --- 4. HITUNG TOTAL DATA (Untuk Pagination) ---
$query_count = "SELECT COUNT(*) as total 
                FROM pendaftaran p 
                LEFT JOIN kamar k ON p.id_kamar = k.id 
                $where_clause";
$res_count = mysqli_query($conn, $query_count);
$total_data = mysqli_fetch_assoc($res_count)['total'];
$total_pages = ceil($total_data / $limit);

// --- 5. QUERY DATA UTAMA (Dengan Limit) ---
$query = "SELECT p.*, k.nomor_kamar, k.lantai 
          FROM pendaftaran p 
          LEFT JOIN kamar k ON p.id_kamar = k.id 
          $where_clause
          ORDER BY k.nomor_kamar ASC, p.nama_lengkap ASC
          LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);

// --- 6. AMBIL DATA KAMAR KOSONG (Untuk Dropdown Modal) ---
$q_kamar = "SELECT k.*, 
            (SELECT COUNT(*) FROM pendaftaran p WHERE p.id_kamar = k.id AND p.status_verifikasi = 'diterima') as terisi 
            FROM kamar k 
            HAVING terisi < k.kapasitas 
            ORDER BY k.lantai ASC, k.nomor_kamar ASC";
$res_kamar = mysqli_query($conn, $q_kamar);
$kamar_tersedia = [];
while($row_k = mysqli_fetch_assoc($res_kamar)){
    $kamar_tersedia[] = $row_k;
}
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Penghuni - SIMA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { --color-primary: #5A7863; }
        
        /* CSS KHUSUS PRINT */
        @media print {
            body * { visibility: hidden; }
            #tabel-area, #tabel-area * { visibility: visible; }
            #tabel-area { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
            .card { border: none !important; box-shadow: none !important; }
        }

        /* Hover Dropdown Item */
        .dropdown-item:active, .dropdown-item:focus {
            background-color: var(--color-primary);
            color: white !important;
        }
        .dropdown-item:active i, .dropdown-item:focus i {
            color: white !important;
        }
    </style>
</head>
<body>

    <div class="no-print">
        <?php include '../../layouts/sidebar_admin.php'; ?>
    </div>

    <main class="content-wrapper px-md-4 py-4 bg-body" style="min-height: 100vh;">
        
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <div>
                <h2 class="fw-bold mb-0 text-primary-custom">
                    <i class="bi bi-people-fill me-2"></i>Kelola Penghuni
                </h2>
                <p class="text-muted small mt-1">Total <strong><?php echo $total_data; ?></strong> penghuni aktif.</p>
            </div>
            <button class="theme-toggle-btn border-0 shadow-sm" onclick="toggleTheme()">
                <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
            </button>
        </div>

        <div class="card shadow-sm border-0 mb-4 rounded-4 no-print">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-center">
                    <input type="hidden" name="limit" value="<?php echo $limit; ?>">
                    
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama / kamar..." value="<?php echo htmlspecialchars($search); ?>">
                            <button class="btn btn-primary-custom" type="submit">Cari</button>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <?php if($search): ?>
                            <a href="kelola_penghuni.php" class="btn btn-outline-secondary me-2"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                        <?php endif; ?>
                        
                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle shadow-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-download me-1"></i> Export
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><button type="button" class="dropdown-item" onclick="exportExcel('tabelPenghuni', 'Data_Penghuni_Page_<?php echo $page; ?>')">Excel (Halaman Ini)</button></li>
                                <li><button type="button" class="dropdown-item" onclick="window.print()">PDF (Cetak)</button></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="tabel-area" class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-card border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-primary-custom">Daftar Penghuni</h5>
                <small class="text-muted d-none d-print-block">Hal <?php echo $page; ?> dari <?php echo $total_pages; ?></small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tabelPenghuni">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Nama Lengkap</th>
                                <th>Kamar</th>
                                <th>Jurusan</th>
                                <th>No. HP</th>
                                <th class="text-end pe-4 no-print">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-custom text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm no-print" style="width:35px; height:35px;">
                                                <?php echo strtoupper(substr($row['nama_lengkap'], 0, 1)); ?>
                                            </div>
                                            <div class="fw-bold text-dark"><?php echo $row['nama_lengkap']; ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($row['nomor_kamar']): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 rounded">
                                                Kamar <?php echo $row['nomor_kamar']; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Belum Ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $row['jurusan']; ?></td>
                                    <td><?php echo $row['no_hp']; ?></td>
                                    
                                    <td class="text-end pe-4 no-print">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm rounded-circle shadow-sm border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical text-muted"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                                <li><h6 class="dropdown-header">Menu Aksi</h6></li>
                                                
                                                <li>
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalPindah<?php echo $row['id']; ?>">
                                                        <i class="bi bi-arrow-left-right me-2 text-primary"></i> Pindah Kamar
                                                    </button>
                                                </li>
                                                
                                                <li>
                                                    <a class="dropdown-item" href="detail_penghuni.php?id=<?php echo $row['id']; ?>">
                                                        <i class="bi bi-person-lines-fill me-2 text-info"></i> Lihat Detail
                                                    </a>
                                                </li>
                                                
                                                <li><hr class="dropdown-divider"></li>
                                                
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger" onclick="konfirmasiHapus(<?php echo $row['id']; ?>)">
                                                        <i class="bi bi-trash me-2"></i> Keluarkan
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="modal fade text-start" id="modalPindah<?php echo $row['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary-custom text-white border-0">
                                                        <h5 class="modal-title fw-bold">Pindah Kamar</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <form method="POST">
                                                            <input type="hidden" name="id_penghuni" value="<?php echo $row['id']; ?>">
                                                            
                                                            <div class="text-center mb-4">
                                                                <h6 class="fw-bold"><?php echo $row['nama_lengkap']; ?></h6>
                                                                <p class="text-muted small">Kamar Saat Ini: <?php echo $row['nomor_kamar'] ? $row['nomor_kamar'] : '-'; ?></p>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold small text-muted">Pilih Kamar Baru</label>
                                                                <select name="id_kamar_baru" class="form-select" required>
                                                                    <option value="" selected disabled>-- Pilih Kamar --</option>
                                                                    <?php foreach($kamar_tersedia as $k): ?>
                                                                        <?php $sisa = $k['kapasitas'] - $k['terisi']; ?>
                                                                        <option value="<?php echo $k['id']; ?>">
                                                                            Kamar <?php echo $k['nomor_kamar']; ?> (Lantai <?php echo $k['lantai']; ?>) - Sisa <?php echo $sisa; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>

                                                            <div class="d-grid mt-4">
                                                                <button type="submit" name="pindah_kamar" class="btn btn-primary-custom">
                                                                    Simpan Perubahan
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Data tidak ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white py-3 no-print d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                
                <div class="d-flex align-items-center">
                    <span class="text-muted small me-2">Tampilkan:</span>
                    <form method="GET" class="d-inline-block">
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                        <input type="hidden" name="page" value="1"> <select name="limit" class="form-select form-select-sm" style="width: 80px;" onchange="this.form.submit()">
                            <option value="10" <?php if($limit == 10) echo 'selected'; ?>>10</option>
                            <option value="20" <?php if($limit == 20) echo 'selected'; ?>>20</option>
                            <option value="50" <?php if($limit == 50) echo 'selected'; ?>>50</option>
                            <option value="100" <?php if($limit == 100) echo 'selected'; ?>>100</option>
                        </select>
                    </form>
                    <span class="text-muted small ms-2">dari <?php echo $total_data; ?> data</span>
                </div>

                <?php if($total_pages > 1): ?>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page-1; ?><?php echo $url_params; ?>">Sebelumnya</a>
                        </li>

                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i == 1 || $i == $total_pages || ($i >= $page - 2 && $i <= $page + 2)): ?>
                                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                    <a class="page-link <?php echo ($page == $i) ? 'bg-success border-success' : 'text-success'; ?>" 
                                       href="?page=<?php echo $i; ?><?php echo $url_params; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php elseif ($i == $page - 3 || $i == $page + 3): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page+1; ?><?php echo $url_params; ?>">Selanjutnya</a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>

    </main>

    <script src="../../assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('msg') === 'moved'){
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Penghuni berhasil dipindahkan.', confirmButtonColor: '#5A7863', timer: 2000 });
        } else if(urlParams.get('msg') === 'deleted'){
            Swal.fire({ icon: 'success', title: 'Dihapus', text: 'Penghuni telah dikeluarkan.', confirmButtonColor: '#5A7863' });
        }

        function konfirmasiHapus(id) {
            Swal.fire({
                title: 'Keluarkan Penghuni?',
                text: "Akses login mereka akan dicabut dan kamar akan dikosongkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Keluarkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `kelola_penghuni.php?hapus=${id}`;
                }
            })
        }

        function exportExcel(tableID, filename = ''){
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableClone = tableSelect.cloneNode(true);
            var rows = tableClone.rows;
            for (var i = 0; i < rows.length; i++) {
                if (rows[i].cells.length > 0) rows[i].deleteCell(-1); // Hapus kolom aksi
            }
            var tableHTML = tableClone.outerHTML.replace(/ /g, '%20');
            filename = filename?filename+'.xls':'excel_data.xls';
            downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);
            if(navigator.msSaveOrOpenBlob){
                var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
                navigator.msSaveOrOpenBlob( blob, filename);
            }else{
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
                downloadLink.download = filename;
                downloadLink.click();
            }
        }
    </script>
</body>
</html>