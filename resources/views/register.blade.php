@extends('layout.master')

@section('title', 'Daftar Pengendara - E-Parkir Universitas Siliwangi')

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

    .register-wrapper {
        border-radius: 1.5rem;
        overflow: hidden;
        border: 1px solid #d1e2d8;
        box-shadow: 0 18px 36px -12px rgba(0, 61, 34, 0.14);
        background-color: #ffffff;
    }

    /* Sisi Kiri: Konten Informasi & Branding Kampus 1 UNSIL */
    .register-brand-side {
        background: linear-gradient(145deg, rgba(0, 61, 34, 0.92) 0%, rgba(0, 90, 50, 0.86) 100%),
                    url('https://unsil.ac.id/wp-content/uploads/2021/04/Rektorat-Unsil.jpg') center/cover no-repeat;
        color: #ffffff;
        position: relative;
        border-right: 4px solid var(--unsil-yellow);
    }

    @media (max-width: 991.98px) {
        .register-brand-side {
            border-right: none;
            border-bottom: 4px solid var(--unsil-yellow);
        }
    }

    .badge-unsil-yellow {
        background-color: var(--unsil-yellow);
        color: #062818;
        font-weight: 700;
    }

    .benefit-pill {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(250, 204, 21, 0.28);
        backdrop-filter: blur(6px);
        border-radius: 12px;
        padding: 12px 16px;
    }

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

    .form-control:focus, .form-select:focus {
        border-color: var(--unsil-green);
        box-shadow: 0 0 0 0.2rem rgba(0, 90, 50, 0.15);
    }
</style>
@endsection

