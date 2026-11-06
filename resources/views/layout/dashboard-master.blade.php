<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Petugas - E-Parkir')</title>

    <link rel="stylesheet" href="{{ asset('aos-master/dist/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f1f5f9;
            color: #0f172a;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* LAYOUT RESPONSIVE (SIDEBAR DESKTOP + OFFCANVAS MOBILE) */
        .wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Sidebar Styling (Desktop) */
        .sidebar {
            width: 280px;
            background-color: #0f172a;
            color: #ffffff;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            padding: 1.5rem;
            transition: all 0.3s ease-in-out;
        }

        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin-left: 280px; /* Jarak untuk Sidebar Desktop */
            transition: all 0.3s ease-in-out;
        }

        .main-dashboard {
            flex: 1;
            padding: 30px 20px;
            width: 100%;
            max-width: 1300px;
            margin: 0 auto;
        }

        .dash-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
        }

        .nav-link-custom {
            color: #94a3b8;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* RESPONSIVE MEDIA QUERIES (DESKTOP vs MOBILE) */
        @media (max-width: 991.98px) {
            /* Sembunyikan Sidebar fixed bawaan di layar HP/Tablet */
            .sidebar-desktop {
                display: none !important;
            }

            /* Reset margin main-wrapper di mobile */
            .main-wrapper {
                margin-left: 0 !important;
            }

            /* Tampilkan Navbar Atas khusus Mobile */
            .mobile-header {
                display: flex !important;
            }
        }

        @media (min-width: 992px) {
            /* Sembunyikan Navbar Atas khusus Mobile di Desktop */
            .mobile-header {
                display: none !important;
            }
        }
    </style>
    @yield('custom-css')
</head>
<body>

    <div class="wrapper">

        <!-- ==================== 1. SIDEBAR FIXED (TAMPIL DI DESKTOP / LG+) ==================== -->
        <aside class="sidebar sidebar-desktop">
            <div>
                <!-- Brand Header -->
                <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom border-secondary border-opacity-25">
                    <span class="fs-3">🅿️</span>
                    <div>
                        <h1 class="fs-6 fw-bold m-0 text-white tracking-wide">E-PARKIR PANEL</h1>
                        <small class="text-white-50" style="font-size: 0.75rem;">Sistem Manajemen Kampus</small>
                    </div>
                </div>

                <!-- Navigation Menu Links -->
                <nav class="d-flex flex-column gap-1">
                    <a href="{{ Auth::user()->role === 'ADMIN' ? route('admin.dashboard') : route('jukir.dashboard') }}" 
                       class="nav-link-custom active">
                        <i class="bi bi-speedometer2 fs-5"></i>
                        <span>Dashboard Utama</span>
                    </a>
                </nav>
            </div>

            <!-- Profile Info & Logout -->
            <div class="pt-3 border-top border-secondary border-opacity-25">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="bg-primary bg-opacity-25 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-person-fill fs-5"></i>
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="text-white m-0 text-truncate fw-semibold small">{{ Auth::user()->name ?? 'Petugas Lapangan' }}</h6>
                        <span class="badge bg-secondary rounded-pill" style="font-size: 0.65rem;">
                            {{ Auth::user()->role ?? 'JUKIR' }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 btn-sm py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout / Keluar</span>
                    </button>
                </form>
            </div>
        </aside>


        <!-- ==================== MAIN CONTENT WRAPPER ==================== -->
        <div class="main-wrapper">

            <!-- NAVBAR DENGAN TOGGLE MENU (TAMPIL DI MOBILE / LG- LOWER) -->
            <header class="mobile-header navbar navbar-dark bg-dark px-3 py-2 shadow-sm">
                <div class="container-fluid p-0 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-4">🅿️</span>
                        <h1 class="fs-6 fw-bold m-0 text-white">E-PARKIR PANEL</h1>
                    </div>

                    <!-- Tombol Trigger Offcanvas Menu Mobile -->
                    <button class="navbar-toggler border-0 shadow-none p-1" 
                            type="button" 
                            data-bs-toggle="offcanvas" 
                            data-bs-target="#offcanvasSidebar" 
                            aria-controls="offcanvasSidebar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </header>

            <!-- KONTEN UTAMA DASHBOARD -->
            <main class="main-dashboard">
                @yield('content')
            </main>

            <!-- FOOTER -->
            <footer class="bg-white text-muted text-center py-3 border-top mt-auto small">
                &copy; 2026 E-Parkir Universitas Siliwangi
            </footer>

        </div>
    </div>


    <!-- ==================== 2. OFFCANVAS SIDEBAR (DRAWER UNTUK MOBILE) ==================== -->
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
        <div class="offcanvas-header border-bottom border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2">
                <span class="fs-4">🅿️</span>
                <h5 class="offcanvas-title fw-bold text-white" id="offcanvasSidebarLabel">E-PARKIR PANEL</h5>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body d-flex flex-column justify-content-between">
            <nav class="d-flex flex-column gap-2">
                <a href="{{ Auth::user()->role === 'ADMIN' ? route('admin.dashboard') : route('jukir.dashboard') }}" 
                   class="nav-link-custom active">
                    <i class="bi bi-speedometer2 fs-5"></i>
                    <span>Dashboard Utama</span>
                </a>
            </nav>

            <div class="pt-3 border-top border-secondary border-opacity-25 mt-auto">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="bg-primary bg-opacity-25 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-person-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="text-white m-0 fw-semibold small">{{ Auth::user()->name ?? 'Petugas Lapangan' }}</h6>
                        <span class="badge bg-secondary rounded-pill" style="font-size: 0.65rem;">
                            {{ Auth::user()->role ?? 'JUKIR' }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 btn-sm py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout / Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPT UTAMA -->
    <script src="{{ asset('aos-master/dist/aos.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
</body>
</html>