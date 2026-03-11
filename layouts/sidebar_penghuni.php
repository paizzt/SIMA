<?php
// Mendapatkan nama file saat ini
$current_page = basename($_SERVER['PHP_SELF']);

// Ambil status bayar dari session (diset di penghuni_auth.php)
$is_paid = isset($_SESSION['status_bayar_awal']) ? $_SESSION['status_bayar_awal'] : false;

// Helper: Class untuk menu yang dikunci
$disabled_class = $is_paid ? '' : 'disabled text-muted opacity-50';
$lock_icon = $is_paid ? '' : '<i class="bi bi-lock-fill ms-auto fs-6"></i>';
?>

<nav id="sidebar" class="sidebar bg-card d-flex flex-column flex-shrink-0 p-3 shadow-sm" style="min-height: 100vh;">
    
    <button class="btn-toggle-sidebar d-none d-md-flex" onclick="toggleSidebar()">
        <i class="bi bi-chevron-left"></i>
    </button>

    <div class="d-flex justify-content-center w-100 mb-3">
        <a href="#" class="d-flex align-items-center text-decoration-none sidebar-header">
            <img src="../../assets/img/logo2.png" alt="Logo" width="40" height="40" class="me-2 logo-icon object-fit-contain">
            <span class="fs-4 fw-bold text-primary-custom sidebar-text">SIMA</span>
        </a>
    </div>
    
    <hr>
    
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1">
            <a href="dashboard.php" class="nav-link <?php echo $disabled_class; ?> <?php echo ($current_page == 'dashboard.php') ? 'active bg-primary-custom' : 'link-body-emphasis'; ?> d-flex align-items-center">
                <i class="bi bi-speedometer2 me-2"></i> 
                <span class="sidebar-text me-2">Dashboard</span>
                <?php if(!$is_paid) echo $lock_icon; ?>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="profil.php" class="nav-link <?php echo $disabled_class; ?> <?php echo ($current_page == 'profil.php') ? 'active bg-primary-custom' : 'link-body-emphasis'; ?> d-flex align-items-center">
                <i class="bi bi-person-circle me-2"></i> 
                <span class="sidebar-text me-2">Profil Saya</span>
                <?php if(!$is_paid) echo $lock_icon; ?>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="pembayaran.php" class="nav-link <?php echo ($current_page == 'pembayaran.php') ? 'active bg-primary-custom' : 'link-body-emphasis'; ?>">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div><i class="bi bi-wallet2 me-2"></i> <span class="sidebar-text">Pembayaran</span></div>
                    <?php if(!$is_paid): ?>
                        <span class="badge bg-danger rounded-pill sidebar-text px-2">Wajib</span>
                    <?php endif; ?>
                </div>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="lapor_kerusakan.php" class="nav-link <?php echo $disabled_class; ?> <?php echo ($current_page == 'lapor_kerusakan.php') ? 'active bg-primary-custom' : 'link-body-emphasis'; ?> d-flex align-items-center">
                <i class="bi bi-tools me-2"></i> 
                <span class="sidebar-text me-2">Lapor Kerusakan</span>
                <?php if(!$is_paid) echo $lock_icon; ?>
            </a>
        </li>
        
        <li class="nav-item mb-1">
            <a href="permohonan.php" class="nav-link <?php echo $disabled_class; ?> <?php echo ($current_page == 'permohonan.php') ? 'active bg-primary-custom' : 'link-body-emphasis'; ?> d-flex align-items-center">
                <i class="bi bi-envelope-paper me-2"></i> 
                <span class="sidebar-text me-2">Permohonan Izin</span>
                <?php if(!$is_paid) echo $lock_icon; ?>
            </a>
        </li>
    </ul>
    
    <hr>
    
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle justify-content-center" data-bs-toggle="dropdown">
            <div class="bg-primary-custom text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                <i class="bi bi-person-fill"></i>
            </div>
            <span class="sidebar-text fw-bold">
                <?php echo isset($_SESSION['nama']) ? explode(' ', $_SESSION['nama'])[0] : 'Penghuni'; ?>
            </span>
        </a>
        <ul class="dropdown-menu text-small shadow">
            <li><a class="dropdown-item text-danger" href="../../logout.php" onclick="confirmLogout(event)">Logout</a></li>
        </ul>
    </div>
</nav>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const content = document.querySelector('.content-wrapper');
        const toggleBtn = document.querySelector('.btn-toggle-sidebar i');
        sidebar.classList.toggle('collapsed');
        if(content) content.classList.toggle('expanded');
        if (sidebar.classList.contains('collapsed')) {
            toggleBtn.classList.remove('bi-chevron-left'); toggleBtn.classList.add('bi-chevron-right');
        } else {
            toggleBtn.classList.remove('bi-chevron-right'); toggleBtn.classList.add('bi-chevron-left');
        }
    }
    function confirmLogout(event) {
        event.preventDefault(); const url = event.currentTarget.getAttribute('href');
        Swal.fire({
            title: 'Yakin ingin keluar?', text: "Sesi Anda akan diakhiri.", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Keluar'
        }).then((result) => { if (result.isConfirmed) window.location.href = url; });
    }
</script>