@section('content')
<div class="container py-4 py-lg-5" data-aos="fade-up">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="register-wrapper">
                <div class="row g-0 align-items-stretch">

                    <!-- ==================== KOLOM KIRI: KONTEN & KEUNTUNGAN AKUN ==================== -->
                    <div class="col-lg-5 register-brand-side p-4 p-md-5 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge badge-unsil-yellow px-3 py-2 rounded-pill mb-3 shadow-sm">
                                <i class="bi bi-person-badge me-1"></i> Registrasi Pengendara
                            </span>

                            <h2 class="fw-bold h3 mb-3 text-white" style="letter-spacing: -0.02em;">
                                Satu Akun untuk Akses Parkir <span style="color: var(--unsil-yellow);">UNSIL</span>
                            </h2>

                            <p class="text-white text-opacity-75 small mb-4" style="line-height: 1.6;">
                                Daftarkan identitas kendaraan Anda sekali untuk mendapatkan QR Code parkir permanen yang terhubung langsung dengan pos penjagaan kampus.
                            </p>

                            <div class="d-flex flex-column gap-3 mb-4">
                                <div class="benefit-pill d-flex align-items-start gap-3">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 mt-0.5" 
                                         style="width: 34px; height: 34px; background-color: var(--unsil-yellow); color: #062818;">
                                        <i class="bi bi-qr-code fs-6"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-white mb-0 small">QR Code Permanen</h6>
                                        <small class="text-white text-opacity-75" style="font-size: 0.76rem;">Cukup tunjukkan kode QR dari dashboard saat masuk area fakultas.</small>
                                    </div>
                                </div>

                                <div class="benefit-pill d-flex align-items-start gap-3">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 mt-0.5" 
                                         style="width: 34px; height: 34px; background-color: var(--unsil-yellow); color: #062818;">
                                        <i class="bi bi-pin-map-fill fs-6"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-white mb-0 small">Pilih Slot Mandiri</h6>
                                        <small class="text-white text-opacity-75" style="font-size: 0.76rem;">Pesan atau tempati nomor slot yang tersedia secara real-time.</small>
                                    </div>
                                </div>

                                <div class="benefit-pill d-flex align-items-start gap-3">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 mt-0.5" 
                                         style="width: 34px; height: 34px; background-color: var(--unsil-yellow); color: #062818;">
                                        <i class="bi bi-stopwatch fs-6"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-white mb-0 small">Pantau Durasi Real-Time</h6>
                                        <small class="text-white text-opacity-75" style="font-size: 0.76rem;">Lihat lokasi slot dan lama waktu parkir langsung dari ponsel Anda.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-top border-warning border-opacity-25 d-flex align-items-center justify-content-between small text-white text-opacity-75">
                            <span><i class="bi bi-shield-lock-fill text-warning me-1"></i> Data Terenkripsi</span>
                            <span>Universitas Siliwangi</span>
                        </div>
                    </div>

                    <!-- ==================== KOLOM KANAN: FORM REGISTRASI ==================== -->
                    <div class="col-lg-7 p-4 p-md-5 bg-white d-flex flex-column justify-content-center">
                        
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-2.5 mb-2" 
                                 style="background-color: var(--unsil-green-light); color: var(--unsil-green);">
                                <i class="bi bi-p-square-fill fs-4"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">Daftar Akun Pengendara</h3>
                            <p class="text-muted small mb-0">Lengkapi data diri dan informasi kendaraan utama Anda.</p>
                        </div>

                        <!-- Sentuhan Merah Minimalis untuk Pesan Error -->
                        @if(session('error'))
                            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 p-3 mb-3 small d-flex align-items-center gap-2" 
                                 style="border-left: 4px solid #dc2626 !important;">
                                <i class="bi bi-exclamation-triangle-fill fs-6"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        <form action="{{ route('register.proses') }}" method="POST">
                            @csrf
                            
                            <!-- 1. Nama Lengkap -->
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-unsil">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" name="name" 
                                           class="form-control border-start-0 rounded-end-3 @error('name') is-invalid @enderror" 
                                           placeholder="Masukkan nama lengkap Anda" 
                                           value="{{ old('name') }}" required autofocus>
                                </div>
                                @error('name')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- 2. Email -->
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Alamat Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-unsil">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" name="email" 
                                           class="form-control border-start-0 rounded-end-3 @error('email') is-invalid @enderror" 
                                           placeholder="nama@unsil.ac.id" 
                                           value="{{ old('email') }}" required>
                                </div>
                                @error('email')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- 3. Password & Konfirmasi Password (2 Kolom di Desktop) -->
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-semibold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-unsil">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" name="password" id="regPassword"
                                               class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror" 
                                               placeholder="Min. 6 karakter" required>
                                        <button class="btn btn-light border border-start-0 text-muted px-2.5" type="button" id="toggleRegPass" title="Lihat Password">
                                            <i class="bi bi-eye" id="iconRegPass"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-semibold">Konfirmasi Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-unsil">
                                            <i class="bi bi-shield-check"></i>
                                        </span>
                                        <input type="password" name="password_confirmation" id="regPasswordConfirm"
                                               class="form-control border-start-0 rounded-end-3" 
                                               placeholder="Ulangi password" required>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Plat Nomor & Jenis Kendaraan (2 Kolom di Desktop) -->
                            <div class="row g-2 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-semibold">Plat Nomor Utama</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-unsil">
                                            <i class="bi bi-card-text"></i>
                                        </span>
                                        <input type="text" 
                                            name="plat_nomor" 
                                            class="form-control text-uppercase fw-bold rounded-3" 
                                            placeholder="Contoh: Z1234AB"
                                            maxlength="11"
                                            pattern="[A-Za-z]{1,2}\s?[1-9][0-9]{0,3}\s?[A-Za-z]{1,3}"
                                            title="Format plat nomor Indonesia yang valid (1-2 huruf depan, 1-4 angka, 1-3 huruf belakang. Contoh: Z1234AB)"
                                            oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                            value="{{ old('plat_nomor', Auth::user()->plat_nomor ?? '') }}" 
                                            required>
                                    </div>
                                    @error('plat_nomor')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-semibold">Jenis Kendaraan</label>
                                    <select name="jenis_kendaraan" class="form-select rounded-3 fw-semibold @error('jenis_kendaraan') is-invalid @enderror" required>
                                        <option value="MOTOR" {{ old('jenis_kendaraan') == 'MOTOR' ? 'selected' : '' }}>🛵 Sepeda Motor</option>
                                        <option value="MOBIL" {{ old('jenis_kendaraan') == 'MOBIL' ? 'selected' : '' }}>🚗 Mobil</option>
                                    </select>
                                    @error('jenis_kendaraan')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-unsil-primary btn-lg w-100 py-3 rounded-3 fw-semibold shadow-sm mb-3 d-flex align-items-center justify-content-center gap-2">
                                <span>Daftar Akun Sekarang</span>
                                <i class="bi bi-arrow-right text-warning"></i>
                            </button>

                            <div class="text-center small text-muted pt-1">
                                Sudah memiliki akun? 
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-unsil">
                                    Masuk di sini
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
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('toggleRegPass');
        const passInput = document.getElementById('regPassword');
        const confirmInput = document.getElementById('regPasswordConfirm');
        const icon = document.getElementById('iconRegPass');

        if (toggleBtn && passInput) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passInput.getAttribute('type') === 'password';
                const nextType = isPassword ? 'text' : 'password';
                passInput.setAttribute('type', nextType);
                if (confirmInput) confirmInput.setAttribute('type', nextType);
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        }
    });
</script>
@endsection