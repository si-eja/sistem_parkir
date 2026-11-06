@extends('layout.master')

@section('title', 'Smart Campus Parking - Universitas Siliwangi')

@section('custom-css')
<style>
    .tracking-wide { letter-spacing: 1px; }
    .card-hover {
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .card-hover:hover {
        transform: translateY(-6px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.08) !important;
    }
    .hero-gradient {
        background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
    }   
    .backdrop-blur {
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
</style>
@endsection

@section('content')
<!-- ALERT NOTIFIKASI CHECK-OUT / CHECK-IN (SUKSES & GAGAL) -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 bg-success bg-opacity-10 text-success rounded-4 p-3 mb-4 shadow-sm d-flex align-items-center justify-content-between" role="alert" data-aos="fade-down">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-4 text-success"></i>
            <div>
                <h6 class="fw-bold mb-0">Berhasil!</h6>
                <span class="small">{{ session('success') }}</span>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 bg-danger bg-opacity-10 text-danger rounded-4 p-3 mb-4 shadow-sm d-flex align-items-center justify-content-between" role="alert" data-aos="fade-down">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
            <div>
                <h6 class="fw-bold mb-0">Gagal!</h6>
                <span class="small">{{ session('error') }}</span>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="container p-0">

    <!-- HERO SECTION UTAMA -->
    <div class="hero-gradient text-white rounded-4 p-4 p-md-5 mb-4 shadow-sm border border-secondary border-opacity-10" data-aos="fade-down">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge bg-primary bg-opacity-25 text-light border border-primary border-opacity-50 px-3 py-2 rounded-pill mb-3 fw-semibold">
                    <i class="bi bi-shield-check me-1"></i> E-Parkir Universitas Siliwangi
                </span>
                <h1 class="display-5 fw-bold mb-3 tracking-wide">Sistem Parkir Digital Kampus</h1>
                <p class="text-white-50 lead fs-6 mb-4">
                    Pantau kapasitas kuota parkir fakultas secara real-time dan cek durasi parkir kendaraan Anda secara otomatis dengan cepat dan mudah.
                </p>

                <!-- TOMBOL TRIGGER COLLAPSE -->
                <button class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold border-opacity-25 shadow-sm d-inline-flex align-items-center gap-2" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#collapseDenahParkir" 
                        aria-expanded="false" 
                        aria-controls="collapseDenahParkir">
                    <i class="bi bi-map-fill text-primary"></i>
                    <span>Lihat Denah & Area Parkir</span>
                    <i class="bi bi-chevron-down ms-1"></i>
                </button>
            </div>
        </div>

        <!-- KONTEN COLLAPSE DENGAN GAMBAR (ROUNDED-4) -->
        <div class="collapse mt-4" id="collapseDenahParkir">
            <div class="card border-0 rounded-4 bg-dark bg-opacity-50 text-white overflow-hidden shadow-lg p-3 p-md-4 border border-white border-opacity-10">
                <div class="d-flex align-items-center justify-content-between mb-3 px-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                        <h5 class="fw-bold mb-0 text-white">Visualisasi Area Parkir Kampus</h5>
                    </div>
                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill px-3 py-1 small">
                        <i class="bi bi-geo-alt-fill me-1"></i>Universitas Siliwangi
                    </span>
                </div>

                <!-- CONTAINER GAMBAR DENAH/PARKIRAN ROUNDED-4 -->
                <div class="position-relative rounded-4 overflow-hidden border border-secondary border-opacity-25 shadow-sm">
                    <img src="https://images.unsplash.com/photo-1506521781263-d8422e82f27a?auto=format&fit=crop&w=1200&q=80" 
                         class="img-fluid w-100 object-fit-cover rounded-4" 
                         alt="Area Parkir Kampus UNSIL"
                         style="max-height: 380px;">

                    <!-- Overlap Badge Info Keterangan -->
                    <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-dark bg-opacity-75 backdrop-blur d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 text-start">
                        <div class="small">
                            <span class="fw-bold text-white d-block">Parkir Terintegrasi Fakultas</span>
                            <span class="text-white-50">Gunakan QR Scan di setiap pos untuk masuk area mandiri.</span>
                        </div>
                        <a href="{{ route('parkir.index') }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold text-nowrap">
                            Pilih Fakultas <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FITUR UTAMA & CARD NAVIGASI BERSEBELAHAN (SIDE BY SIDE) -->
    <div class="row g-4 mb-5 align-items-stretch" data-aos="fade-up">
        
        <!-- CARD PENGENDARA MANDIRI (KIRI) -->
        <div class="col-lg-5 col-md-12">
            <div class="card h-100 border-0 shadow-sm rounded-4 card-hover p-4 d-flex flex-column justify-content-between bg-white">
                <div>
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 p-3 mb-3 shadow-sm" style="width: 55px; height: 55px;">
                        <i class="bi bi-qr-code-scan fs-3"></i>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill small mb-2 d-inline-block fw-semibold">Akses Pengendara</span>
                    <h4 class="fw-bold text-dark mb-2">Pengendara Mandiri</h4>
                    <p class="text-muted small mb-4">
                        Masuk area parkir fakultas, cek ketersediaan slot kuota kosong secara langsung, dan daftarkan plat motor Anda secara mandiri dengan cepat.
                    </p>
                </div>
                <div>
                    <a href="{{ route('parkir.index') }}" class="btn btn-primary btn-lg rounded-3 fw-semibold w-100 shadow-sm py-3 d-flex align-items-center justify-content-center gap-2">
                        <span>Masuk Area Parkir</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- FITUR CEK DURASI & STATUS PARKIR REALTIME (KANAN) -->
        <div class="col-lg-7 col-md-12">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white p-4">
                <div class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-search text-primary fs-4"></i>
                        <h4 class="fw-bold text-dark mb-0">Cek Status & Durasi Parkir</h4>
                    </div>
                    <p class="text-muted small mb-0">Masukkan Plat Nomor kendaraan Anda untuk melihat lokasi dan durasi parkir saat ini.</p>
                </div>

                <!-- FORM PENCARIAN -->
                <form action="{{ route('landing') }}" method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-8 col-12">
                            <div class="input-group input-group-lg bg-light border rounded-3 overflow-hidden shadow-sm">
                                <input type="text" name="plat_nomor" 
                                       class="form-control bg-transparent border-0 text-uppercase fw-bold tracking-wide shadow-none fs-6" 
                                       placeholder="CONTOH: Z 1234 AB" 
                                       value="{{ request('plat_nomor') }}" 
                                       required 
                                       autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <button class="btn btn-primary btn-lg w-100 fw-semibold shadow-sm h-100 fs-6" type="submit">
                                <i class="bi bi-search me-1"></i> Cek Status
                            </button>
                        </div>
                    </div>
                </form>

                <!-- HASIL PENCARIAN -->
                @if(request()->has('plat_nomor'))
                    @if($riwayat)
                        @php
                            $isAktif =$riwayat->status === 'AKTIF';
                            // Lock timezone WIB (Asia/Jakarta)
                            $waktuMasuk = \Carbon\Carbon::parse($riwayat->created_at)->setTimezone('Asia/Jakarta');$waktuKeluar = $riwayat->waktu_keluar ? \Carbon\Carbon::parse($riwayat->waktu_keluar)->setTimezone('Asia/Jakarta') : null;
                        @endphp

                        <div class="card border border-light bg-light rounded-4 p-3 shadow-sm position-relative mt-auto" id="cardHasilRiwayat" data-aos="zoom-in">
                            
                            <!-- Header Hasil Search & Tombol Reset -->
                            <div class="d-flex align-items-center justify-content-between pb-2 border-bottom border-secondary border-opacity-10">
                                <div>
                                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Plat Nomor Kendaraan</small>
                                    <h4 class="fw-bold text-primary mb-0 tracking-wide">{{ $riwayat->plat_nomor }}</h4>
                                    <span class="text-dark fw-semibold small">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $riwayat->area->nama_area }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    @if($isAktif)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill small fw-bold">
                                            <i class="bi bi-clock-history me-1"></i> SEDANG PARKIR
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-pill small fw-bold">
                                            <i class="bi bi-check-circle-fill me-1"></i> SELESAI
                                        </span>
                                    @endif

                                    <!-- TOMBOL RESET / HILANGKAN CARD HASIL -->
                                    <a href="{{ route('landing') }}" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 30px; height: 30px;" title="Cari Plat Lain / Reset">
                                        <i class="bi bi-x-lg small"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Grid Kartu Detail -->
                            <div class="row g-2 pt-2 text-center">
                                <!-- Jam Masuk -->
                                <div class="col-6 col-sm-3">
                                    <div class="bg-white p-2 rounded-3 border border-light shadow-sm h-100">
                                        <span class="text-muted d-block" style="font-size: 0.7rem;">Masuk</span>
                                        <strong class="text-dark d-block small mt-1">{{ $waktuMasuk->format('H:i') }} WIB</strong>
                                        <span class="text-muted d-block" style="font-size: 0.65rem;">{{ $waktuMasuk->format('d/m/Y') }}</span>
                                    </div>
                                </div>

                                <!-- Jam Keluar -->
                                <div class="col-6 col-sm-3">
                                    <div class="bg-white p-2 rounded-3 border border-light shadow-sm h-100">
                                        <span class="text-muted d-block" style="font-size: 0.7rem;">Keluar</span>
                                        <strong class="text-dark d-block small mt-1">
                                            {{ $waktuKeluar ? $waktuKeluar->format('H:i') . ' WIB' : '-' }}
                                        </strong>
                                        <span class="text-muted d-block" style="font-size: 0.65rem;">
                                            {{ $waktuKeluar ? $waktuKeluar->format('d/m/Y') : 'Masih di dalam' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Total Durasi -->
                                <div class="col-6 col-sm-3">
                                    <div class="bg-primary bg-opacity-10 border border-primary border-opacity-25 p-2 rounded-3 h-100">
                                        <span class="text-primary d-block fw-semibold" style="font-size: 0.7rem;">Durasi</span>
                                        <strong class="text-primary d-block small mt-1" id="durasi-text">
                                            @if($isAktif)
                                                Memuat...
                                            @else
                                                {{ $waktuMasuk->diffForHumans($waktuKeluar, true) }}
                                            @endif
                                        </strong>
                                        <span class="text-primary opacity-75 d-block" style="font-size: 0.65rem;">
                                            {{ $isAktif ? 'Real-time' : 'Waktu Akhir' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Pencatatan -->
                                <div class="col-6 col-sm-3">
                                    <div class="bg-white p-2 rounded-3 border border-light shadow-sm h-100">
                                        <span class="text-muted d-block" style="font-size: 0.7rem;">Input</span>
                                        <span class="badge {{ $riwayat->input_oleh === 'JUKIR' ? 'bg-warning text-dark' : 'bg-info text-white' }} fw-semibold mt-1" style="font-size: 0.65rem;">
                                            {{ $riwayat->input_oleh }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- SCRIPT STOPWATCH REALTIME DETIK DEMI DETIK -->
                            @if($isAktif)
                            <script>
                                (function() {
                                    const masukTimestamp = new Date("{{ $waktuMasuk->toIso8601String() }}").getTime();

                                    function updateDurasiRealtime() {
                                        const now = new Date().getTime();
                                        const selisihDetik = Math.floor((now - masukTimestamp) / 1000);

                                        if (selisihDetik < 0) return;

                                        const jam = Math.floor(selisihDetik / 3600);
                                        const menit = Math.floor((selisihDetik % 3600) / 60);
                                        const detik = selisihDetik % 60;

                                        let stringDurasi = '';
                                        if (jam > 0) stringDurasi += jam + 'j ';
                                        if (menit > 0 || jam > 0) stringDurasi += menit + 'm ';
                                        stringDurasi += detik + 'd';

                                        const elemDurasi = document.getElementById('durasi-text');
                                        if (elemDurasi) {
                                            elemDurasi.innerText = stringDurasi;
                                        }
                                    }

                                    updateDurasiRealtime();
                                    setInterval(updateDurasiRealtime, 1000);
                                })();
                            </script>
                            @endif

                            <!-- TOMBOL TRIGGER MODAL (HANYA TOMBOL DI DALAM CARD) -->
                            @if($isAktif)
                                <div class="mt-3 pt-2 border-top border-secondary border-opacity-10 text-center">
                                    <button type="button" 
                                            class="btn btn-danger w-100 py-2.5 rounded-3 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalCheckout{{ $riwayat->id }}">
                                        <i class="bi bi-box-arrow-right fs-5"></i>
                                        <span>Selesai Parkir (Check-Out Mandiri)</span>
                                    </button>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">
                                        *Klik tombol di atas saat Anda akan meninggalkan lahan parkir.
                                    </small>
                                </div>
                            @endif

                        </div>

                    @else
                        <!-- STATE JIKA PLAT TIDAK DITEMUKAN -->
                        <div class="alert alert-danger bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-4 p-3 text-center text-danger shadow-sm position-relative mb-0 mt-auto" role="alert">
                            <a href="{{ route('landing') }}" class="btn-close position-absolute top-0 end-0 m-2" aria-label="Close" title="Reset Pencarian"></a>
                            <i class="bi bi-exclamation-triangle-fill fs-5 d-block mb-1"></i>
                            <h6 class="fw-bold mb-0">Plat {{ $plat_cari }} Tidak Ditemukan</h6>
                        </div>
                    @endif
                @endif
            </div>
        </div>

    </div>

    <!-- SECTION TAMBAHAN: INFORMASI SLOT PARKIR REAL-TIME PER FAKULTAS (BERTAHAP KE BAWAH) -->
    <div class="mb-5" data-aos="fade-up" data-aos-delay="100">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-p-square-fill text-primary fs-3"></i> Sisa Slot Parkir Fakultas
                </h3>
                <p class="text-muted small mb-0">Ketersediaan kuota lahan parkir secara langsung di setiap area fakultas.</p>
            </div>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill small fw-semibold d-none d-md-inline-block">
                <i class="bi bi-arrow-repeat me-1"></i> Real-time Update
            </span>
        </div>

        @if(isset($semua_area) && count($semua_area) > 0)
            <!-- CONTAINER LIST MEMANJANG KE BAWAH (COL-12) -->
            <div class="row g-3">
                @foreach($semua_area as $areaItem)
                    @php
                        // Menghitung kuota terisi dan sisa slot
                        $terisi = $areaItem->parkir_aktif_count ?? $areaItem->parkirAktif()->count();
                        $sisaSlot = max(0, $areaItem->kapasitas_total - $terisi);
                        $persentaseTerisi = $areaItem->kapasitas_total > 0 ? ($terisi / $areaItem->kapasitas_total) * 100 : 100;

                        // Penentuan Indikator Warna
                        if ($sisaSlot <= 0) {
                            $badgeStatus = 'PENUH';
                            $badgeColor = 'bg-danger text-danger';
                            $progressColor = 'bg-danger';
                        } elseif ($persentaseTerisi >= 80) {
                            $badgeStatus = 'HAMPIR PENUH';
                            $badgeColor = 'bg-warning text-warning';
                            $progressColor = 'bg-warning';
                        } else {
                            $badgeStatus = 'TERSEDIA';
                            $badgeColor = 'bg-success text-success';
                            $progressColor = 'bg-success';
                        }
                    @endphp

                    <!-- ITEM CARD UTUH DENGAN LAYOUT VERTIKAL (LEBAR NAMA FULL 100%) -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 card-hover bg-white p-3 p-md-4">
                            
                            <!-- 1. HEADER NAMA PARKIRAN (UTUH TIDAK TERPOTONG) -->
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-light">
                                <h5 class="fw-bold text-dark mb-0 me-2">
                                    📍 {{ $areaItem->nama_area }}
                                </h5>
                                <span class="badge {{ $badgeColor }} bg-opacity-10 border border-current px-3 py-1.5 rounded-pill small fw-bold" style="font-size: 0.75rem;">
                                    {{ $badgeStatus }}
                                </span>
                            </div>

                            <!-- 2. KONTEN DETAIL DI BAWAH NAMA PARKIRAN -->
                            <div class="row align-items-center g-3">
                                
                                <!-- Progress Bar & Keterisian Slot (Kiri/Tengah) -->
                                <div class="col-md-8 col-sm-7 col-12">
                                    <div class="d-flex justify-content-between align-items-center small text-muted mb-1" style="font-size: 0.8rem;">
                                        <span>Lahan Terisi: <strong>{{ $terisi }}</strong></span>
                                        <span>Kapasitas Total: <strong>{{ $areaItem->kapasitas_total }}</strong> Slot</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 10px; background-color: #e2e8f0;">
                                        <div class="progress-bar {{ $progressColor }} rounded-pill" 
                                            role="progressbar" 
                                            style="width: {{ $persentaseTerisi }}%;" 
                                            aria-valuenow="{{ $persentaseTerisi }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="text-end mt-1">
                                        <small class="text-muted" style="font-size: 0.75rem;">Keterisian {{ round($persentaseTerisi) }}%</small>
                                    </div>
                                </div>

                                <!-- Total Slot Kosong & Tombol Action (Kanan) -->
                                <div class="col-md-4 col-sm-5 col-12 d-flex align-items-center justify-content-between justify-content-sm-end gap-3">
                                    <div class="text-start text-sm-end">
                                        <span class="fs-3 fw-bold text-dark d-block lh-1">{{ $sisaSlot }}</span>
                                        <small class="text-muted fw-semibold d-block" style="font-size: 0.7rem;">Slot Kosong</small>
                                    </div>
                                    
                                    <div>
                                        <a href="{{ route('parkir.index', ['area_id' => $areaItem->id]) }}" 
                                        class="btn btn-outline-primary rounded-3 fw-semibold px-4 py-2 d-flex align-items-center gap-2 {{ $sisaSlot <= 0 ? 'disabled' : '' }}"
                                        style="white-space: nowrap;">
                                            <span>{{ $sisaSlot <= 0 ? 'Penuh' : 'Parkir' }}</span>
                                            <i class="bi bi-chevron-right small"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Fallback jika variabel $semua_area belum di-pass dari Controller -->
            <div class="alert alert-info bg-info bg-opacity-10 border border-info border-opacity-25 rounded-4 p-4 text-center text-info shadow-sm">
                <i class="bi bi-info-circle-fill fs-4 d-block mb-1"></i>
                <h6 class="fw-bold mb-0">Data Area Parkir Belum Dimuat</h6>
                <p class="small mb-0">Pastikan Method `showLanding` di `Page.php` mengirimkan variabel <code>$semua_area</code> ke view landing.</p>
            </div>
        @endif
    </div>

</div>
<!-- KONTEN MODAL CHECKOUT MANDIRI (DILETAKKAN DI LUAR CONTAINER KARTU) -->
@if(request()->has('plat_nomor') && isset($riwayat) && $riwayat->status === 'AKTIF')
    <div class="modal fade" id="modalCheckout{{ $riwayat->id }}" tabindex="-1" aria-labelledby="modalCheckoutLabel{{ $riwayat->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                
                <!-- HEADER MODAL -->
                <div class="modal-header bg-danger text-white border-0 py-3">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2 fs-6" id="modalCheckoutLabel{{ $riwayat->id }}">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <span>Konfirmasi Selesai Parkir</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- BODY MODAL -->
                <div class="modal-body p-4 text-center">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-box-arrow-right fs-2"></i>
                    </div>

                    <h6 class="fw-bold text-dark mb-2">Apakah Anda yakin ingin keluar?</h6>
                    <p class="text-muted small mb-3">
                        Status parkir kendaraan Anda akan diubah menjadi <strong class="text-dark">SELESAI</strong> dan slot kuota parkir di area ini akan dilepaskan kembali.
                    </p>

                    <!-- DETAIL PARKIR RINGKAS -->
                    <div class="bg-light rounded-3 p-3 text-start border border-light mb-2">
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Plat Nomor:</small>
                                <span class="fw-bold text-primary">{{ $riwayat->plat_nomor }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Lokasi Parkir:</small>
                                <span class="fw-bold text-dark">{{ $riwayat->area->nama_area }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FOOTER MODAL (AKSI CHECKOUT) -->
                <div class="modal-footer bg-light border-0 p-3 d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-3 fw-semibold small" data-bs-dismiss="modal">
                        Batal
                    </button>
                    
                    <form action="{{ route('parkir.checkout', $riwayat->id) }}" method="POST" class="w-50 m-0">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100 py-2 rounded-3 fw-semibold small d-flex align-items-center justify-content-center gap-1">
                            <span>Ya, Check-Out</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endif
@endsection