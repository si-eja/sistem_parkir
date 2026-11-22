@extends('layout.master')

@section('title', 'Daftar Akun Pengendara - E-Parkir UNSIL')

@section('custom-css')
<style>
    :root {
        --unsil-green: #005a32;
        --unsil-green-dark: #003d22;
        --unsil-yellow: #facc15;
    }
    body { background-color: #f4f7f5; }
    .register-card {
        background: #ffffff;
        border: 1px solid #d1e2d8;
        border-top: 5px solid var(--unsil-green);
        border-radius: 1.25rem;
        box-shadow: 0 12px 30px -10px rgba(0, 90, 50, 0.12);
    }
    .btn-unsil-primary {
        background-color: var(--unsil-green);
        color: #ffffff;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-unsil-primary:hover {
        background-color: var(--unsil-green-dark);
        color: var(--unsil-yellow);
    }
    .btn-check:checked + .btn-outline-unsil {
        background-color: var(--unsil-green);
        color: #ffffff;
        border-color: var(--unsil-green);
    }
    .btn-outline-unsil {
        color: var(--unsil-green);
        border-color: #cbd5e1;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="register-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <span class="badge px-3 py-2 rounded-pill mb-2" style="background-color: var(--unsil-yellow); color: #062818; font-weight: 700;">
                        <i class="bi bi-qr-code-scan me-1"></i> REGISTRASI AKSES PARKIR
                    </span>
                    <h4 class="fw-bold text-dark mb-1">Buat Akun E-Parkir UNSIL</h4>
                    <p class="text-muted small">Dapatkan Kartu QR permanen untuk akses masuk & keluar gerbang kampus</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 small">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.proses') }}" method="POST">
                    @csrf

                    <!-- PILIHAN KATEGORI CIVITAS (MAHASISWA / DOSEN) -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Status Civitas Akademika <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="kategori" id="katMahasiswa" value="MAHASISWA" 
                                       {{ old('kategori', 'MAHASISWA') == 'MAHASISWA' ? 'checked' : '' }} onchange="toggleLabelIdentitas()">
                                <label class="btn btn-outline-unsil w-100 py-2 rounded-3 fw-semibold" for="katMahasiswa">
                                    <i class="bi bi-mortarboard-fill me-1"></i> Mahasiswa
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="kategori" id="katDosen" value="DOSEN" 
                                       {{ old('kategori') == 'DOSEN' ? 'checked' : '' }} onchange="toggleLabelIdentitas()">
                                <label class="btn btn-outline-unsil w-100 py-2 rounded-3 fw-semibold" for="katDosen">
                                    <i class="bi bi-person-workspace me-1"></i> Dosen / Staf
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- NAMA LENGKAP -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="Masukkan nama lengkap beserta gelar (jika dosen)" value="{{ old('name') }}" required>
                    </div>

                    <!-- NIM / NIP (OPSIONAL) -->
                    <div class="mb-3">
                        <label id="labelIdentitas" class="form-label small fw-semibold text-muted">NPM / NIM Mahasiswa (Opsional)</label>
                        <input type="text" id="inputIdentitas" name="nomor_identitas" class="form-control rounded-3 font-monospace" placeholder="Contoh: 227006001" value="{{ old('nomor_identitas') }}">
                    </div>

                    <!-- EMAIL -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control rounded-3" placeholder="nama@student.unsil.ac.id / @unsil.ac.id" value="{{ old('email') }}" required>
                    </div>

                    <!-- PLAT NOMOR & JENIS KENDARAAN -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label small fw-semibold text-muted">Plat Nomor Kendaraan <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="plat_nomor" 
                                   class="form-control text-uppercase fw-bold rounded-3" 
                                   placeholder="Contoh: Z1234AB"
                                   maxlength="11"
                                   pattern="[A-Za-z]{1,2}\s?[1-9][0-9]{0,3}\s?[A-Za-z]{1,3}"
                                   title="Format plat nomor Indonesia yang valid (1-2 huruf depan, 1-4 angka, 1-3 huruf belakang. Contoh: Z1234AB)"
                                   oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                   value="{{ old('plat_nomor') }}" 
                                   required>
                            <small class="text-muted" style="font-size: 0.72rem;">Tanpa spasi (Contoh: Z1234AB)</small>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold text-muted">Jenis Kendaraan <span class="text-danger">*</span></label>
                            <select name="jenis_kendaraan" class="form-select rounded-3 fw-semibold" required>
                                <option value="MOTOR" {{ old('jenis_kendaraan') == 'MOTOR' ? 'selected' : '' }}>Motor</option>
                                <option value="MOBIL" {{ old('jenis_kendaraan') == 'MOBIL' ? 'selected' : '' }}>Mobil</option>
                            </select>
                        </div>
                    </div>

                    <!-- PASSWORD & KONFIRMASI -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control rounded-3" placeholder="Minimal 6 karakter" minlength="6" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="Ulangi password" minlength="6" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-unsil-primary w-100 py-2.5 rounded-3 fw-bold mb-3">
                        <i class="bi bi-check-circle-fill me-1"></i> Daftar & Buat Kartu QR
                    </button>

                    <div class="text-center small text-muted">
                        Sudah punya akun E-Parkir? <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: var(--unsil-green);">Masuk di sini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleLabelIdentitas() {
        const isDosen = document.getElementById('katDosen').checked;
        const label = document.getElementById('labelIdentitas');
        const input = document.getElementById('inputIdentitas');
        if (isDosen) {
            label.innerText = 'NIP / NIDN Dosen (Opsional)';
            input.placeholder = 'Contoh: 198501012015041001';
        } else {
            label.innerText = 'NPM / NIM Mahasiswa (Opsional)';
            input.placeholder = 'Contoh: 227006001';
        }
    }
    document.addEventListener('DOMContentLoaded', toggleLabelIdentitas);
</script>
@endsection