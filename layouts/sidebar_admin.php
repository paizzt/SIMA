<?php
// Mendapatkan nama file saat ini untuk menentukan menu aktif
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav id="sidebar" class="sidebar bg-card d-flex flex-column flex-shrink-0 p-3 shadow-sm" style="min-height: 100vh;">
    
    <button class="btn-toggle-sidebar d-none d-md-flex" onclick="toggleSidebar()">
        <i class="bi bi-chevron-left"></i>
    </button>

    <div class="d-flex justify-content-center w-100 mb-3">
        <a href="dashboard.php" class="d-flex align-items-center text-decoration-none sidebar-header">
            <img src="../../assets/img/logo2.png" alt="Logo" width="40" height="40" class="me-2 logo-icon object-fit-contain">
            <span class="fs-4 fw-bold text-primary-custom sidebar-text">SIMA</span>
        </a>
    </div>
    
    <hr>
    
    <ul class="nav nav-pills flex-column mb-auto">
        
        <li class="nav-item mb-1">
            <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active bg-primary-custom' : 'link-body-emphasis'; ?>" title="Dashboard">
                <i class="bi bi-speedometer2 me-2"></i> 
                <span class="sidebar-text">Dashboard</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="verifikasi_pendaftar.php" class="nav-link <?php echo ($current_page == 'verifikasi_pendaftar.php' || $current_page == 'detail_pendaftar.php') ? 'active bg-primary-custom' : 'link-body-emphasis'; ?>" title="Verifikasi Pendaftar">
                <i class="bi bi-person-check me-2"></i> 
                <span class="sidebar-text">Verifikasi Pendaftar</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="kelola_survei.php" class="nav-link <?php echo ($current_page == 'kelola_survei.php') ? 'active bg-primary-custom' : 'link-body-emphasis'; ?>" title="Jadwal Survei">
                <i class="bi bi-calendar-check me-2"></i> 
                <span class="sidebar-text">Jadwal Survei</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="kelola_penghuni.php" class="nav-link <?php echo ($current_page == 'kelola_penghuni.php' || $current_page == 'detail_penghuni.php') ? 'active bg-primary-custom' : 'link-body-emphasis'; ?>" title="Data Penghuni">
                <i class="bi bi-people me-2"></i> 
                <span class="sidebar-text">Data Penghuni</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="kelola_permohonan.php" class="nav-link <?php echo ($current_page == 'kelola_permohonan.php') ? 'active bg-primary-custom' : 'link-body-emphasis'; ?>" title="Permohonan Izin">
                <i class="bi bi-envelope-paper me-2"></i> 
                <span class="sidebar-text">Permohonan Izin</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="manajemen_kamar.php" class="nav-link <?php echo ($current_page == 'manajemen_kamar.php') ? 'active bg-primary-custom' : 'link-body-emphasis'; ?>" title="Manajemen Kamar">
                <i class="bi bi-door-open me-2"></i> 
                <span class="sidebar-text">Manajemen Kamar</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="laporan_kerusakan.php" class="nav-link <?php echo ($current_page == 'laporan_kerusakan.php') ? 'active bg-primary-custom' : 'link-body-emphasis'; ?>" title="Laporan Kerusakan">
                <i class="bi bi-tools me-2"></i> 
                <span class="sidebar-text">Laporan Kerusakan</span>
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="laporan_keuangan.php" class="nav-link <?php echo ($current_page == 'laporan_keuangan.php') ? 'active bg-primary-custom' : 'link-body-emphasis'; ?>" title="Laporan Keuangan">
                <i class="bi bi-wallet2 me-2"></i> 
                <span class="sidebar-text">Laporan Keuangan</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="rekapitulasi.php" class="nav-link <?php echo ($current_page == 'rekapitulasi.php') ? 'active bg-primary-custom' : 'link-body-emphasis'; ?>" title="Laporan & Rekapitulasi">
                <i class="bi bi-bar-chart-fill me-2"></i> 
                <span class="sidebar-text">Laporan & Rekap</span>
            </a>
        </li>

    </ul>
    
    <hr>
    
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle justify-content-center" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="bg-primary-custom text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                <i class="bi bi-person-fill"></i>
            </div>
            <span class="sidebar-text fw-bold">
                <?php echo isset($_SESSION['nama']) ? substr($_SESSION['nama'], 0, 15) : 'Admin'; ?>
            </span>
        </a>
        <ul class="dropdown-menu text-small shadow">
            <li><a class="dropdown-item" href="profil.php">Profil Admin</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-danger" href="../../logout.php" onclick="confirmLogout(event)">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</nav>

<script>
    // 1. Fungsi Toggle Sidebar
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const content = document.querySelector('.content-wrapper');
        const toggleBtn = document.querySelector('.btn-toggle-sidebar i');
        
        sidebar.classList.toggle('collapsed');
        if(content) content.classList.toggle('expanded');

        // Ganti Icon Panah
        if (sidebar.classList.contains('collapsed')) {
            toggleBtn.classList.remove('bi-chevron-left');
            toggleBtn.classList.add('bi-chevron-right');
        } else {
            toggleBtn.classList.remove('bi-chevron-right');
            toggleBtn.classList.add('bi-chevron-left');
        }

        // Simpan State ke LocalStorage
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarState', isCollapsed ? 'collapsed' : 'expanded');
    }

    // 2. Restore State saat Load Page
    document.addEventListener('DOMContentLoaded', () => {
        const savedState = localStorage.getItem('sidebarState');
        const sidebar = document.getElementById('sidebar');
        const content = document.querySelector('.content-wrapper');
        const toggleBtn = document.querySelector('.btn-toggle-sidebar i');

        if (savedState === 'collapsed') {
            sidebar.classList.add('collapsed');
            if(content) content.classList.add('expanded');
            if(toggleBtn) {
                toggleBtn.classList.remove('bi-chevron-left');
                toggleBtn.classList.add('bi-chevron-right');
            }
        }
    });

    // 3. Fungsi Confirm Logout (SweetAlert)
    function confirmLogout(event) {
        event.preventDefault(); // Cegah link langsung jalan
        const url = event.currentTarget.getAttribute('href');

        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: "Anda harus login kembali untuk mengakses halaman ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#5A7863',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>