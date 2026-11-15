@extends('layout.master')

@section('title', 'Login - E-Parkir Universitas Siliwangi')

@section('custom-css')
<style>
    :root {
        --unsil-green: #005a32;
        --unsil-green-dark: #003d22;
        --unsil-green-light: #e6f4ea;
        --unsil-yellow: #facc15;
        --unsil-yellow-hover: #eab308;
        --unsil-red: #dc2626;
    }

    .login-wrapper {
        border-radius: 1.5rem;
        overflow: hidden;
        border: 1px solid #d1e2d8;
        box-shadow: 0 18px 36px -12px rgba(0, 61, 34, 0.14);
        background-color: #ffffff;
    }

    /* Sisi Kiri: Konten Informasi & Branding Kampus 1 UNSIL */
    .login-brand-side {
        background: linear-gradient(145deg, rgba(0, 61, 34, 0.92) 0%, rgba(0, 90, 50, 0.86) 100%),
                    url('https://unsil.ac.id/wp-content/uploads/2021/04/Rektorat-Unsil.jpg') center/cover no-repeat;
        color: #ffffff;
        position: relative;
        border-right: 4px solid var(--unsil-yellow);
    }

    /* Responsif: Saat di layar HP/Tablet, garis kuning pindah ke bawah sisi konten */
    @media (max-width: 991.98px) {
        .login-brand-side {
            border-right: none;
            border-bottom: 4px solid var(--unsil-yellow);
        }
    }

    .badge-unsil-yellow {
        background-color: var(--unsil-yellow);
        color: #062818;
        font-weight: 700;
    }

    .feature-pill {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(250, 204, 21, 0.28);
        backdrop-filter: blur(6px);
        border-radius: 12px;
        padding: 12px 16px;
    }

    /* Tombol Utama Hijau UNSIL */
    .btn-unsil-primary {
        background-color: var(--unsil-green);
        border-color: var(--unsil-green);
        color: #ffffff;
        transition: all 0.2s ease;
    }
    .btn-unsil-primary:hover {
        background-color: var(--unsil-green-dark);
        border-color: var(--unsil-green-dark);
        color: var(--unsil-yellow);
        transform: translateY(-1px);
    }

    .text-unsil {
        color: var(--unsil-green) !important;
    }

    .form-control:focus {
        border-color: var(--unsil-green);
        box-shadow: 0 0 0 0.2rem rgba(0, 90, 50, 0.15);
    }
</style>
@endsection

@section('content')
<div class="container py-4 py-lg-5" data-aos="fade-up">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="login-wrapper">
                <div class="row g-0 align-items-stretch">

                    <!-- ==================== KOLOM KIRI: KONTEN & BRANDING UNSIL ==================== -->
                    <div class="col-lg-6 login-brand-side p-4 p-md-5 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge badge-unsil-yellow px-3 py-2 rounded-pill mb-3 shadow-sm">
                                <i class="bi bi-shield-check me-1"></i> Universitas Siliwangi
                            </span>

                            <h2 class="fw-bold display-6 mb-3 text-white" style="letter-spacing: -0.02em;">
                                Smart Campus Parking <span style="color: var(--unsil-yellow);">UNSIL</span>
                            </h2>

                            <p class="text-white text-opacity-75 mb-4" style="line-height: 1.6;">
                                Sistem manajemen parkir terpadu untuk sivitas akademika Universitas Siliwangi. Pantau ketersediaan slot secara real-time dan nikmati kemudahan akses QR Code mandiri.
                            </p>

                            <!-- Keunggulan Singkat -->
                            <div class="d-flex flex-column gap-2.5 mb-4">
                                <div class="feature-pill d-flex align-items-center gap-3">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" 
                                         style="width: 38px; height: 38px; background-color: var(--unsil-yellow); color: #062818;">
                                        <i class="bi bi-qr-code fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-white mb-0 small">Identitas QR Digital</h6>
                                        <small class="text-white text-opacity-75" style="font-size: 0.78rem;">Check-in cepat di gerbang fakultas tanpa tiket kertas.</small>
                                    </div>
                                </div>

                                <div class="feature-pill d-flex align-items-center gap-3">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" 
                                         style="width: 38px; height: 38px; background-color: var(--unsil-yellow); color: #062818;">
                                        <i class="bi bi-grid-3x3-gap-fill fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-white mb-0 small">Monitoring Slot Real-Time</h6>
                                        <small class="text-white text-opacity-75" style="font-size: 0.78rem;">Cek kapasitas kosong sebelum memasuki area parkir.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-top border-warning border-opacity-25 d-flex align-items-center justify-content-between small text-white text-opacity-75">
                            <span><i class="bi bi-geo-alt-fill text-warning me-1"></i> Kampus 1 & 2 UNSIL</span>
                            <span><i class="bi bi-check-circle-fill text-warning me-1"></i> Terintegrasi</span>
                        </div>
                    </div>

                    <!-- ==================== KOLOM KANAN: FORM LOGIN ==================== -->
                    <div class="col-lg-6 p-4 p-md-5 bg-white d-flex flex-column justify-content-center">
                        
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-2.5 mb-3" 
                                 style="background-color: var(--unsil-green-light); color: var(--unsil-green);">
                                <i class="bi bi-p-square-fill fs-3"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">Selamat Datang</h3>
                            <p class="text-muted small mb-0">Silakan masuk menggunakan akun terdaftar Anda untuk melanjutkan.</p>
                        </div>

                        @if(session('success'))
                            <div class="alert border-0 rounded-3 p-3 mb-3 small d-flex align-items-center gap-2" 
                                 style="background-color: #e6f4ea; color: #005a32; border-left: 4px solid #005a32 !important;">
                                <i class="bi bi-check-circle-fill fs-6"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        <!-- Sentuhan Merah Sangat Minimal Khusus untuk Pesan Error -->
                        @if(session('error'))
                            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 p-3 mb-3 small d-flex align-items-center gap-2" 
                                 style="border-left: 4px solid #dc2626 !important;">
                                <i class="bi bi-exclamation-triangle-fill fs-6"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        <form action="{{ route('login.proses') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Alamat Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-unsil">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" name="email" 
                                           class="form-control form-control-lg border-start-0 rounded-end-3 @error('email') is-invalid @enderror" 
                                           placeholder="nama@unsil.ac.id" 
                                           value="{{ old('email') }}" required autofocus>
                                </div>
                                @error('email')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label text-muted small fw-semibold">Password</label>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-unsil">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" name="password" id="loginPassword"
                                           class="form-control form-control-lg border-start-0 border-end-0 @error('password') is-invalid @enderror" 
                                           placeholder="Masukkan password" required>
                                    <button class="btn btn-light border border-start-0 text-muted" type="button" id="togglePassword" title="Tampilkan/Sembunyikan Password">
                                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-unsil-primary btn-lg w-100 py-3 rounded-3 fw-semibold shadow-sm mb-3 d-flex align-items-center justify-content-center gap-2">
                                <span>Masuk Sekarang</span>
                                <i class="bi bi-box-arrow-in-right text-warning"></i>
                            </button>

                            <div class="text-center small text-muted pt-2">
                                Pengendara baru? 
                                <a href="{{ route('register') }}" class="text-decoration-none fw-bold text-unsil">
                                    Daftar Akun Sekarang
                                </a>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fitur Toggle Lihat / Sembunyikan Password
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('loginPassword');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.classList.toggle('bi-eye');
                toggleIcon.classList.toggle('bi-eye-slash');
            });
        }
    });
</script>
@endsection