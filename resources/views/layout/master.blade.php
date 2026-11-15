<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Parkir Digital Universitas Siliwangi')</title>

    <link rel="stylesheet" href="{{ asset('aos-master/dist/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --unsil-green: #005a32;
            --unsil-green-dark: #003d22;
            --unsil-yellow: #facc15;
            --unsil-yellow-hover: #eab308;
            --unsil-red: #dc2626;
        }

        body {
            background-color: #f4f7f5;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header Nuansa Hijau Khas UNSIL + Garis Aksen Kuning */
        header {
            background: linear-gradient(90deg, var(--unsil-green-dark) 0%, var(--unsil-green) 100%);
            color: #fff;
            padding: 15px 0;
            border-bottom: 3px solid var(--unsil-yellow);
            box-shadow: 0 4px 12px rgba(0, 61, 34, 0.15);
        }

        header h1 {
            font-size: 1.3rem;
            margin: 0;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        header h1 a {
            color: #ffffff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        header h1 a span.brand-highlight {
            color: var(--unsil-yellow);
        }

        /* Tombol Navigasi Header */
        .btn-nav-yellow {
            background-color: var(--unsil-yellow);
            border-color: var(--unsil-yellow);
            color: #062818;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .btn-nav-yellow:hover {
            background-color: var(--unsil-yellow-hover);
            border-color: var(--unsil-yellow-hover);
            color: #000000;
            transform: translateY(-1px);
        }

        .btn-nav-outline-yellow {
            color: #ffffff;
            border: 1px solid rgba(250, 204, 21, 0.5);
            background: rgba(255, 255, 255, 0.08);
            transition: all 0.2s ease;
        }

        .btn-nav-outline-yellow:hover {
            background-color: rgba(250, 204, 21, 0.18);
            border-color: var(--unsil-yellow);
            color: var(--unsil-yellow);
        }

        /* Badge User Aktif */
        .badge-user-unsil {
            background-color: rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(250, 204, 21, 0.4);
            color: #ffffff;
        }

        /* Sentuhan Merah Sangat Minimal untuk Tombol Logout */
        .btn-logout-minimal {
            background-color: rgba(220, 38, 38, 0.18);
            border: 1px solid rgba(248, 113, 113, 0.5);
            color: #fecaca;
            transition: all 0.2s ease;
        }

        .btn-logout-minimal:hover {
            background-color: var(--unsil-red);
            border-color: var(--unsil-red);
            color: #ffffff;
        }

        .main-container {
            flex: 1;
            margin: 0 auto;
            width: 100%;
        }

        /* Footer Nuansa Hijau Gelap UNSIL */
        footer {
            background-color: var(--unsil-green-dark);
            color: #cbd5e1;
            text-align: center;
            padding: 18px 15px;
            font-size: 0.88rem;
            margin-top: auto;
            border-top: 3px solid var(--unsil-yellow);
        }

        footer span.accent-unsil {
            color: var(--unsil-yellow);
            font-weight: 600;
        }
    </style>
    @yield('custom-css')
</head>
<body>
    <header>
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
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <h1>
                    <a href="{{ route('landing') }}">
                        <i class="bi bi-p-square-fill" style="color: var(--unsil-yellow); font-size: 1.5rem;"></i>
                        <span>E-PARKIR <span class="brand-highlight">UNSIL</span></span>
                    </a>
                </h1>
            </div>
    
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <span style="color: #bbf7d0; font-size: 0.85rem; font-weight: 500;" class="d-none d-md-inline me-2">
                    Universitas Siliwangi
                </span>
    
                {{-- MENU STATUS AUTENTIKASI USER / PETUGAS --}}
                @auth
                    @php
                        $role = Auth::user()->role;
                        $targetRoute = route('pengendara.dashboard');
                        if ($role === 'ADMIN') {
                            $targetRoute = route('admin.dashboard');
                        } elseif ($role === 'JUKIR') {
                            $targetRoute = route('jukir.dashboard');
                        }
                    @endphp

                    <div class="d-flex align-items-center gap-2">
                        <!-- Link Cepat ke Dashboard Sesuai Role -->
                        <a href="{{ $targetRoute }}" class="btn btn-nav-yellow btn-sm rounded-pill px-3 shadow-sm">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>

                        <span class="badge badge-user-unsil px-3 py-2 rounded-pill small d-none d-sm-inline fw-medium">
                            <i class="bi bi-person-circle text-warning me-1"></i> {{ Auth::user()->name }} ({{ $role }})
                        </span>

                        <!-- Aksen Merah Minimal Khusus Tombol Logout -->
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-logout-minimal btn-sm rounded-pill px-3 fw-semibold">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Jika Pengunjung Belum Login -->
                    <a href="{{ route('login') }}" class="btn btn-nav-outline-yellow btn-sm rounded-pill px-3 fw-semibold">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-nav-yellow btn-sm rounded-pill px-3 shadow-sm">
                        <i class="bi bi-person-plus me-1"></i> Daftar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <div class="main-container">
        @yield('content')
    </div>

    <footer>
        <p class="m-0">&copy; 2026 <span class="accent-unsil">E-Parkir Kampus Universitas Siliwangi</span>. All rights reserved.</p>
    </footer>

    <script src="{{ asset('aos-master/dist/aos.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>