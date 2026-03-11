<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - SIMA' : 'SIMA - Sistem Informasi Manajemen Asrama'; ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    ```

### 2. Buat File `SIMA/layouts/footer.php`
File ini berisi penutup konten, copyright (opsional), dan script JS.

```php
    <footer class="text-center py-3 text-muted small bg-body border-top" style="margin-left: var(--sidebar-width); transition: margin-left 0.3s;">
        &copy; <?php echo date('Y'); ?> SIMA - Sistem Informasi Manajemen Asrama.
    </footer>

    <script src="../../assets/js/theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const footer = document.querySelector('footer');
            if(sidebar && footer) {
                if(sidebar.classList.contains('collapsed')) {
                    footer.style.marginLeft = 'var(--sidebar-collapsed-width)';
                }
                
                // Observer untuk memantau perubahan class sidebar
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.type === "attributes" && mutation.attributeName === "class") {
                            if(sidebar.classList.contains('collapsed')) {
                                footer.style.marginLeft = 'var(--sidebar-collapsed-width)';
                            } else {
                                footer.style.marginLeft = 'var(--sidebar-width)';
                            }
                        }
                    });
                });
                observer.observe(sidebar, { attributes: true });
            }
        });
    </script>
</body>
</html>