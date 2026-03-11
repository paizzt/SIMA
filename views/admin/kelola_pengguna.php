<?php 
include '../../functions/admin_auth.php'; 
include '../../config/database.php';

// --- LOGIKA TAMBAH USER ---
if (isset($_POST['tambah_user'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Cek email duplikat
    $cek = mysqli_query($conn, "SELECT * FROM pengelola WHERE email = '$email'");
    if(mysqli_num_rows($cek) > 0){
        header("Location: kelola_pengguna.php?status=duplicate");
    } else {
        $query = "INSERT INTO pengelola (nama_lengkap, email, password, role) VALUES ('$nama', '$email', '$password', '$role')";
        if (mysqli_query($conn, $query)) {
            header("Location: kelola_pengguna.php?status=success_add");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// --- LOGIKA HAPUS USER ---
if (isset($_GET['delete'])) {
    $id_del = $_GET['delete'];
    // Cegah hapus diri sendiri
    if($id_del != $_SESSION['user_id']){
        mysqli_query($conn, "DELETE FROM pengelola WHERE id = '$id_del'");
        header("Location: kelola_pengguna.php?status=success_delete");
    } else {
        header("Location: kelola_pengguna.php?status=error_self");
    }
}

// --- AMBIL DATA USER ---
$result = mysqli_query($conn, "SELECT * FROM pengelola ORDER BY role ASC, nama_lengkap ASC");
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengguna - SIMA Admin</title>
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
                    <i class="bi bi-shield-lock me-2"></i>Kelola Pengguna
                </h2>
                <p class="text-muted small mt-1">Manajemen akun Admin dan Staff pengelola asrama.</p>
            </div>
            <button class="btn btn-sm btn-outline-secondary rounded-circle p-2" onclick="toggleTheme()">
                <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
            </button>
        </div>

        <div class="mb-4">
            <button class="btn btn-primary-custom shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pengguna Baru
            </button>
        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="fw-bold mb-0 text-primary-custom">Daftar Akun Pengelola</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Nama Lengkap</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width:35px; height:35px;">
                                            <?php echo strtoupper(substr($row['nama_lengkap'], 0, 1)); ?>
                                        </div>
                                        <span class="fw-bold text-dark"><?php echo $row['nama_lengkap']; ?></span>
                                    </div>
                                </td>
                                <td><?php echo $row['email']; ?></td>
                                <td>
                                    <?php if($row['role'] == 'admin'): ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-pill">Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 rounded-pill">Staff</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if($row['id'] == $_SESSION['user_id']): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success">Akun Saya</span>
                                    <?php else: ?>
                                        <button onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn btn-sm btn-outline-danger" title="Hapus User">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 6 Karakter" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role / Jabatan</label>
                            <select name="role" class="form-select">
                                <option value="admin">Administrator (Full Akses)</option>
                                <option value="staff">Staff (Terbatas)</option>
                            </select>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" name="tambah_user" class="btn btn-primary-custom">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Notifikasi Handling
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if(status === 'success_add'){
            Swal.fire({icon: 'success', title: 'Berhasil!', text: 'Pengguna baru telah ditambahkan.'});
        } else if(status === 'success_delete'){
            Swal.fire({icon: 'success', title: 'Terhapus!', text: 'Data pengguna berhasil dihapus.'});
        } else if(status === 'duplicate'){
            Swal.fire({icon: 'error', title: 'Gagal!', text: 'Email sudah digunakan oleh pengguna lain.'});
        } else if(status === 'error_self'){
            Swal.fire({icon: 'warning', title: 'Ditolak!', text: 'Anda tidak bisa menghapus akun sendiri.'});
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Pengguna?',
                text: "Akses login pengguna ini akan dicabut permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `kelola_pengguna.php?delete=${id}`;
                }
            })
        }
    </script>
</body>
</html>