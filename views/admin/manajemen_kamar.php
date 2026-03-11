<?php 
include '../../functions/admin_auth.php'; 
include '../../config/database.php';

// --- 1. LOGIKA TAMBAH KAMAR MANUAL ---
if (isset($_POST['tambah_kamar'])) {
    $nomor = mysqli_real_escape_string($conn, $_POST['nomor_kamar']);
    $lantai = $_POST['lantai'];
    $kapasitas = $_POST['kapasitas'];
    
    // Cek duplikat
    $cek = mysqli_query($conn, "SELECT id FROM kamar WHERE nomor_kamar = '$nomor'");
    if(mysqli_num_rows($cek) == 0){
        $query = "INSERT INTO kamar (nomor_kamar, lantai, kapasitas) VALUES ('$nomor', '$lantai', '$kapasitas')";
        if(mysqli_query($conn, $query)){
            header("Location: manajemen_kamar.php?msg=success");
            exit();
        }
    } else {
        header("Location: manajemen_kamar.php?msg=duplicate");
        exit();
    }
}

// --- 2. LOGIKA HAPUS KAMAR ---
if (isset($_GET['hapus_id'])) {
    $id_hapus = $_GET['hapus_id'];
    // Cek apakah kamar kosong
    $cek_isi = mysqli_query($conn, "SELECT COUNT(*) as isi FROM pendaftaran WHERE id_kamar='$id_hapus' AND status_verifikasi='diterima'");
    $data_isi = mysqli_fetch_assoc($cek_isi);
    
    if($data_isi['isi'] == 0){
        mysqli_query($conn, "DELETE FROM kamar WHERE id='$id_hapus'");
        header("Location: manajemen_kamar.php?msg=deleted");
        exit();
    } else {
        header("Location: manajemen_kamar.php?msg=failed_delete");
        exit();
    }
}

// --- 3. QUERY DATA DENGAN SORTING KHUSUS ---
// Mengurutkan: Lantai ASC, lalu Angka Kamar (1.2 sebelum 1.10)
$query = "SELECT k.*, 
          (SELECT COUNT(*) FROM pendaftaran p WHERE p.id_kamar = k.id AND p.status_verifikasi = 'diterima') as terisi 
          FROM kamar k 
          ORDER BY k.lantai ASC, CAST(SUBSTRING_INDEX(k.nomor_kamar, '.', -1) AS UNSIGNED) ASC";

$result = mysqli_query($conn, $query);

