<?php 
include '../../functions/admin_auth.php'; 
include '../../config/database.php';

// --- LOGIKA 1: UPDATE STATUS PEMBAYARAN ---
if (isset($_POST['aksi'])) {
    $id_bayar = $_POST['id_pembayaran'];
    // Jika tombol 'terima' diklik -> status jadi 'verified', jika 'tolak' -> 'rejected'
    $status = ($_POST['aksi'] == 'terima') ? 'verified' : 'rejected';
    
    $update_query = "UPDATE pembayaran SET status = '$status' WHERE id = '$id_bayar'";
    
    if (mysqli_query($conn, $update_query)) {
        header("Location: laporan_keuangan.php?msg=updated");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// --- LOGIKA 2: AMBIL DATA TRANSAKSI ---
$query = "SELECT py.*, p.nama_lengkap, k.nomor_kamar 
          FROM pembayaran py
          JOIN pendaftaran p ON py.id_pendaftar = p.id
          LEFT JOIN kamar k ON p.id_kamar = k.id
          ORDER BY FIELD(py.status, 'pending', 'verified', 'rejected'), py.tanggal_bayar DESC";

$result = mysqli_query($conn, $query);

// --- LOGIKA 3: HITUNG TOTAL PEMASUKAN ---
$q_total = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM pembayaran WHERE status='verified'");
$d_total = mysqli_fetch_assoc($q_total);
$total_income = $d_total['total'] ? $d_total['total'] : 0;
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - SIMA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <?php include '../../layouts/sidebar_admin.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-primary-custom">
                    <i class="bi bi-wallet2 me-2"></i>Manajemen Pembayaran
                </h2>
                <p class="text-muted small mt-1">Verifikasi bukti transfer sewa asrama.</p>
            </div>
            <button class="btn btn-sm btn-outline-secondary rounded-circle p-2" onclick="toggleTheme()">
                <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
            </button>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card card-facility shadow-sm border-0 border-start border-4 border-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 me-3">
                                <i class="bi bi-graph-up-arrow fs-3"></i>
                            </div>
                            <div>
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.75rem;">Total Pemasukan</small>
                                <h3 class="fw-bold text-success mb-0">Rp <?php echo number_format($total_income, 0, ',', '.'); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="fw-bold mb-0 text-primary-custom"><i class="bi bi-table me-2"></i>Daftar Transaksi Masuk</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Penghuni</th>
                                <th>Tanggal & Keterangan</th>
                                <th>Nominal</th>
                                <th>Bukti Bayar</th>
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
                                            <div class="avatar-sm bg-primary-custom text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:35px; height:35px;">
                                                <?php echo strtoupper(substr($row['nama_lengkap'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo $row['nama_lengkap']; ?></div>
                                                <small class="text-muted">
                                                    <?php echo $row['nomor_kamar'] ? "Kamar " . $row['nomor_kamar'] : "Belum ada kamar"; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-dark fw-medium"><?php echo date('d M Y', strtotime($row['tanggal_bayar'])); ?></div>
                                        <small class="text-muted text-truncate d-inline-block" style="max-width: 150px;">
                                            <?php echo $row['keterangan']; ?>
                                        </small>
                                    </td>
                                    <td class="fw-bold text-dark">
                                        Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" 
                                                onclick="Swal.fire({
                                                    imageUrl: '../../uploads/bukti_pembayaran/<?php echo $row['bukti_bayar']; ?>', 
                                                    imageAlt: 'Bukti Transfer',
                                                    showCloseButton: true,
                                                    showConfirmButton: false,
                                                    width: '400px'
                                                })">
                                            <i class="bi bi-eye me-1"></i> Lihat
                                        </button>
                                    </td>
                                    <td>
                                        <?php 
                                        if($row['status'] == 'verified') {
                                            echo '<span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i>Valid</span>';
                                        } elseif($row['status'] == 'rejected') {
                                            echo '<span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i>Ditolak</span>';
                                        } else {
                                            echo '<span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split me-1"></i>Perlu Cek</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <?php if($row['status'] == 'pending'): ?>
                                            <form method="POST" class="d-inline" id="form-bayar-<?php echo $row['id']; ?>">
                                                <input type="hidden" name="id_pembayaran" value="<?php echo $row['id']; ?>">
                                                
                                                <button type="button" class="btn btn-sm btn-outline-danger me-1" title="Tolak" 
                                                        onclick="confirmAksi(event, 'form-bayar-<?php echo $row['id']; ?>', 'tolak')">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                                
                                                <button type="button" class="btn btn-sm btn-primary-custom" title="Validasi" 
                                                        onclick="confirmAksi(event, 'form-bayar-<?php echo $row['id']; ?>', 'terima')">
                                                    <i class="bi bi-check-lg"></i> Validasi
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted small fst-italic"><i class="bi bi-lock-fill"></i> Selesai</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                        Belum ada data transaksi pembayaran.
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
    // FUNGSI KONFIRMASI VALIDASI/TOLAK PEMBAYARAN
    function confirmAksi(event, formId, aksi) {
        event.preventDefault();
        const form = document.getElementById(formId);
        
        // Tentukan teks dan warna berdasarkan aksi
        let titleText = aksi === 'terima' ? 'Validasi Pembayaran?' : 'Tolak Pembayaran?';
        let confirmText = aksi === 'terima' ? 'Ya, Validasi!' : 'Ya, Tolak!';
        let confirmColor = aksi === 'terima' ? '#2d7b1d' : '#dc3545'; // Sesuaikan warna

        Swal.fire({
            title: titleText,
            text: "Pastikan Anda sudah mengecek bukti transfer dengan benar.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmText,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Buat input tersembunyi untuk mengirim nilai 'aksi'
                let inputAksi = document.createElement('input');
                inputAksi.type = 'hidden';
                inputAksi.name = 'aksi';
                inputAksi.value = aksi;
                form.appendChild(inputAksi);
                
                // Submit form
                form.submit();
            }
        });
    }

    // (Biarkan script notifikasi Toast yang sudah ada di bawah ini)
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('msg') === 'updated'){
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        Toast.fire({
            icon: 'success',
            title: 'Status pembayaran berhasil diperbarui!'
        });
    }
</script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('msg') === 'updated'){
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                icon: 'success',
                title: 'Status pembayaran berhasil diperbarui!'
            });
        }
    </script>
</body>
</html>