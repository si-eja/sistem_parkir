<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Parkir Digital')</title>

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
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        header h1 {
            font-size: 1.4rem;
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
            padding: 15px;
            font-size: 0.9rem;
            margin-top: auto;
        }
    </style>
    @yield('custom-css')
</head>
<body>

    <header>
        <div class="container d-flex justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <h1><a href="{{ route('landing') }}">🅿️ E-PARKIR DIGITAL</a></h1>
            </div>
    
            <div class="d-flex align-items-center gap-3">
                <span style="color: #94a3b8; font-size: 0.9rem;" class="d-none d-sm-inline">Berpikir Komputasional</span>
    
                {{-- MENU STATUS PETUGAS LOGIN / LOGOUT --}}
                @auth
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary px-3 py-2 text-uppercase">
                            <i class="bi bi-person-fill me-1"></i> {{ Auth::user()->name }} ({{ Auth::user()->role }})
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm rounded-2">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm rounded-2">
                        <i class="bi bi-person-badge me-1"></i> Login Petugas
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <div class="main-container">
        @yield('content')
    </div>

    <footer>
        <p>&copy; 2026 Proyek Kelompok PBL Parkiran</p>
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