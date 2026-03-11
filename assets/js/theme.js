/**
 * Modern Theme Toggler for SIMA
 * Handles Light/Dark mode switching & Animations
 */

function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    const icon = document.getElementById('theme-icon');
    
    // 1. Animasi Icon Berputar
    if(icon) icon.classList.add('icon-spin-transition');

    // 2. Ganti Tema (dengan sedikit delay agar pas dengan animasi)
    setTimeout(() => {
        // Set ke HTML tag
        html.setAttribute('data-bs-theme', newTheme);
        // Simpan ke LocalStorage
        localStorage.setItem('theme', newTheme);

        // Ganti Icon
        if(icon) {
            if (newTheme === 'dark') {
                icon.className = 'bi bi-sun-fill';
            } else {
                icon.className = 'bi bi-moon-stars-fill';
            }
        }
    }, 250);

    // 3. Bersihkan kelas animasi
    setTimeout(() => {
        if(icon) icon.classList.remove('icon-spin-transition');
    }, 500);
}

// --- AUTO RUN SAAT WEBSITE DIBUKA ---
(function() {
    // Cek penyimpanan terakhir, default ke 'light'
    const savedTheme = localStorage.getItem('theme') || 'light';
    
    // Terapkan ke HTML tag langsung agar tidak kedip
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
    
    // Saat konten sudah siap, sesuaikan iconnya
    document.addEventListener('DOMContentLoaded', () => {
        const icon = document.getElementById('theme-icon');
        if(icon) {
            icon.className = savedTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        }
    });
})();