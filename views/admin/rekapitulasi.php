<?php 
include '../../functions/admin_auth.php'; 
include '../../config/database.php';

// --- TANGKAP FILTER TAHUN ---
$tahun_aktif = isset($_GET['tahun']) ? mysqli_real_escape_string($conn, $_GET['tahun']) : date('Y');

// --- 1. QUERY REKAP PENGHUNI PER LANTAI (Sangat Dinamis) ---
// Menghitung langsung dari tabel pendaftaran yang 'diterima', mengabaikan kolom 'terisi' yang mungkin tidak update
$q_penghuni = "
    SELECT 
        k.lantai, 
        COUNT(DISTINCT k.id) as total_kamar, 
        SUM(k.kapasitas) as total_kapasitas,
        (SELECT COUNT(p.id) FROM pendaftaran p JOIN kamar k2 ON p.id_kamar = k2.id WHERE k2.lantai = k.lantai AND p.status_verifikasi = 'diterima') as total_terisi
    FROM kamar k 
    GROUP BY k.lantai 
    ORDER BY k.lantai ASC
";
$res_penghuni = mysqli_query($conn, $q_penghuni);

// --- 2. QUERY REKAP PEMBAYARAN (Dinamis Berdasarkan Tahun Filter) ---
$q_bayar = "
    SELECT 
        DATE_FORMAT(tanggal_bayar, '%m') as bulan_angka,
        DATE_FORMAT(tanggal_bayar, '%M %Y') as bulan_nama, 
        COUNT(id) as total_transaksi, 
        SUM(jumlah_bayar) as total_pemasukan 
    FROM pembayaran 
    WHERE status = 'verified' AND YEAR(tanggal_bayar) = '$tahun_aktif'
    GROUP BY bulan_angka, bulan_nama 
    ORDER BY bulan_angka ASC
";
$res_bayar = mysqli_query($conn, $q_bayar);

// --- 3. QUERY REKAP KERUSAKAN (Dinamis Berdasarkan Tahun Filter) ---
$q_rusak = "
    SELECT status, COUNT(id) as jumlah 
    FROM laporan_kerusakan 
    WHERE YEAR(tanggal_lapor) = '$tahun_aktif'
    GROUP BY status
";
$res_rusak = mysqli_query($conn, $q_rusak);

