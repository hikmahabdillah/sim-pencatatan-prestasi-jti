<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="#" target="_blank">
            <img src="{{ asset('image/LogoTalentiTransparent.png') }}" width="50px" height="50px" class="h-100"
                style="max-height: 60px!important; width: 60px" alt="main_logo">
            <span class="ms-1 fs-5 font-weight-bold">Talenti</span>
        </a>
        <!-- Minimize Button -->
        <div class="position-absolute end-4 top-105 d-none d-xl-block bg-white z-index-2">
            <button
                class="bg-white btn btn-sm btn-icon-only btn-rounded btn-outline-secondary mb-0 p-2 d-md-flex justify-content-md-center align-items-md-center"
                id="minimizeSidebar">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </button>
        </div>
    </div>
    <hr class="horizontal dark mt-4">
    <div class="collapse navbar-collapse w-auto" style="height: auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link active" href="/">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard(all user)</span>
                </a>
            </li>

            <!-- Administrasi -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Administrasi (admin)</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/mahasiswa">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Manajemen Mahasiswa">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen Mahasiswa</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/dospem">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Manajemen Dosen Pembimbing">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen Dosen Pembimbing</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admin">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Manajemen Admin">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen Admin</span>
                </a>
            </li>

            <!-- Kelompok Prestasi Mahasiswa -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Prestasi Mahasiswa</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/prestasi">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Prestasi Mahasiswa">
                        <i class="ni ni-trophy text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Prestasi Mahasiswa(admin-dospem)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/verifikasi-prestasi">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Verifikasi Prestasi">
                        <i class="ni ni-check-bold text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Verifikasi Prestasi(admin-dospem)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/tingkat_prestasi">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Manajemen Tingkat Prestasi">
                        <i class="ni ni-chart-bar-32 text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen Tingkat Prestasi(admin)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/laporan-prestasi">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Laporan Prestasi">
                        <i class="ni ni-chart-bar-32 text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Laporan Prestasi(admin-mahasiswa)</span>
                </a>
            </li>

            <!-- Kelompok Lomba -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Lomba</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/lomba">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Lomba">
                        <i class="ni ni-istanbul text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Lomba(dospem-mahasiswa)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/manajemen-lomba">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Manajemen Lomba">
                        <i class="ni ni-settings text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen Lomba(admin)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/kategori">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Manajemen Kategori">
                        <i class="ni ni-settings text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen Kategori(admin)</span>
                </a>
            </li>

            <!-- Lainnya -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Lainnya (admin)</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/role">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Manajemen Role">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen Role</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/prodi">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Manajemen Program Studi">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen Program Studi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/periode">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Manajemen Periode">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Manajemen periode</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<!-- JavaScript for Sidebar Toggle -->
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const minimizeBtn = document.getElementById('minimizeSidebar');
            const sidebar = document.getElementById('sidenav-main');
            const mainContent = document.querySelector('.main-content');
            const navLinks = document.querySelectorAll('.nav-link-text');
            const navHeaders = document.querySelectorAll('.nav-item h6');
            const brandText = document.querySelector('.navbar-brand span');

            minimizeBtn.addEventListener('click', function() {
                sidebar.classList.toggle('mini-sidebar');

                // Toggle text visibility
                navLinks.forEach(link => link.classList.toggle('d-none'));
                navHeaders.forEach(header => header.classList.toggle('d-none'));
                brandText.classList.toggle('d-none');

                // Toggle icon
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-chevron-left');
                icon.classList.toggle('fa-chevron-right');

                // Toggle width of sidebar
                if (sidebar.classList.contains('mini-sidebar')) {
                    sidebar.style.width = '80px';
                    // Apply margin only if screen width >= 1200px
                    if (window.innerWidth >= 1200) {
                        mainContent.style.setProperty('margin-left', '6.125rem', 'important');
                    }
                } else {
                    sidebar.style.width = '';
                    mainContent.style.setProperty('margin-left', '', 'important');
                }

                // Optional: reapply on resize
                window.addEventListener('resize', () => {
                    if (!sidebar.classList.contains('mini-sidebar')) return;
                    if (window.innerWidth >= 1200) {
                        mainContent.style.setProperty('margin-left', '6.125rem', 'important');
                    } else {
                        mainContent.style.setProperty('margin-left', '', 'important');
                    }
                });
            });
        });
    </script>
@endpush
<!-- CSS for Sidebar -->
@push('css')
    <style>
        /* Add these styles to your CSS */
        .mini-sidebar .nav-link {
            justify-content: center;
        }

        .mini-sidebar .icon {
            margin-right: 0 !important;
        }

        .mini-sidebar .navbar-brand {
            justify-content: center;
        }

        @media (min-width: 1200px) {
            .mini-sidebar {
                width: 80px !important;
            }
        }
    </style>
@endpush
