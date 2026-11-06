@extends('layout.master')

@section('title', 'Login Petugas - E-Parkir')

@section('custom-css')
<style>
    .login-wrapper {
        max-width: 420px;
        margin: 40px auto;
    }
    .login-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        padding: 2rem;
    }
    .input-group-text-custom {
        background-color: #f8fafc;
        border-right: none;
        color: #64748b;
    }
    .form-control-custom {
        border-left: none;
        background-color: #f8fafc;
        font-size: 0.95rem;
        text-transform: none !important; /* Mencegah huruf besar otomatis pada email & password */
    }
    .form-control-custom:focus {
        background-color: #ffffff;
        box-shadow: none;
        border-color: #cbd5e1;
    }
    .input-group:focus-within {
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        border-radius: 8px;
    }
    .btn-toggle-pwd {
        background-color: #f8fafc;
        border: 1px solid #cbd5e1;
        border-left: none;
        color: #64748b;
    }
</style>
@endsection

@section('content')
<div class="login-wrapper" data-aos="zoom-in">
    <div class="login-card">
        
        <!-- HEADER LOGIN -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-dark text-white rounded-circle mb-3" style="width: 56px; height: 56px;">
                <i class="bi bi-shield-lock-fill fs-4"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">Akses Petugas</h4>
            <p class="text-muted small mb-0">Masukkan akun terdaftar untuk mengelola sistem parkir</p>
        </div>

        <!-- NOTIFIKASI ERROR (GAGAL LOGIN) -->
        @if(session('error'))
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 small p-3 mb-3 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <!-- NOTIFIKASI SUKSES (SETELAH LOGOUT) -->
        @if(session('success'))
            <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 small p-3 mb-3 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <!-- FORM LOGIN -->
        <form action="{{ route('login.proses') }}" method="POST">
            @csrf
            
            <!-- INPUT EMAIL -->
            <div class="mb-3">
                <label for="email" class="form-label text-secondary fw-semibold small mb-1">Alamat Email</label>
                <div class="input-group">
                    <span class="input-group-text input-group-text-custom">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control form-control-custom @error('email') is-invalid @enderror" 
                           placeholder="nama@parkir.com" 
                           value="{{ old('email') }}" 
                           required 
                           autocomplete="email" 
                           autofocus>
                </div>
                @error('email')
                    <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                @enderror
            </div>

            <!-- INPUT PASSWORD -->
            <div class="mb-4">
                <label for="password" class="form-label text-secondary fw-semibold small mb-1">Kata Sandi (Password)</label>
                <div class="input-group">
                    <span class="input-group-text input-group-text-custom">
                        <i class="bi bi-key"></i>
                    </span>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control form-control-custom" 
                           placeholder="••••••••" 
                           required>
                    <button class="btn btn-toggle-pwd" type="button" id="btnTogglePassword">
                        <i class="bi bi-eye-slash" id="iconToggle"></i>
                    </button>
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <button type="submit" class="btn btn-dark w-100 py-2.5 rounded-3 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2">
                <span>Masuk ke Dashboard</span>
                <i class="bi bi-box-arrow-in-right"></i>
            </button>
        </form>

        <!-- KEMBALI KE LANDING PAGE -->
        <div class="text-center mt-4">
            <a href="{{ route('landing') }}" class="text-decoration-none text-muted small fw-semibold link-primary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Utama
            </a>
        </div>

    </div>
</div>

<!-- SCRIPT TOGGLE SHOW/HIDE PASSWORD -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('btnTogglePassword');
        const passwordInput = document.getElementById('password');
        const iconToggle = document.getElementById('iconToggle');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                
                iconToggle.classList.toggle('bi-eye-slash', !isPassword);
                iconToggle.classList.toggle('bi-eye', isPassword);
            });
        }
    });
</script>
@endsection