@extends('layout.master')

@section('title', 'Check-In Pengendara Mandiri - E-Parkir')

@section('custom-css')
<style>
    .kuota-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #e2e8f0;
    }
    .kuota-number { 
        font-size: 4rem; 
        font-weight: 800; 
        line-height: 1;
        margin: 10px 0 5px 0;
    }
    .text-success-custom { color: #16a34a; }
    .text-warning-custom { color: #d97706; }
    .text-danger-custom { color: #dc2626; }
</style>
@endsection

@section('content')
<!-- MENGGUNAKAN LAYOUT FULL COL-MD-12 -->
<div class="row justify-content-center" data-aos="zoom-in">
    <div class="col-md-12 col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">

            <!-- NAVIGASI KEMBALI KE LANDING PAGE -->
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <a href="{{ route('landing') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Beranda
                </a>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill small fw-semibold">
                    <i class="bi bi-qr-code-scan me-1"></i> Parkir Mandiri
                </span>
            </div>

            <!-- FITUR 1: DROPDOWN PILIH AREA FAKULTAS -->
            <div class="mb-4 text-start">
                <label class="form-label text-muted small fw-semibold mb-1">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>Pilih Lokasi Area Parkir:
                </label>
                <select class="form-select form-select-lg fw-bold border-secondary border-opacity-25 rounded-3" 
                        id="selectArea" 
                        onchange="location = this.value;">
                    @foreach($semua_area as $item)
                        <option value="{{ route('parkir.index', ['area_id' => $item->id]) }}" 
                                {{ $area && $area->id == $item->id ? 'selected' : '' }}>
                            📍 {{ $item->nama_area }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- NOTIFIKASI ERROR / SUCCESS -->
            @if(session('success'))
                <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 p-3 mb-4 text-start small d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 p-3 mb-4 text-start small d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <!-- GRID DUA KOLOM DI DALAM CARD (MD/LG) -->
            <div class="row g-4 align-items-stretch">
                
                <!-- KOLOM KIRI: STATISTIK KUOTA SLOT PARKIR -->
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="kuota-box text-center h-100 d-flex flex-column justify-content-center">
                        <span class="text-muted small d-block text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Sisa Ketersediaan Slot</span>
                        
                        @php
                            $persentaseTerisi = $area->kapasitas_total > 0 ? (($area->kapasitas_total - $sisa_kuota) / $area->kapasitas_total) * 100 : 100;
                            
                            // Indikator Warna Dinamis
                            if ($sisa_kuota <= 0) {
                                $colorClass = 'text-danger-custom';
                                $progressClass = 'bg-danger';
                            } elseif ($persentaseTerisi >= 80) {
                                $colorClass = 'text-warning-custom';
                                $progressClass = 'bg-warning';
                            } else {
                                $colorClass = 'text-success-custom';
                                $progressClass = 'bg-success';
                            }
                        @endphp

                        <div class="kuota-number {{ $colorClass }}">{{ $sisa_kuota }}</div>
                        <p class="text-muted small mb-3">dari total <strong>{{ $area->kapasitas_total }}</strong> Slot Kosong</p>

                        <!-- FITUR 2: PROGRESS BAR KAPASITAS PARKIR -->
                        <div class="progress rounded-pill style-progress" style="height: 10px; background-color: #e2e8f0;">
                            <div class="progress-bar {{ $progressClass }} rounded-pill" 
                                 role="progressbar" 
                                 style="width: {{ $persentaseTerisi }}%;" 
                                 aria-valuenow="{{ $persentaseTerisi }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mt-2" style="font-size: 0.75rem;">
                            <span>Terisi: {{ $area->kapasitas_total - $sisa_kuota }}</span>
                            <span>Kosong: {{ $sisa_kuota }}</span>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: FORM CHECK-IN MANDIRI -->
                <div class="col-lg-7 col-md-6 col-12">
                    <div class="border rounded-4 p-4 h-100 d-flex flex-column justify-content-between bg-light">
                        <form action="{{ route('parkir.checkin') }}" method="POST" class="text-start">
                            @csrf
                            <input type="hidden" name="area_id" value="{{ $area->id }}">
                            <input type="hidden" name="input_oleh" value="MANDIRI">

                            <div class="mb-4">
                                <label for="plat_nomor" class="form-label font-weight-600 text-secondary small fw-semibold">
                                    Masukkan Plat Nomor Kendaraan
                                </label>
                                <div class="input-group input-group-lg border rounded-3 overflow-hidden bg-white shadow-sm">
                                    <span class="input-group-text bg-white border-0 text-muted">
                                        <i class="bi bi-car-front-fill fs-5"></i>
                                    </span>
                                    <input type="text" 
                                           id="plat_nomor" 
                                           name="plat_nomor" 
                                           class="form-control border-0 text-uppercase fw-bold fs-5 shadow-none" 
                                           placeholder="Contoh: Z 1234 AB" 
                                           value="{{ old('plat_nomor') }}" 
                                           required 
                                           autocomplete="off" 
                                           style="letter-spacing: 1.5px;">
                                </div>
                                <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">
                                    *Pastikan plat nomor diisi dengan benar sesuai fisik kendaraan.
                                </small>
                            </div>

                            <button type="submit" 
                                    class="btn btn-primary btn-lg w-100 py-3 fw-semibold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" 
                                    {{ $sisa_kuota <= 0 ? 'disabled' : '' }}>
                                @if($sisa_kuota <= 0)
                                    <i class="bi bi-x-circle-fill fs-5"></i>
                                    <span>Maaf, Parkiran Penuh</span>
                                @else
                                    <i class="bi bi-box-arrow-in-right fs-5"></i>
                                    <span>Mulai Parkir (Check-In)</span>
                                @endif
                            </button>
                        </form>

                        <!-- FITUR 3: PETUNJUK RINGKAS PENGENDARA -->
                        <div class="mt-4 pt-3 border-top text-center text-muted small">
                            <i class="bi bi-info-circle me-1 text-primary"></i> 
                            Ingat jam masuk Anda! Anda bisa cek durasi parkir real-time di 
                            <a href="{{ route('landing') }}" class="text-decoration-none fw-semibold">Halaman Utama</a>.
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection