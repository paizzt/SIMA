<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMA</title>
    <link rel="icon" type="image/png" href="assets/img/logo1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --color-primary: #5A7863;
            --color-bg: #F4F7F5;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fff;
            height: 100vh;
            overflow: hidden;
        }

        .login-wrapper {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* BAGIAN KIRI (GAMBAR) */
        .login-side-image {
            width: 55%;
            background: url('assets/img/asrama.jpeg') no-repeat center center/cover;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 50px;
            color: white;
        }
        
        .login-side-image::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(47, 62, 53, 0.85) 0%, rgba(90, 120, 99, 0.7) 100%);
            z-index: 1;
        }

        .image-content { position: relative; z-index: 2; }

        /* BAGIAN KANAN (FORM) */
        .login-form-container {
            width: 45%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow-y: auto;
        }

        .login-box { width: 100%; max-width: 400px; }

        /* Input Styling */
        .form-floating > .form-control {
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }
        .form-floating > .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px rgba(90, 120, 99, 0.1);
        }

        /* Tombol */
        .btn-primary-custom {
            background-color: var(--color-primary);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-primary-custom:hover {
            background-color: #46604e;
            transform: translateY(-2px);
        }

        @media (max-width: 992px) {
            .login-side-image { display: none; }
            .login-form-container { width: 100%; background-color: var(--color-bg); }
            .login-box {
                background: white; padding: 40px; border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        
        <div class="login-side-image">
            <div class="image-content">
                <h1 class="display-4 fw-bold mb-3">Selamat Datang di SIMA</h1>
                <p class="lead opacity-75">Sistem Informasi Manajemen Asrama yang modern, aman, dan nyaman.</p>
                <div class="mt-4">
                    <span class="badge bg-white text-success px-3 py-2 rounded-pill fw-bold shadow-sm me-2">
                        <i class="bi bi-shield-check me-1"></i> Aman
                    </span>
                    <span class="badge bg-white text-success px-3 py-2 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-wifi me-1"></i> Terintegrasi
                    </span>
                </div>
            </div>
        </div>

        <div class="login-form-container">
            <div class="login-box">
                
                <a href="index.php" class="text-decoration-none text-muted small mb-4 d-inline-flex align-items-center">
                    <i class=""></i> Kembali 
                </a>

                <div class="text-center mb-5 mt-2">
                    <img src="assets/img/logo1.png" alt="Logo SIMA" width="60" height="60" class="mb-3 object-fit-contain">
                    <h3 class="fw-bold mb-1 text-dark">Login Akun</h3>
                    <p class="text-muted small">Masuk untuk mengakses dashboard asrama.</p>
                </div>

                <form id="loginForm">
                    
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="nama@contoh.com" required>
                        <label for="floatingEmail">Alamat Email</label>
                    </div>
                    
                    <div class="form-floating mb-4">
                        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                        <label for="floatingPassword">Kata Sandi</label>
                    </div>

                    <button type="submit" class="btn btn-primary-custom mb-3 shadow-sm" id="btnLogin">
                        Masuk<i class=""></i>
                    </button>

                    <div class="text-center">
                        <p class="small text-muted mb-0">Belum punya akun? 
                            <a href="views/landing/daftar.php" class="fw-bold text-success text-decoration-none">Daftar Disini</a>
                        </p>
                    </div>

                </form>

                <div class="mt-5 text-center text-muted opacity-50" style="font-size: 0.75rem;">
                    &copy; 2026 SIMA System
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah reload halaman
            
            const btn = document.getElementById('btnLogin');
            const originalText = btn.innerHTML;
            
            // Ubah tombol jadi loading
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';
            btn.disabled = true;

            const formData = new FormData(this);

            fetch('action_handlers/process_login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Kembalikan tombol
                btn.innerHTML = originalText;
                btn.disabled = false;

                if (data.status === 'success') {
                    // JIKA SUKSES
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Berhasil!',
                        text: 'Mengalihkan ke Dashboard...',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    // JIKA GAGAL / ERROR
                    Swal.fire({
                        icon: data.status, // error atau info
                        title: data.title,
                        text: data.message,
                        confirmButtonColor: '#d33'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = originalText;
                btn.disabled = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal menghubungi server.',
                    confirmButtonColor: '#d33'
                });
            });
        });
    </script>
</body>
</html>