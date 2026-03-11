<?php 
include '../../functions/admin_auth.php'; 
include '../../config/database.php';

if (!isset($_GET['id'])) {
    header("Location: kelola_penghuni.php");
    exit();
}

$id_penghuni = $_GET['id'];

// --- 1. LOGIKA PINDAH KAMAR ---
if (isset($_POST['pindah_kamar'])) {
    $id_kamar_baru = $_POST['id_kamar_baru'];
    $query_update = "UPDATE pendaftaran SET id_kamar = '$id_kamar_baru' WHERE id = '$id_penghuni'";
    
    if (mysqli_query($conn, $query_update)) {
        echo "<script>alert('Berhasil pindah kamar!'); window.location='detail_penghuni.php?id=$id_penghuni';</script>";
    }
}

// --- 2. LOGIKA KELUARKAN PENGHUNI ---
if (isset($_POST['keluarkan_penghuni'])) {
    $q_out = "UPDATE pendaftaran SET status_verifikasi = 'ditolak', id_kamar = NULL WHERE id = '$id_penghuni'";
    if (mysqli_query($conn, $q_out)) {
        header("Location: kelola_penghuni.php?msg=deleted");
        exit();
    }
}

// --- 3. AMBIL DATA UTAMA ---
$query = "SELECT p.*, k.nomor_kamar, k.lantai, k.id as current_kamar_id
          FROM pendaftaran p 
          LEFT JOIN kamar k ON p.id_kamar = k.id 
          WHERE p.id = '$id_penghuni'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) { echo "Data tidak ditemukan."; exit(); }

// --- 4. AMBIL DATA TEMAN SEKAMAR ---
$roommates = [];
if ($data['current_kamar_id']) {
    $id_kamar = $data['current_kamar_id'];
    $q_mate = "SELECT nama_lengkap, jurusan FROM pendaftaran WHERE id_kamar = '$id_kamar' AND id != '$id_penghuni' AND status_verifikasi='diterima'";
    $res_mate = mysqli_query($conn, $q_mate);
    while($r = mysqli_fetch_assoc($res_mate)) { $roommates[] = $r; }
}

// --- 5. AMBIL DATA KAMAR KOSONG (Untuk Dropdown Pindah) ---
$q_kamar = "SELECT k.*, (SELECT COUNT(*) FROM pendaftaran p WHERE p.id_kamar = k.id AND p.status_verifikasi = 'diterima') as terisi 
            FROM kamar k HAVING terisi < k.kapasitas ORDER BY k.nomor_kamar ASC";