$data_lantai = [];
$total_kamar = 0;
$total_kosong = 0;

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)){
        $data_lantai[$row['lantai']][] = $row;
        $total_kamar++;
        if($row['terisi'] == 0) $total_kosong++;
    }
}
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kamar - SIMA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { --color-primary: #5A7863; }
        
        .room-box {
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #eee;
            background: white;
            border-radius: 12px;
            padding: 15px 10px;
            text-align: center;
            position: relative;
        }
        .room-box:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 20px rgba(90, 120, 99, 0.15) !important; 
            border-color: var(--color-primary);
        }
        
        /* Indikator Status (Border Bawah) */
        .room-avail { border-bottom: 5px solid #198754; } /* Hijau */
        .room-filled { border-bottom: 5px solid #ffc107; } /* Kuning */
        .room-full { border-bottom: 5px solid #dc3545; background-color: #fff5f5; } /* Merah */
        
        /* Tombol Hapus Melayang */
        .delete-btn {
            position: absolute; top: 5px; right: 5px;
            width: 24px; height: 24px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; background: #fff; color: #dc3545;
            border: 1px solid #eee;
            opacity: 0; transition: all 0.2s;
            z-index: 10;
        }
        .room-box:hover .delete-btn { opacity: 1; }
        .delete-btn:hover { background: #dc3545; color: white; }

        /* Warna Teks Hijau Custom */
        .text-room-number {
            color: var(--color-primary); /* Menggunakan Hijau Tema */
            font-weight: 800;
        }
    </style>
</head>
<body>

    <?php include '../../layouts/sidebar_admin.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body" style="min-height: 100vh;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-primary-custom">
                    <i class="bi bi-door-open me-2"></i>Manajemen Kamar
                </h2>
                <p class="text-muted small mt-1">
                    Total <strong><?php echo $total_kamar; ?></strong> kamar. 
                    Tersedia <strong><?php echo $total_kosong; ?></strong> kamar kosong.
                </p>
            </div>
            
            <div class="d-flex gap-2">
                <button class="btn btn-primary-custom shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Kamar
                </button>
                <button class="theme-toggle-btn border-0 shadow-sm" onclick="toggleTheme()">
                    <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
                </button>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body py-3 d-flex flex-wrap justify-content-start align-items-center gap-4">
                <span class="small fw-bold text-muted text-uppercase">Indikator:</span>
                <div class="d-flex align-items-center">
                    <span class="badge bg-success rounded-circle p-2 me-2"> </span>
                    <span class="small text-muted">Kosong</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-warning rounded-circle p-2 me-2"> </span>
                    <span class="small text-muted">Terisi Sebagian</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-danger rounded-circle p-2 me-2"> </span>
                    <span class="small text-muted">Penuh</span>
                </div>
            </div>
        </div>

        <?php for ($f = 1; $f <= 4; $f++): ?>
            <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-light border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-primary-custom">
                        <i class="bi bi-layers-fill me-2"></i>Lantai <?php echo $f; ?>
                    </h6>
                </div>
                <div class="card-body bg-light bg-opacity-10">
                    
                    <?php if (isset($data_lantai[$f]) &&count($data_lantai[$f]) > 0): ?>
                        <div class="row g-3">
                            <?php foreach ($data_lantai[$f] as $kamar): ?>
                                <?php 
                                    $isi = $kamar['terisi'];
                                    $max = $kamar['kapasitas'];
                                    
                                    // Tentukan Style Box & Icon
                                    if ($isi >= $max) {
                                        $box_class = "room-full"; 
                                        $icon_color = "text-danger";
                                    } elseif ($isi > 0) {
                                        $box_class = "room-filled"; 
                                        $icon_color = "text-warning";
                                    } else {
                                        $box_class = "room-avail"; 
                                        $icon_color = "text-success";
                                    }
                                ?>
                                
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                    <div class="room-box <?php echo $box_class; ?> shadow-sm" 
                                         onclick="detailKamar('<?php echo $kamar['nomor_kamar']; ?>', '<?php echo $isi; ?>', '<?php echo $max; ?>')">
                                        
                                        <?php if($isi == 0): ?>
                                            <a href="#" onclick="event.stopPropagation(); hapusKamar(<?php echo $kamar['id']; ?>, '<?php echo $kamar['nomor_kamar']; ?>')" class="delete-btn shadow-sm" title="Hapus Kamar">
                                                <i class="bi bi-trash-fill" style="font-size: 0.7rem;"></i>
                                            </a>
                                        <?php endif; ?>

                                        <div class="fs-4 mb-1 text-success fw-bold"><?php echo $kamar['nomor_kamar']; ?></div>
                                        
                                        <div class="d-flex justify-content-center align-items-center small text-muted bg-light rounded-pill py-1 px-2 mx-auto" style="width: fit-content;">
                                            <i class="bi bi-person-fill <?php echo $icon_color; ?> me-1"></i> 
                                            <span class="fw-bold text-dark"><?php echo $isi; ?></span>
                                            <span class="mx-1 text-muted">/</span>
                                            <span><?php echo $max; ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted small border border-dashed rounded bg-white">
                            <i class="bi bi-info-circle me-1"></i> Belum ada kamar di Lantai <?php echo $f; ?>.
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        <?php endfor; ?>

    </main>

    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary-custom text-white border-0">
                    <h5 class="modal-title fw-bold">Tambah Kamar Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nomor Kamar</label>
                            <input type="text" name="nomor_kamar" class="form-control" placeholder="Contoh: 1.1, 2.15" required>
                            <div class="form-text">Format: [Lantai].[Nomor Urut]</div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Lantai</label>
                                <select name="lantai" class="form-select" required>
                                    <option value="1">Lantai 1</option>
                                    <option value="2">Lantai 2</option>
                                    <option value="3">Lantai 3</option>
                                    <option value="4">Lantai 4</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Kapasitas</label>
                                <input type="number" name="kapasitas" class="form-control" value="4" min="1" required>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="tambah_kamar" class="btn btn-primary-custom shadow-sm">
                                <i class="bi bi-save me-2"></i>Simpan Kamar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Notifikasi SweetAlert
        const urlParams = new URLSearchParams(window.location.search);
        const msg = urlParams.get('msg');

        if(msg === 'success'){
            Swal.fire({icon: 'success', title: 'Berhasil', text: 'Kamar baru ditambahkan.', confirmButtonColor: '#5A7863', timer: 2000});
        } else if(msg === 'duplicate'){
            Swal.fire({icon: 'error', title: 'Gagal', text: 'Nomor kamar sudah ada!', confirmButtonColor: '#d33'});
        } else if(msg === 'deleted'){
            Swal.fire({icon: 'success', title: 'Terhapus', text: 'Data kamar berhasil dihapus.', confirmButtonColor: '#5A7863', timer: 1500});
        } else if(msg === 'failed_delete'){
            Swal.fire({icon: 'warning', title: 'Gagal Hapus', text: 'Kamar masih ada penghuninya!', confirmButtonColor: '#d33'});
        }

        // Fungsi Detail Kamar
        function detailKamar(nomor, isi, max) {
            Swal.fire({
                title: `Kamar ${nomor}`,
                html: `
                    <div class="text-center px-4">
                        <div class="mb-3">
                             <div class="display-1 fw-bold text-success mb-2"><i class="bi bi-door-open"></i></div>
                             <h4 class="fw-bold">Status Hunian</h4>
                             <span class="fs-3 fw-bold ${isi>=max ? 'text-danger':'text-success'}">${isi} / ${max}</span>
                             <p class="text-muted small">Orang Terisi</p>
                        </div>
                        <p class="small text-muted mb-0">Klik tombol di bawah untuk melihat daftar nama penghuni.</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Lihat Detail Penghuni',
                cancelButtonText: 'Tutup',
                confirmButtonColor: '#5A7863'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `kelola_penghuni.php?search=${nomor}`;
                }
            });
        }

        function hapusKamar(id, nomor) {
            Swal.fire({
                title: 'Hapus Kamar?',
                text: `Anda yakin ingin menghapus Kamar ${nomor}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#5A7863',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `manajemen_kamar.php?hapus_id=${id}`;
                }
            })
        }
    </script>
</body>
</html>