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
        body {
            background-color: #f4f6f9;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        header {
            background-color: #1e293b;
            color: #fff;
            padding: 15px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        header h1 {
            font-size: 1.3rem;
            margin: 0;
            font-weight: 700;
        }
        header h1 a {
            color: #ffffff;
            text-decoration: none;
        }
        .main-container {
            flex: 1;
            padding: 30px 15px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        footer {
            background-color: #1e293b;
            color: #94a3b8;
            text-align: center;
            padding: 18px 15px;
            font-size: 0.9rem;
            margin-top: auto;
        }
    </style>
    @yield('custom-css')
</head>
<body>

    <header>
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <h1><a href="{{ route('landing') }}">🅿️ E-PARKIR DIGITAL</a></h1>
            </div>
    
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <span style="color: #94a3b8; font-size: 0.85rem;" class="d-none d-md-inline me-2">Universitas Siliwangi</span>
    
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
                        <a href="{{ $targetRoute }}" class="btn btn-outline-light btn-sm rounded-pill px-3">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>

                        <span class="badge bg-primary bg-opacity-25 border border-primary border-opacity-50 text-white px-3 py-2 rounded-pill small d-none d-sm-inline">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }} ({{ $role }})
                        </span>

                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 fw-semibold">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Jika Pengunjung Belum Login -->
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold shadow-sm">
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
        <p class="m-0">&copy; 2026 E-Parkir Kampus Universitas Siliwangi. All rights reserved.</p>
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