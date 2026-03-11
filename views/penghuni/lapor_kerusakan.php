<?php 
include '../../functions/penghuni_auth.php'; 
include '../../config/database.php';

$id_user = $_SESSION['user_id'];
$msg_type = "";
$msg_text = "";

// --- LOGIKA KIRIM LAPORAN ---
if (isset($_POST['kirim_laporan'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    // Upload Foto
    $foto_nama = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_ext = pathinfo($foto_nama, PATHINFO_EXTENSION);
    $foto_baru = uniqid() . '.' . $foto_ext;
    $lokasi_upload = '../../uploads/kerusakan/';

    // Validasi Ekstensi
    $ekstensi_boleh = array('png', 'jpg', 'jpeg', 'webp');
    
    if (in_array(strtolower($foto_ext), $ekstensi_boleh)) {
        // Buat folder jika belum ada
        if (!file_exists($lokasi_upload)) {
            mkdir($lokasi_upload, 0777, true);
        }

        if (move_uploaded_file($foto_tmp, $lokasi_upload . $foto_baru)) {
            $query = "INSERT INTO laporan_kerusakan (id_pendaftar, judul_laporan, deskripsi, foto_bukti, status) 
                    VALUES ('$id_user', '$judul', '$deskripsi', '$foto_baru', 'baru')";
            if (mysqli_query($conn, $query)) {
                header("Location: lapor_kerusakan.php?status=success");
                exit();
            } else {
                $msg_type = "error";
                $msg_text = "Gagal menyimpan ke database.";
            }
        } else {
            $msg_type = "error";
            $msg_text = "Gagal mengupload gambar.";
        }
    } else {
        $msg_type = "error";
        $msg_text = "Format file tidak valid (Gunakan JPG/PNG).";
    }
}

// --- AMBIL RIWAYAT LAPORAN ---
$query_history = "SELECT * FROM laporan_kerusakan WHERE id_pendaftar = '$id_user' ORDER BY tanggal_lapor DESC";
$result = mysqli_query($conn, $query_history);
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Kerusakan - SIMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <?php include '../../layouts/sidebar_penghuni.php'; ?>

    <main class="content-wrapper px-md-4 py-4 bg-body" style="min-height: 100vh;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-primary-custom">
                    <i class="bi bi-tools me-2"></i>Layanan Perbaikan
                </h2>
                <p class="text-muted small mt-1">Laporkan fasilitas yang rusak agar segera diperbaiki.</p>
            </div>
            
            <button class="theme-toggle-btn" onclick="toggleTheme()" title="Ganti Mode Tampilan">
                <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
            </button>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="fw-bold mb-0 text-primary-custom">Formulir Laporan</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php if($msg_type == 'error'): ?>
                            <div class="alert alert-danger small py-2 mb-3"><?php echo $msg_text; ?></div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-uppercase">Objek Kerusakan</label>
                                <input type="text" name="judul" class="form-control" placeholder="Contoh: Keran Air Patah, Lampu Mati" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-uppercase">Deskripsi Detail</label>
                                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan kondisi kerusakan..." required></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-uppercase">Foto Bukti</label>
                                <input type="file" name="foto" class="form-control" accept="image/*" required>
                                <div class="form-text small text-muted">Format: JPG, PNG, WEBP. Max 2MB.</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="kirim_laporan" class="btn btn-primary-custom shadow-sm py-2">
                                    <i class="bi bi-send-fill me-2"></i>Kirim Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card bg-primary-custom text-white border-0 rounded-4 mt-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-lightbulb fs-2 me-3 opacity-50"></i>
                        <small>Pastikan foto terlihat jelas agar teknisi membawa alat yang tepat.</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-primary-custom">Riwayat Laporan Saya</h5>
                        <span class="badge bg-light text-muted border"><?php echo mysqli_num_rows($result); ?> Laporan</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <div class="list-group list-group-flush">
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <div class="list-group-item p-4 border-bottom-0 border-top">
                                        <div class="row align-items-center">
                                            <div class="col-3 col-md-2">
                                                <div class="ratio ratio-1x1 rounded-3 overflow-hidden shadow-sm" style="cursor: pointer;" 
                                                    onclick="lihatFoto('../../uploads/kerusakan/<?php echo $row['foto_bukti']; ?>')">
                                                    <img src="../../uploads/kerusakan/<?php echo $row['foto_bukti']; ?>" class="object-fit-cover" alt="Bukti">
                                                </div>
                                            </div>

                                            <div class="col-9 col-md-10">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold text-dark"><?php echo $row['judul_laporan']; ?></h6>
                                                        <small class="text-muted"><i class="bi bi-clock me-1"></i><?php echo date('d M Y, H:i', strtotime($row['tanggal_lapor'])); ?></small>
                                                    </div>
                                                    
                                                    <?php 
                                                    if($row['status'] == 'pending') 
                                                        echo '<span class="badge bg-warning text-dark border border-warning rounded-pill px-3"><i class="bi bi-hourglass-split me-1"></i>Menunggu</span>';
                                                    elseif($row['status'] == 'in_progress') 
                                                        echo '<span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-3"><i class="bi bi-tools me-1"></i>Diproses</span>';
                                                    else 
                                                        echo '<span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3"><i class="bi bi-check-circle me-1"></i>Selesai</span>';
                                                    ?>
                                                </div>
                                                
                                                <p class="mb-0 text-muted small bg-light p-2 rounded fst-italic text-truncate">
                                                    "<?php echo $row['deskripsi']; ?>"
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-clipboard-check display-1 text-muted opacity-25"></i>
                                </div>
                                <h6 class="text-muted fw-bold">Belum ada laporan kerusakan.</h6>
                                <p class="text-muted small">Fasilitas aman? Bagus! Nikmati harimu.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <script src="../../assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Notifikasi Sukses via URL
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('status') === 'success'){
            Swal.fire({
                icon: 'success',
                title: 'Terkirim!',
                text: 'Laporan Anda telah masuk ke sistem kami.',
                confirmButtonColor: '#5A7863',
                timer: 3000
            }).then(() => {
                window.history.replaceState(null, null, window.location.pathname);
            });
        }

        // Fungsi Lihat Foto Popup
        function lihatFoto(url) {
            Swal.fire({
                imageUrl: url,
                imageAlt: 'Bukti Foto',
                showConfirmButton: false,
                showCloseButton: true,
                width: 'auto',
                padding: '1em',
                background: '#fff'
            });
        }
    </script>
</body>
</html>