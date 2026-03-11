<nav class="navbar navbar-expand-lg navbar-custom fixed-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary-custom" href="../../index.php">
            <i class="bi bi-building-check me-2"></i>SIMA
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="../../index.php#beranda">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../index.php#fasilitas">Fasilitas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../index.php#biaya">Biaya</a>
                </li>
                
                <li class="nav-item d-lg-none my-2"><hr class="dropdown-divider"></li>

                <li class="nav-item me-2">
                    <button class="btn btn-link nav-link px-2" onclick="toggleTheme()" title="Ganti Tema">
                        <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
                    </button>
                </li>

                <li class="nav-item ms-lg-2 my-1 my-lg-0">
                    <a href="../../login.php" class="btn btn-outline-success rounded-pill px-4 w-100">Masuk</a>
                </li>
                <li class="nav-item ms-lg-2 my-1 my-lg-0">
                    <a href="daftar.php" class="btn btn-primary-custom shadow-sm rounded-pill px-4 w-100">Daftar</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar-custom');
        if (window.scrollY > 50) {
            navbar.classList.add('bg-white', 'shadow-sm');
            navbar.classList.remove('bg-transparent');
        } else {
            navbar.classList.remove('bg-white', 'shadow-sm');
            navbar.classList.add('bg-transparent');
        }
    });
</script>