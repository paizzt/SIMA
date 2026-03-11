<?php
include '../config/database.php';

if (isset($_POST['keyword'])) {
    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);
    
    $query = "SELECT * FROM pendaftaran WHERE email = '$keyword' OR id = '$keyword'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $status = $data['status_verifikasi'];
        
        ?>
        <div class="text-center animate-fade-in">
            <div class="avatar-lg bg-primary-custom text-white fw-bold rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 70px; height: 70px; font-size: 1.8rem;">
                <?php echo strtoupper(substr($data['nama_lengkap'], 0, 1)); ?>
            </div>
            
            <h5 class="fw-bold mb-1 text-dark"><?php echo $data['nama_lengkap']; ?></h5>
            <p class="text-muted small mb-3"><?php echo $data['jurusan']; ?></p>

            <?php if ($status == 'pending'): ?>
                <div class="alert alert-warning border-0 py-3 rounded-4 mb-2">
                    <i class="bi bi-hourglass-split fs-4 mb-2 d-block"></i>
                    <div class="fw-bold">Menunggu Verifikasi</div>
                    <small>Data Anda sedang direview admin.</small>
                </div>
            
            <?php elseif ($status == 'diterima'): ?>
                <div class="alert alert-success border-0 py-4 rounded-4 bg-success bg-opacity-10 text-success mb-2">
                    <i class="bi bi-check-circle-fill display-4 mb-2 d-block"></i>
                    <h5 class="fw-bold mb-1">Selamat! Anda Diterima.</h5>
                    <p class="mb-3 small opacity-75">Silakan login untuk melihat kamar.</p>
                    <a href="login.php" class="btn btn-success btn-sm rounded-pill px-4 shadow-sm">Masuk Dashboard</a>
                </div>

            <?php elseif ($status == 'dibatalkan'): ?>
                <div class="alert border-0 py-3 rounded-4 bg-secondary bg-opacity-10 text-secondary mb-0">
                    <i class="bi bi-x-octagon-fill fs-4 mb-2 d-block"></i>
                    <div class="fw-bold">Pendaftaran Dibatalkan</div>
                    <small>Anda telah membatalkan pendaftaran ini.</small>
                </div>

            <?php else: ?>
                <div class="alert alert-danger border-0 py-3 rounded-4 bg-danger bg-opacity-10 text-danger mb-0">
                    <i class="bi bi-x-circle-fill fs-4 mb-2 d-block"></i>
                    <div class="fw-bold">Mohon Maaf</div>
                    <small>Pendaftaran Anda ditolak.</small>
                </div>
            <?php endif; ?>

            <?php if ($status == 'pending' || $status == 'diterima'): ?>
                
                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill mt-2 w-100 fw-bold" onclick="document.getElementById('formBatalContainer').classList.toggle('d-none')">
                    Batalkan Pendaftaran
                </button>

                <div id="formBatalContainer" class="d-none mt-3 text-start bg-white p-3 rounded-3 border border-danger shadow-sm">
                    
                    <form id="formPembatalan" action="action_handlers/proses_batal.php" method="POST">
                        <input type="hidden" name="id_pendaftaran" value="<?php echo $data['id']; ?>">
                        
                        <label class="small fw-bold text-danger mb-2">
                            <i class="bi bi-chat-text me-1"></i> Alasan & Saran untuk SIMA
                        </label>
                        
                        <textarea id="saranBatal" name="saran" class="form-control mb-3" rows="3" placeholder="Tuliskan alasan batal dan saran perbaikan untuk kami..." required></textarea>
                        
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-light w-50 fw-bold" onclick="document.getElementById('formBatalContainer').classList.add('d-none')">Tutup</button>
                            
                            <button type="button" class="btn btn-sm btn-danger w-50 fw-bold" onclick="
                                var txtSaran = document.getElementById('saranBatal').value;
                                if (txtSaran.trim() === '') {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Saran Kosong',
                                        text: 'Mohon isi alasan pembatalan terlebih dahulu.'
                                    });
                                    return;
                                }
                                Swal.fire({ 
                                    title: 'Batalkan Pendaftaran?', 
                                    text: 'Anda yakin ingin membatalkan pendaftaran ini secara permanen? Keputusan ini tidak bisa diubah.', 
                                    icon: 'warning', 
                                    showCancelButton: true, 
                                    confirmButtonColor: '#d33', 
                                    cancelButtonColor: '#6c757d', 
                                    confirmButtonText: 'Ya, Batalkan!', 
                                    cancelButtonText: 'Kembali' 
                                }).then((result) => { 
                                    if (result.isConfirmed) { 
                                        document.getElementById('formPembatalan').submit();
                                    } 
                                });
                            ">Kirim & Batal</button>
                        </div>
                    </form>
                </div>

            <?php endif; ?>
        </div>
        <?php
    } else {
        ?>
        <div class="text-center py-4 animate-fade-in">
            <i class="bi bi-search display-4 text-muted opacity-25 mb-3 d-block"></i>
            <h6 class="fw-bold text-muted">Data Tidak Ditemukan</h6>
            <p class="small text-muted mb-0">Coba periksa kembali Email atau ID Anda.</p>
        </div>
        <?php
    }
}
?>