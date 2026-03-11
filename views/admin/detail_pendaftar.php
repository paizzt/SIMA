<?php 
include '../../functions/admin_auth.php'; 
include '../../config/database.php';

if (!isset($_GET['id'])) {
    header("Location: verifikasi_pendaftar.php");
    exit();
}

$id_pendaftar = $_GET['id'];
$msg_type = "";
$msg_text = "";

// --- LOGIKA PROSES VERIFIKASI (DENGAN ALGORITMA GREEDY) ---
if (isset($_POST['proses_verifikasi'])) {
    $status = $_POST['status_keputusan']; // 'diterima' atau 'ditolak'
    
    if ($status == 'diterima') {
        // --- MULAI ALGORITMA GREEDY ---
        // 1. Ambil data kamar (Urutkan Lantai ASC, Nomor ASC)
        $q_kamar = "SELECT k.id, k.nomor_kamar, k.kapasitas, 
                    (SELECT COUNT(*) FROM pendaftaran p WHERE p.id_kamar = k.id AND p.status_verifikasi = 'diterima') as terisi 
                    FROM kamar k 
                    ORDER BY k.lantai ASC, k.nomor_kamar ASC";
        $res_kamar = mysqli_query($conn, $q_kamar);
        
        $kamar_terpilih = null;

        // 2. Cari Kamar Pertama yang Masih Muat (First Fit)
        while($k = mysqli_fetch_assoc($res_kamar)) {
            if ($k['terisi'] < $k['kapasitas']) {
                $kamar_terpilih = $k['id'];
                break; // Stop, sudah dapat kamar
            }
        }

        // 3. Simpan Keputusan
        if ($kamar_terpilih != null) {
            $query = "UPDATE pendaftaran SET status_verifikasi = 'diterima', id_kamar = '$kamar_terpilih' WHERE id = '$id_pendaftar'";
            
            if (mysqli_query($conn, $query)) {
                header("Location: verifikasi_pendaftar.php?msg=processed");
                exit();
            } else {
                $msg_type = "error";
                $msg_text = "Gagal update database.";
            }
        } else {
            // Jika Penuh
            $msg_type = "error";
            $msg_text = "Gagal Menerima: Mohon maaf, semua kamar asrama sudah PENUH!";
        }

    } else {
        // JIKA DITOLAK
        $query = "UPDATE pendaftaran SET status_verifikasi = 'ditolak', id_kamar = NULL WHERE id = '$id_pendaftar'";
        if (mysqli_query($conn, $query)) {
            header("Location: verifikasi_pendaftar.php?msg=processed");
            exit();
        }
    }
}

// --- AMBIL DATA PENDAFTAR ---
// Menggunakan IFNULL untuk mencegah error jika kolom gender kosong
$query = "SELECT *, IFNULL(jenis_kelamin, 'L') as jk_fix FROM pendaftaran WHERE id = '$id_pendaftar'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) { echo "Data tidak ditemukan."; exit(); }
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pendaftar - SIMA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .greedy-info {
            background: #e9ecef;
            border-left: 4px solid var(--color-primary);
            padding: 15px; border-radius: 4px; font-size: 0.9rem; color: #495057;
        }
        :root { --color-primary: #5A7863; }
    </style>
</head>
<body>

    <?php include '../../layouts/sidebar_admin.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body" style="min-height: 100vh;">
        
        <div class="mb-4">
            <a href="verifikasi_pendaftar.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-primary-custom">Detail Calon Penghuni</h2>
                <p class="text-muted small mt-1">ID Pendaftaran: #<?php echo str_pad($data['id'], 4, '0', STR_PAD_LEFT); ?></p>
            </div>
            
            <div>
                <?php if($data['status_verifikasi'] == 'pending'): ?>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill border border-warning fs-6">Menunggu Verifikasi</span>
                <?php elseif($data['status_verifikasi'] == 'diterima'): ?>
                    <span class="badge bg-success px-3 py-2 rounded-pill fs-6">Sudah Diterima</span>
                <?php else: ?>
                    <span class="badge bg-danger px-3 py-2 rounded-pill fs-6">Ditolak</span>
                <?php endif; ?>
            </div>
        </div>

        <?php if($msg_type == 'error'): ?>
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"></i>
            <div><?php echo $msg_text; ?></div>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-card border-bottom py-3">
                        <h6 class="fw-bold mb-0 text-primary-custom"><i class="bi bi-images me-2"></i>Berkas Identitas</h6>
                    </div>
                    <div class="card-body text-center bg-light">
                        
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">FOTO KTM / PROFIL</label>
                            <div class="ratio ratio-1x1 rounded-3 overflow-hidden shadow-sm mx-auto bg-white" style="max-width: 250px; cursor: pointer;" 
                                 onclick="lihatFoto('../../uploads/identitas/<?php echo $data['foto_ktm']; ?>', 'Foto KTM')">
                                <img src="../../uploads/identitas/<?php echo $data['foto_ktm']; ?>" class="object-fit-cover" alt="Foto KTM">
                            </div>
                        </div>

                        <hr>

                        <div>
                            <label class="form-label small fw-bold text-muted">FOTO KTP</label>
                            <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm mx-auto bg-white" style="cursor: pointer;"
                                 onclick="lihatFoto('../../uploads/dokumen_persyaratan/<?php echo $data['foto_ktp']; ?>', 'Foto KTP')">
                                <img src="../../uploads/dokumen_persyaratan/<?php echo $data['foto_ktp']; ?>" class="object-fit-cover" alt="Foto KTP">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-card border-bottom py-3">
                        <h6 class="fw-bold mb-0 text-primary-custom"><i class="bi bi-person-lines-fill me-2"></i>Biodata Lengkap</h6>
                    </div>
                    <div class="card-body p-4">
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold">Nama Lengkap</label>
                                <div class="fs-5 fw-bold text-dark"><?php echo $data['nama_lengkap']; ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold">Jenis Kelamin</label>
                                <div class="fs-5 text-dark"><?php echo ($data['jk_fix'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold">Nomor WhatsApp</label>
                                <div class="fs-5 text-dark">
                                    <?php echo $data['no_hp']; ?>
                                    <a href="https://wa.me/<?php echo $data['no_hp']; ?>" target="_blank" class="ms-2 text-success small"><i class="bi bi-whatsapp"></i> Chat</a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold">Email</label>
                                <div class="fs-5 text-dark"><?php echo $data['email']; ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold">Jurusan / Fakultas</label>
                                <div class="fs-5 text-dark"><?php echo $data['jurusan']; ?></div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="text-success small text-uppercase fw-bold"><i class="bi bi-calendar-check me-1"></i>Rencana Survei/Masuk</label>
                                <div class="fs-5 fw-bold text-dark">
                                    <?php 
                                        if($data['tanggal_survei']) {
                                            echo date('d F Y', strtotime($data['tanggal_survei'])); 
                                        } else {
                                            echo "<span class='text-muted fst-italic'>Belum ditentukan</span>";
                                        }
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold">Tanggal Daftar</label>
                                <div class="fs-5 text-dark"><?php echo date('d F Y', strtotime($data['tanggal_daftar'])); ?></div>
                            </div>
                            <div class="col-12">
                                <label class="text-muted small text-uppercase fw-bold">Alamat Asal</label>
                                <div class="p-3 bg-light rounded text-dark border"><?php echo $data['alamat_asal']; ?></div>
                            </div>
                        </div>

                        <?php if($data['status_verifikasi'] == 'pending'): ?>
                            <div class="border-top pt-4 mt-4">
                                <h6 class="fw-bold mb-3">Keputusan Admin:</h6>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary-custom flex-grow-1 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTerima">
                                        <i class="bi bi-check-circle-fill me-2"></i>Terima
                                    </button>
                                    
                                    <button type="button" class="btn btn-outline-danger flex-grow-1 py-2" onclick="konfirmasiTolak()">
                                        <i class="bi bi-x-circle-fill me-2"></i>Tolak
                                    </button>
                                </div>
                            </div>
                        <?php elseif($data['status_verifikasi'] == 'diterima'): ?>
                             <div class="alert alert-success d-flex align-items-center mb-0">
                                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                <div>
                                    <strong>Pendaftar Diterima</strong><br>
                                    Telah ditempatkan secara otomatis di Kamar ID: <?php echo $data['id_kamar']; ?>
                                </div>
                             </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>

    </main>

    <div class="modal fade" id="modalTerima" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary-custom text-white border-0">
                    <h5 class="modal-title fw-bold">Konfirmasi Penerimaan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST">
                        <input type="hidden" name="status_keputusan" value="diterima">
                        
                        <div class="text-center mb-4">
                            <h5 class="fw-bold">Alokasi Otomatis</h5>
                            <p class="text-muted">Sistem akan memilihkan kamar terbaik menggunakan <strong>Algoritma Greedy</strong>.</p>
                        </div>

                        <div class="greedy-info mb-4">
                            <strong>Cara Kerja:</strong><br>
                            Sistem akan mencari kamar yang masih kosong berdasarkan urutan prioritas:
                            <ol class="mb-0 ps-3 mt-1">
                                <li>Lantai Terbawah (Lantai 1, 2, dst)</li>
                                <li>Nomor Kamar Terkecil (101, 102, dst)</li>
                            </ol>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="proses_verifikasi" class="btn btn-primary-custom shadow py-2">
                                Jalankan & Terima Pendaftar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="formTolak" method="POST" style="display:none;">
        <input type="hidden" name="status_keputusan" value="ditolak">
        <input type="hidden" name="proses_verifikasi" value="1">
    </form>

    <script src="../../assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function lihatFoto(url, title) {
            Swal.fire({
                title: title, imageUrl: url, imageAlt: title,
                width: 'auto', showConfirmButton: false, showCloseButton: true, background: '#fff'
            });
        }
        function konfirmasiTolak() {
            Swal.fire({
                title: 'Tolak Pendaftaran?',
                text: "Data ini akan ditandai sebagai ditolak dan tidak bisa dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) { document.getElementById('formTolak').submit(); }
            })
        }
    </script>
</body>
</html>