$res_kamar = mysqli_query($conn, $q_kamar);
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penghuni - SIMA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { --color-primary: #5A7863; }
        .nav-tabs .nav-link.active {
            border-top: 3px solid var(--color-primary);
            color: var(--color-primary);
            font-weight: bold;
        }
        .nav-tabs .nav-link { color: #6c757d; }
    </style>
</head>
<body>

    <?php include '../../layouts/sidebar_admin.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body" style="min-height: 100vh;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="kelola_penghuni.php" class="btn btn-sm btn-outline-secondary rounded-pill mb-2"><i class="bi bi-arrow-left"></i> Kembali</a>
                <h2 class="fw-bold mb-0 text-primary-custom">Profil Penghuni</h2>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPindah">
                    <i class="bi bi-arrow-left-right me-1"></i> Pindah Kamar
                </button>
                <button class="btn btn-outline-danger rounded-pill shadow-sm" onclick="konfirmasiKeluar()">
                    <i class="bi bi-box-arrow-right me-1"></i> Check-Out
                </button>
            </div>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                    <div class="card-body text-center p-5 bg-white">
                        <div class="avatar-xl bg-primary-custom text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center shadow" style="width: 100px; height: 100px; font-size: 2.5rem;">
                            <?php echo strtoupper(substr($data['nama_lengkap'], 0, 1)); ?>
                        </div>
                        <h4 class="fw-bold mb-1"><?php echo $data['nama_lengkap']; ?></h4>
                        <p class="text-muted mb-3"><?php echo $data['jurusan']; ?></p>
                        
                        <div class="d-flex justify-content-center gap-2 mb-4">
                            <a href="https://wa.me/<?php echo $data['no_hp']; ?>" target="_blank" class="btn btn-success btn-sm rounded-pill px-3">
                                <i class="bi bi-whatsapp me-1"></i> WhatsApp
                            </a>
                            <a href="mailto:<?php echo $data['email']; ?>" class="btn btn-secondary btn-sm rounded-pill px-3">
                                <i class="bi bi-envelope me-1"></i> Email
                            </a>
                        </div>

                        <hr>

                        <div class="row text-start mt-4">
                            <div class="col-6 mb-3">
                                <small class="text-muted d-block fw-bold">STATUS</small>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">Aktif</span>
                            </div>
                            <div class="col-6 mb-3">
                                <small class="text-muted d-block fw-bold">BERGABUNG</small>
                                <span class="text-dark small"><?php echo date('d M Y', strtotime($data['tanggal_daftar'])); ?></span>
                            </div>
                            <div class="col-12">
                                <small class="text-muted d-block fw-bold">KAMAR SAAT INI</small>
                                <div class="d-flex align-items-center mt-1">
                                    <div class="icon-box bg-light text-primary rounded p-2 me-2"><i class="bi bi-key-fill"></i></div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Kamar <?php echo $data['nomor_kamar']; ?></h6>
                                        <small class="text-muted">Lantai <?php echo $data['lantai']; ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white fw-bold border-bottom">Teman Sekamar</div>
                    <ul class="list-group list-group-flush">
                        <?php if(!empty($roommates)): ?>
                            <?php foreach($roommates as $rm): ?>
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="avatar-xs bg-secondary bg-opacity-10 text-secondary rounded-circle me-2 d-flex justify-content-center align-items-center" style="width:30px; height:30px; font-size:0.8rem;">
                                        <?php echo strtoupper(substr($rm['nama_lengkap'], 0, 1)); ?>
                                    </div>
                                    <div class="small">
                                        <div class="fw-bold"><?php echo $rm['nama_lengkap']; ?></div>
                                        <div class="text-muted" style="font-size: 0.7rem;"><?php echo $rm['jurusan']; ?></div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted small text-center py-3">Tidak ada teman sekamar.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="biodata-tab" data-bs-toggle="tab" data-bs-target="#biodata" type="button" role="tab">Biodata Lengkap</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pembayaran-tab" data-bs-toggle="tab" data-bs-target="#pembayaran" type="button" role="tab">Riwayat Pembayaran</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="laporan-tab" data-bs-toggle="tab" data-bs-target="#laporan" type="button" role="tab">Laporan Kerusakan</button>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="tab-content" id="myTabContent">
                            
                            <div class="tab-pane fade show active" id="biodata" role="tabpanel">
                                
                                <h6 class="fw-bold text-primary-custom mb-3 border-bottom pb-2">Data Pribadi</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="text-muted small fw-bold text-uppercase">Nama Lengkap</label>
                                        <div class="fs-5 text-dark"><?php echo $data['nama_lengkap']; ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small fw-bold text-uppercase">Jenis Kelamin</label>
                                        <div class="fs-5 text-dark"><?php echo ($data['jenis_kelamin'] ?? 'L') == 'L' ? 'Laki-laki' : 'Perempuan'; ?></div>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-muted small fw-bold text-uppercase">Alamat Asal</label>
                                        <div class="p-2 bg-light rounded text-dark border"><?php echo $data['alamat_asal']; ?></div>
                                    </div>
                                </div>

                                <h6 class="fw-bold text-primary-custom mb-3 border-bottom pb-2">Data Kontak</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="text-muted small fw-bold text-uppercase">Nomor WhatsApp</label>
                                        <div class="fs-5 text-dark">
                                            <?php echo $data['no_hp']; ?> 
                                            <a href="https://wa.me/<?php echo $data['no_hp']; ?>" target="_blank" class="ms-2 small text-success"><i class="bi bi-whatsapp"></i> Chat</a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small fw-bold text-uppercase">Alamat Email</label>
                                        <div class="fs-5 text-dark"><?php echo $data['email']; ?></div>
                                    </div>
                                </div>

                                <h6 class="fw-bold text-primary-custom mb-3 border-bottom pb-2">Data Akademik & Sistem</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="text-muted small fw-bold text-uppercase">Jurusan / Fakultas</label>
                                        <div class="fs-5 text-dark"><?php echo $data['jurusan']; ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small fw-bold text-uppercase">Tanggal Daftar</label>
                                        <div class="fs-5 text-dark"><?php echo date('d F Y', strtotime($data['tanggal_daftar'])); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-success small fw-bold text-uppercase"><i class="bi bi-calendar-check me-1"></i>Tanggal Survei / Masuk</label>
                                        <div class="fs-5 fw-bold text-dark">
                                            <?php 
                                            if ($data['tanggal_survei'] && $data['tanggal_survei'] != '0000-00-00') {
                                                echo date('d F Y', strtotime($data['tanggal_survei']));
                                            } else {
                                                echo "<span class='text-muted fst-italic'>- Belum diisi -</span>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="fw-bold text-primary-custom mb-3 border-bottom pb-2">Berkas Pendukung</h6>
                                <div class="d-flex gap-3">
                                    <button onclick="lihatFoto('../../uploads/dokumen_persyaratan/<?php echo $data['foto_ktp']; ?>', 'Foto KTP')" class="btn btn-light border shadow-sm">
                                        <i class="bi bi-card-heading me-2 text-primary"></i> Lihat Foto KTP
                                    </button>
                                    <button onclick="lihatFoto('../../uploads/dokumen_persyaratan/<?php echo $data['foto_ktm']; ?>', 'Foto KTM')" class="btn btn-light border shadow-sm">
                                        <i class="bi bi-person-badge me-2 text-primary"></i> Lihat Foto KTM
                                    </button>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pembayaran" role="tabpanel">
                                <?php 
                                    $q_bayar = "SELECT * FROM pembayaran WHERE id_pendaftar = '$id_penghuni' ORDER BY tanggal_bayar DESC";
                                    $res_bayar = mysqli_query($conn, $q_bayar);
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="bg-light text-muted small">
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jumlah</th>
                                                <th>Bukti</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(mysqli_num_rows($res_bayar) > 0): ?>
                                                <?php while($b = mysqli_fetch_assoc($res_bayar)): ?>
                                                <tr>
                                                    <td><?php echo date('d M Y', strtotime($b['tanggal_bayar'])); ?></td>
                                                    <td>Rp <?php echo number_format($b['jumlah_bayar'], 0, ',', '.'); ?></td>
                                                    <td>
                                                        <button onclick="lihatFoto('../../uploads/bukti_pembayaran/<?php echo $b['bukti_bayar']; ?>', 'Bukti Transfer')" class="btn btn-xs btn-link text-decoration-none">Lihat</button>
                                                    </td>
                                                    <td>
                                                        <?php if($b['status'] == 'lunas'): ?>
                                                            <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill">Lunas</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning text-dark border border-warning rounded-pill">Pending</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr><td colspan="4" class="text-center py-4 text-muted small">Belum ada riwayat pembayaran.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="laporan" role="tabpanel">
                                <?php 
                                    $q_lapor = "SELECT * FROM laporan_kerusakan WHERE id_pendaftar = '$id_penghuni' ORDER BY tanggal_lapor DESC";
                                    $res_lapor = mysqli_query($conn, $q_lapor);
                                ?>
                                <div class="list-group list-group-flush">
                                    <?php if(mysqli_num_rows($res_lapor) > 0): ?>
                                        <?php while($l = mysqli_fetch_assoc($res_lapor)): ?>
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1 fw-bold"><?php echo $l['judul_laporan']; ?></h6>
                                                    <small class="text-muted d-block"><?php echo date('d M Y', strtotime($l['tanggal_lapor'])); ?></small>
                                                    <p class="mb-0 small text-muted mt-1 fst-italic">"<?php echo $l['deskripsi']; ?>"</p>
                                                </div>
                                                <span class="badge <?php echo ($l['status']=='done'?'bg-success':'bg-warning text-dark'); ?> rounded-pill">
                                                    <?php echo ($l['status']=='done'?'Selesai':'Proses'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <div class="text-center py-4 text-muted small">Tidak ada laporan kerusakan.</div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <div class="modal fade" id="modalPindah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary-custom text-white border-0">
                    <h5 class="modal-title fw-bold">Pindah Kamar</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST">
                        <p class="text-muted small">Memindahkan <strong><?php echo $data['nama_lengkap']; ?></strong> dari Kamar <?php echo $data['nomor_kamar']; ?>.</p>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Pilih Kamar Tujuan</label>
                            <select name="id_kamar_baru" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Kamar --</option>
                                <?php while($k = mysqli_fetch_assoc($res_kamar)): ?>
                                    <?php $sisa = $k['kapasitas'] - $k['terisi']; ?>
                                    <option value="<?php echo $k['id']; ?>">
                                        Kamar <?php echo $k['nomor_kamar']; ?> (Lt. <?php echo $k['lantai']; ?>) - Sisa <?php echo $sisa; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="pindah_kamar" class="btn btn-primary-custom">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="formKeluar" method="POST" style="display:none;">
        <input type="hidden" name="keluarkan_penghuni" value="1">
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
        function konfirmasiKeluar() {
            Swal.fire({
                title: 'Konfirmasi Check-Out',
                text: "Apakah Anda yakin ingin mengeluarkan penghuni ini? Akses akan dicabut.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Keluarkan'
            }).then((result) => {
                if (result.isConfirmed) { document.getElementById('formKeluar').submit(); }
            })
        }
    </script>
</body>
</html>