// Siapkan wadah array default agar tidak error jika kosong
$data_rusak = ['baru' => 0, 'diproses' => 0, 'selesai' => 0];
while($r = mysqli_fetch_assoc($res_rusak)) {
    $data_rusak[$r['status']] = $r['jumlah'];
}
$total_laporan_rusak = array_sum($data_rusak);
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan & Rekapitulasi - SIMA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    
    <style>
        :root { --color-primary: #5A7863; }
        
        /* CSS KHUSUS UNTUK MODE CETAK (PRINT) */
        @media print {
            body { background-color: #fff !important; }
            .no-print, .sidebar, .btn, .theme-toggle-btn { display: none !important; }
            .content-wrapper { margin-left: 0 !important; padding: 0 !important; }
            .card { border: none !important; box-shadow: none !important; }
            .print-header { display: block !important; text-align: center; margin-bottom: 20px; }
            table { width: 100% !important; border-collapse: collapse !important; }
            th, td { border: 1px solid #000 !important; padding: 8px !important; }
            .badge { border: none !important; color: #000 !important; padding: 0 !important; }
        }
        
        .print-header { display: none; }
    </style>
</head>
<body>

    <?php include '../../layouts/sidebar_admin.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body" style="min-height: 100vh;">
        
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <div>
                <h2 class="fw-bold mb-0 text-primary-custom">
                    <i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan & Rekapitulasi
                </h2>
                <p class="text-muted small mt-1">Ringkasan data operasional asrama secara keseluruhan.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary-custom shadow-sm" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Cetak Laporan
                </button>
                <button class="theme-toggle-btn border-0 shadow-sm" onclick="toggleTheme()">
                    <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
                </button>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-4 no-print">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label class="col-form-label fw-bold"><i class="bi bi-calendar-event me-2"></i>Filter Data Tahun:</label>
                    </div>
                    <div class="col-auto">
                        <select name="tahun" class="form-select">
                            <?php 
                            // Membuat pilihan tahun dari 2 tahun lalu hingga tahun ini
                            $thn_sekarang = date('Y');
                            for($t = $thn_sekarang - 2; $t <= $thn_sekarang; $t++): 
                            ?>
                                <option value="<?php echo $t; ?>" <?php echo ($t == $tahun_aktif) ? 'selected' : ''; ?>>
                                    <?php echo $t; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-success">Terapkan Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="print-header">
            <h2>SISTEM INFORMASI MANAJEMEN ASRAMA (SIMA)</h2>
            <h4>Laporan Rekapitulasi - Tahun <?php echo $tahun_aktif; ?></h4>
            <p>Dicetak pada: <?php echo date('d F Y, H:i'); ?></p>
            <hr style="border: 2px solid #000;">
        </div>

        <div class="row g-4">
            
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0 text-primary-custom"><i class="bi bi-building me-2"></i>Rekapitulasi Kapasitas Kamar Saat Ini</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Lantai</th>
                                        <th>Total Kamar</th>
                                        <th>Kapasitas Maksimal</th>
                                        <th>Penghuni Aktif (Terisi)</th>
                                        <th>Sisa Kuota</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $tot_kamar = 0; $tot_kapasitas = 0; $tot_terisi = 0;
                                    while($row = mysqli_fetch_assoc($res_penghuni)): 
                                        $sisa = $row['total_kapasitas'] - $row['total_terisi'];
                                        $tot_kamar += $row['total_kamar'];
                                        $tot_kapasitas += $row['total_kapasitas'];
                                        $tot_terisi += $row['total_terisi'];
                                    ?>
                                    <tr>
                                        <td class="ps-4 fw-bold">Lantai <?php echo $row['lantai']; ?></td>
                                        <td><?php echo $row['total_kamar']; ?> Kamar</td>
                                        <td><?php echo $row['total_kapasitas']; ?> Orang</td>
                                        <td class="text-primary fw-bold"><?php echo $row['total_terisi']; ?> Orang</td>
                                        <td class="<?php echo ($sisa == 0) ? 'text-danger' : 'text-success'; ?> fw-bold">
                                            <?php echo $sisa; ?> Orang
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                                <tfoot class="bg-light fw-bold">
                                    <tr>
                                        <td class="ps-4">TOTAL KESELURUHAN</td>
                                        <td><?php echo $tot_kamar; ?> Kamar</td>
                                        <td><?php echo $tot_kapasitas; ?> Orang</td>
                                        <td class="text-primary"><?php echo $tot_terisi; ?> Orang</td>
                                        <td><?php echo ($tot_kapasitas - $tot_terisi); ?> Orang</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0 text-primary-custom">
                            <i class="bi bi-wallet2 me-2"></i>Pemasukan Tahun <?php echo $tahun_aktif; ?>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Periode Bulan</th>
                                        <th>Transaksi Valid</th>
                                        <th class="text-end pe-4">Total Pemasukan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $grand_total = 0;
                                    if(mysqli_num_rows($res_bayar) > 0):
                                        while($row = mysqli_fetch_assoc($res_bayar)): 
                                            // Format ke Bahasa Indonesia (Opsional)
                                            $bulan_indo = str_replace(
                                                ['January','February','March','April','May','June','July','August','September','October','November','December'],
                                                ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
                                                $row['bulan_nama']
                                            );
                                            $grand_total += $row['total_pemasukan'];
                                    ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?php echo $bulan_indo; ?></td>
                                        <td><?php echo $row['total_transaksi']; ?> Transaksi</td>
                                        <td class="text-end pe-4 text-success fw-bold">Rp <?php echo number_format($row['total_pemasukan'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php endwhile; else: ?>
                                    <tr><td colspan="3" class="text-center py-4 text-muted fst-italic">Belum ada data pemasukan di tahun <?php echo $tahun_aktif; ?>.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot class="bg-light fw-bold">
                                    <tr>
                                        <td colspan="2" class="ps-4 text-end">TOTAL KESELURUHAN:</td>
                                        <td class="text-end pe-4 text-success fs-5">Rp <?php echo number_format($grand_total, 0, ',', '.'); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0 text-primary-custom">
                            <i class="bi bi-tools me-2"></i>Status Perbaikan (Tahun <?php echo $tahun_aktif; ?>)
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <div><i class="bi bi-exclamation-circle text-danger me-2"></i>Laporan Baru (Menunggu)</div>
                                <span class="badge bg-danger rounded-pill fs-6"><?php echo $data_rusak['baru']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <div><i class="bi bi-gear text-primary me-2"></i>Sedang Diperbaiki</div>
                                <span class="badge bg-primary rounded-pill fs-6"><?php echo $data_rusak['diproses']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <div><i class="bi bi-check-circle text-success me-2"></i>Sudah Selesai</div>
                                <span class="badge bg-success rounded-pill fs-6"><?php echo $data_rusak['selesai']; ?></span>
                            </li>
                        </ul>
                        <div class="p-3 bg-light rounded-3 text-center border">
                            <span class="text-muted d-block small mb-1">Total Keseluruhan Laporan Masuk</span>
                            <h3 class="fw-bold mb-0 text-dark"><?php echo $total_laporan_rusak; ?> Laporan</h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
        <div class="print-header mt-5" style="text-align: right; margin-top: 50px !important;">
            <p>Makassar, <?php echo date('d F Y'); ?></p>
            <p style="margin-bottom: 80px;">Mengetahui,<br><strong>Admin Pengelola Asrama</strong></p>
            <p style="text-decoration: underline; font-weight: bold;">( ........................................... )</p>
        </div>

    </main>

    <script src="../../assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>