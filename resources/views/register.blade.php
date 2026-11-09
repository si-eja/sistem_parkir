@extends('layout.master')

@section('title', 'Daftar Pengendara - E-Parkir')

@section('content')
<div class="row justify-content-center my-4" data-aos="zoom-in">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
            
            <div class="text-center mb-4">
                <span class="fs-1">🅿️</span>
                <h4 class="fw-bold text-dark mt-2 mb-1">Daftar Akun Pengendara</h4>
                <p class="text-muted small">Buat akun untuk mendapatkan QR Code Barcode Parkir Permanen</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 p-3 mb-3 small">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('register.proses') }}" method="POST">
                @csrf
                
                <!-- 1. Nama Lengkap -->
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control rounded-3" placeholder="Masukkan nama Anda" value="{{ old('name') }}" required>
                </div>

                <!-- 2. Email -->
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Alamat Email</label>
                    <input type="email" name="email" class="form-control rounded-3" placeholder="contoh@domain.com" value="{{ old('email') }}" required>
                </div>

                <!-- 3. Password -->
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control rounded-3" placeholder="Minimal 6 karakter" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="Ulangi password" required>
                </div>

                <!-- 4. Plat Nomor Utama -->
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Plat Nomor Utama Kendaraan</label>
                    <input type="text" name="plat_nomor" class="form-control text-uppercase fw-bold rounded-3" placeholder="Contoh: Z 1234 AB" value="{{ old('plat_nomor') }}" required autocomplete="off">
                </div>

                <!-- 5. Jenis Kendaraan -->
                <div class="mb-4">
                    <label class="form-label text-muted small fw-semibold">Jenis Kendaraan Utama</label>
                    <select name="jenis_kendaraan" class="form-select rounded-3 fw-semibold" required>
                        <option value="MOTOR" selected>🛵 Sepeda Motor</option>
                        <option value="MOBIL">🚗 Mobil</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-semibold shadow-sm mb-3">
                    Daftar Akun Sekarang
                </button>

                <div class="text-center small text-muted">
                    Sudah memiliki akun? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Masuk di sini</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection