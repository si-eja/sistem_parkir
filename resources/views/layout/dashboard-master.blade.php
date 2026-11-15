<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Petugas - E-Parkir UNSIL')</title>

    <link rel="stylesheet" href="{{ asset('aos-master/dist/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --unsil-green: #005a32;
            --unsil-green-dark: #003d22;
            --unsil-green-light: #e6f4ea;
            --unsil-yellow: #facc15;
            --unsil-yellow-hover: #eab308;
            --unsil-red: #dc2626;
        }

        body {
            background-color: #f4f7f5;
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

        /* Sidebar Styling (Desktop) - Nuansa Hijau Gelap UNSIL */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, var(--unsil-green-dark) 0%, var(--unsil-green) 100%);
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
            border-right: 3px solid var(--unsil-yellow);
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
            border-radius: 1.25rem;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 90, 50, 0.05);
            border: 1px solid #d1e2d8;
        }

        /* Navigasi Menu Sidebar */
        .nav-link-custom {
            color: #d1fae5;
            padding: 11px 15px;
            border-radius: 10px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .nav-link-custom:hover {
            color: var(--unsil-yellow);
            background-color: rgba(255, 255, 255, 0.08);
        }

        .nav-link-custom.active {
            color: #062818;
            background-color: var(--unsil-yellow);
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        /* Aksen Merah Sangat Minimal untuk Tombol Logout */
        .btn-logout-minimal {
            background-color: rgba(220, 38, 38, 0.2);
            border: 1px solid rgba(248, 113, 113, 0.45);
            color: #fecaca;
            transition: all 0.2s ease;
        }

        .btn-logout-minimal:hover {
            background-color: var(--unsil-red);
            border-color: var(--unsil-red);
            color: #ffffff;
        }

        /* Mobile Header & Offcanvas Nuansa UNSIL */
        .mobile-header-unsil {
            background: linear-gradient(90deg, var(--unsil-green-dark) 0%, var(--unsil-green) 100%);
            border-bottom: 3px solid var(--unsil-yellow);
        }

        .offcanvas-unsil {
            background: linear-gradient(180deg, var(--unsil-green-dark) 0%, var(--unsil-green) 100%);
            border-right: 3px solid var(--unsil-yellow) !important;
        }

        /* RESPONSIVE MEDIA QUERIES (DESKTOP vs MOBILE) */
        @media (max-width: 991.98px) {
            .sidebar-desktop {
                display: none !important;
            }
            .main-wrapper {
                margin-left: 0 !important;
            }
            .mobile-header {
                display: flex !important;
            }
        }

        @media (min-width: 992px) {
            .mobile-header {
                display: none !important;
            }
        }
    </style>
    @yield('custom-css')
</head>
<body>
<!-- NOTIFIKASI SYSTEM FLOATING (BERLAKU UNTUK SEMUA HALAMAN) -->
@if(session('success') || session('error') || session('warning') || session('info'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080; margin-top: 72px; max-width: 420px; width: 100%; pointer-events: none;">
        
        @if(session('success'))
            <div class="alert alert-dismissible fade show border-0 rounded-4 p-3 mb-2 shadow-lg d-flex align-items-center justify-content-between auto-close-alert" 
                 style="background-color: #e6f4ea; color: #005a32; border-left: 5px solid #005a32 !important; pointer-events: auto;" 
                 role="alert" data-aos="fade-left">
                <div class="d-flex align-items-center gap-2 pe-2">
                    <i class="bi bi-check-circle-fill fs-5 flex-shrink-0"></i>
                    <div>
                        <h6 class="fw-bold mb-0 small">Berhasil!</h6>
                        <span class="small">{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-dismissible fade show border-0 rounded-4 p-3 mb-2 shadow-lg d-flex align-items-center justify-content-between auto-close-alert" 
                 style="background-color: #fefce8; color: #854d0e; border-left: 5px solid #facc15 !important; pointer-events: auto;" 
                 role="alert" data-aos="fade-left">
                <div class="d-flex align-items-center gap-2 pe-2">
                    <i class="bi bi-exclamation-circle-fill fs-5 flex-shrink-0" style="color: #eab308;"></i>
                    <div>
                        <h6 class="fw-bold mb-0 small">Informasi</h6>
                        <span class="small">{{ session('warning') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-dismissible fade show border-0 rounded-4 p-3 mb-2 shadow-lg d-flex align-items-center justify-content-between auto-close-alert" 
                 style="background-color: #fef2f2; color: #b91c1c; border-left: 4px solid #dc2626 !important; pointer-events: auto;" 
                 role="alert" data-aos="fade-left">
                <div class="d-flex align-items-center gap-2 pe-2">
                    <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
                    <div>
                        <h6 class="fw-bold mb-0 small">Perhatian!</h6>
                        <span class="small">{{ session('error') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                document.querySelectorAll('.auto-close-alert').forEach(function(alertEl) {
                    if (typeof bootstrap !== 'undefined') {
                        let bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
                        bsAlert.close();
                    } else {
                        alertEl.remove();
                    }
                });
            }, 4000);
        });
    </script>
@endif
    <div class="wrapper">
        <!-- ==================== 1. SIDEBAR FIXED (TAMPIL DI DESKTOP / LG+) ==================== -->
        <aside class="sidebar sidebar-desktop">
            <div>
                <!-- Brand Header -->
                <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom border-warning border-opacity-25">
                    <i class="bi bi-p-square-fill" style="color: var(--unsil-yellow); font-size: 1.75rem;"></i>
                    <div>
                        <h1 class="fs-6 fw-bold m-0 text-white tracking-wide">
                            E-PARKIR <span style="color: var(--unsil-yellow);">UNSIL</span>
                        </h1>
                        <small style="color: #bbf7d0; font-size: 0.72rem;">Panel Manajemen Kampus</small>
                    </div>
                </div>

                <!-- Navigation Menu Links -->
                <nav class="d-flex flex-column gap-2">
                    @if(Auth::user()->role === 'JUKIR')
                        <!-- MENU PAGE 1: POS CHECK-IN & SCAN -->
                        <a href="{{ route('jukir.dashboard', ['area_id' => request('area_id')]) }}" 
                        class="nav-link-custom {{ request()->routeIs('jukir.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-qr-code-scan fs-5"></i>
                            <span>POS Scan & Check-In</span>
                        </a>

                        <!-- MENU PAGE 2: MONITORING SLOT LAPANGAN -->
                        <a href="{{ route('jukir.monitoringjukir', ['area_id' => request('area_id')]) }}" 
                        class="nav-link-custom {{ request()->routeIs('jukir.monitoring') ? 'active' : '' }}">
                            <i class="bi bi-grid-3x3-gap-fill fs-5"></i>
                            <span>Monitoring Slot Lapangan</span>
                        </a>
                    @elseif(Auth::user()->role === 'ADMIN')
                        <!-- MENU UNTUK ADMIN -->
                        <a href="{{ route('admin.dashboard') }}" 
                        class="nav-link-custom {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.area.slots') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 fs-5"></i>
                            <span>Dashboard Utama</span>
                        </a>

                        <!-- MENU PAGE DATA PETUGAS PARKIR -->
                        <a href="{{ route('admin.petugas') }}" 
                        class="nav-link-custom {{ request()->routeIs('admin.petugas*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill fs-5"></i>
                            <span>Data Petugas Parkir</span>
                        </a>
                    @endif

                    <hr class="border-warning border-opacity-25 my-1">

                    <a href="{{ route('landing') }}" class="nav-link-custom">
                        <i class="bi bi-globe2 fs-5"></i>
                        <span>Lihat Landing Page</span>
                    </a>
                </nav>
            </div>

            <!-- Profile Info & Logout -->
            <div class="pt-3 border-top border-warning border-opacity-25">
                <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-3" style="background: rgba(0, 0, 0, 0.2);">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" 
                         style="width: 40px; height: 40px; background-color: var(--unsil-yellow); color: #062818;">
                        <i class="bi bi-person-fill fs-5"></i>
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="text-white m-0 text-truncate fw-semibold small">{{ Auth::user()->name ?? 'Petugas Lapangan' }}</h6>
                        <span class="badge rounded-pill mt-1" 
                              style="font-size: 0.65rem; background-color: rgba(250, 204, 21, 0.2); color: var(--unsil-yellow); border: 1px solid rgba(250, 204, 21, 0.4);">
                            {{ Auth::user()->role ?? 'JUKIR' }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-logout-minimal w-100 btn-sm py-2 rounded-3 fw-semibold d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout / Keluar</span>
                    </button>
                </form>
            </div>
        </aside>


        <!-- ==================== MAIN CONTENT WRAPPER ==================== -->
        <div class="main-wrapper">

            <!-- NAVBAR DENGAN TOGGLE MENU (TAMPIL DI MOBILE / LG- LOWER) -->
            <header class="mobile-header mobile-header-unsil navbar navbar-dark px-3 py-2 shadow-sm">
                <div class="container-fluid p-0 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-p-square-fill" style="color: var(--unsil-yellow); font-size: 1.4rem;"></i>
                        <h1 class="fs-6 fw-bold m-0 text-white">
                            E-PARKIR <span style="color: var(--unsil-yellow);">UNSIL</span>
                        </h1>
                    </div>

                    <!-- Tombol Trigger Offcanvas Menu Mobile -->
                    <button class="navbar-toggler border-warning border-opacity-50 shadow-none p-1" 
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
            <footer class="bg-white text-muted text-center py-3 border-top mt-auto small" style="border-top-color: #d1e2d8 !important;">
                &copy; 2026 <strong style="color: var(--unsil-green);">E-Parkir Universitas Siliwangi</strong>. All rights reserved.
            </footer>

        </div>
    </div>


    <!-- ==================== 2. OFFCANVAS SIDEBAR (DRAWER UNTUK MOBILE) ==================== -->
    <div class="offcanvas offcanvas-start offcanvas-unsil text-white" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
        <div class="offcanvas-header border-bottom border-warning border-opacity-25">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-p-square-fill" style="color: var(--unsil-yellow); font-size: 1.5rem;"></i>
                <h5 class="offcanvas-title fw-bold text-white" id="offcanvasSidebarLabel">
                    E-PARKIR <span style="color: var(--unsil-yellow);">UNSIL</span>
                </h5>
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

                <a href="{{ route('landing') }}" class="nav-link-custom">
                    <i class="bi bi-globe2 fs-5"></i>
                    <span>Lihat Landing Page</span>
                </a>
            </nav>

            <div class="pt-3 border-top border-warning border-opacity-25 mt-auto">
                <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded-3" style="background: rgba(0, 0, 0, 0.2);">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" 
                         style="width: 40px; height: 40px; background-color: var(--unsil-yellow); color: #062818;">
                        <i class="bi bi-person-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="text-white m-0 fw-semibold small">{{ Auth::user()->name ?? 'Petugas Lapangan' }}</h6>
                        <span class="badge rounded-pill mt-1" 
                              style="font-size: 0.65rem; background-color: rgba(250, 204, 21, 0.2); color: var(--unsil-yellow); border: 1px solid rgba(250, 204, 21, 0.4);">
                            {{ Auth::user()->role ?? 'JUKIR' }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-logout-minimal w-100 btn-sm py-2 rounded-3 fw-semibold d-flex align-items-center justify-content-center gap-2">
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