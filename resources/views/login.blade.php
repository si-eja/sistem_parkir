@extends('layout.master')

@section('title', 'Login - E-Parkir')

@section('content')
<div class="row justify-content-center my-4" data-aos="zoom-in">
    <div class="col-md-5 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
            
            <div class="text-center mb-4">
                <span class="fs-1">🅿️</span>
                <h4 class="fw-bold text-dark mt-2 mb-1">Masuk E-Parkir</h4>
                <p class="text-muted small">Silakan login untuk mengakses sistem</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 p-3 mb-3 small">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 p-3 mb-3 small">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.proses') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Alamat Email</label>
                    <input type="email" name="email" class="form-control form-control-lg rounded-3" placeholder="nama@domain.com" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg rounded-3" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-3 fw-semibold shadow-sm mb-3">
                    Masuk Sekarang
                </button>

                <div class="text-center small text-muted">
                    Pengendara baru? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Daftar Akun</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection