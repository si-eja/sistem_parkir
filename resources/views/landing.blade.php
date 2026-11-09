@extends('layout.master')

@section('title', 'Smart Campus Parking - Universitas Siliwangi')

@section('custom-css')
<style>
    /* Styling Visual Minimalis & Bento Card */
    body {
        background-color: #f8fafc;
        color: #0f172a;
    }
    
    .tracking-wide { 
        letter-spacing: -0.02em; 
    }
    
    .bento-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .bento-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 12px 24px -10px rgba(15, 23, 42, 0.05) !important;
    }
    
    .hero-minimal {
        background: #0f172a;
        border-radius: 1.25rem;
    }

    .badge-soft-primary {
        background-color: rgba(37, 99, 235, 0.1);
        color: #2563eb;
    }

    /* Step Badge Icon */
    .step-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(37, 99, 235, 0.08);
        color: #2563eb;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Grid Slotting Visual Mini */
    .slot-pill {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 6px 10px;
        border-radius: 8px;
        text-align: center;
        transition: all 0.2s ease;
    }
    .slot-kosong {
        background-color: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .slot-terisi {
        background-color: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
</style>
@endsection

@section('content')
<!-- NOTIFIKASI SYSTEM -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 bg-success bg-opacity-10 text-success rounded-4 p-3 mb-4 shadow-sm d-flex align-items-center justify-content-between" role="alert" data-aos="fade-down">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
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
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>
                <h6 class="fw-bold mb-0">Gagal!</h6>
                <span class="small">{{ session('error') }}</span>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container p-0">

    <!-- HERO SECTION MINIMALIS -->
    <div class="hero-minimal text-white p-4 p-md-5 mb-4 shadow-sm" data-aos="fade-down">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <span class="badge badge-soft-primary text-primary px-3 py-2 rounded-pill mb-3 fw-semibold border border-primary border-opacity-25" style="background: rgba(37, 99, 235, 0.15);">
                    <i class="bi bi-shield-check me-1"></i> E-Parkir Universitas Siliwangi
                </span>
                <h1 class="display-6 fw-bold mb-3 tracking-wide">Sistem Parkir Digital Kampus</h1>
                <p class="text-white-50 lead fs-6 mb-0">
                    Pantau posisi virtual slot parkir yang kosong per fakultas secara real-time dan gunakan akses QR mandiri dengan praktis.
                </p>
            </div>
        </div>
    </div>

    <!-- BENTO GRID: CARD PENGENDARA & PANDUAN PETUNJUK (PENGGANTI CEK STATUS) -->
    <div class="row g-4 mb-5 align-items-stretch" data-aos="fade-up">
        
        <!-- CARD AKSES PENGENDARA MANDIRI (KIRI) -->
        <div class="col-lg-5 col-md-12">
            <div class="bento-card h-100 p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 p-3 mb-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-qr-code-scan fs-4"></i>
                    </div>
                    <span class="badge badge-soft-primary px-3 py-1 rounded-pill small mb-2 d-inline-block fw-semibold">Akses Pengendara</span>
                    <h4 class="fw-bold text-dark mb-2">Pengendara Mandiri</h4>
                    <p class="text-muted small mb-4">
                        Masuk area parkir kampus, pilih lokasi tujuan Anda, dan tunjukkan Barcode QR Identitas kepada petugas jukir di pintu masuk.
                    </p>
                </div>
                <div>
                    <a href="{{ route('pengendara.dashboard') }}" class="btn btn-primary btn-lg rounded-3 fw-semibold w-100 shadow-sm py-3 d-flex align-items-center justify-content-center gap-2">
                        <span>Masuk Area Parkir</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- CARD PENGGANTI: ALUR & CARA PARKIR MANDIRI (KANAN) -->
        <div class="col-lg-7 col-md-12">
            <div class="bento-card h-100 p-4 d-flex flex-column justify-content-between bg-white">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-info-circle-fill text-primary fs-4"></i>
                        <h4 class="fw-bold text-dark mb-0">Petunjuk Parkir Digital</h4>
                    </div>
                    <p class="text-muted small mb-4">Prosedur singkat penggunaan sistem E-Parkir mandiri di area kampus.</p>

                    <!-- LANGKAH-LANGKAH ALUR PARKIR -->
                    <div class="d-flex flex-column gap-3">
                        
                        <!-- Step 1 -->
                        <div class="d-flex align-items-start gap-3 p-2.5 rounded-3 bg-light border border-light">
                            <div class="step-icon">1</div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1 fs-6">Pilih Lokasi & Check-In Mandiri</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.8rem;">
                                    Buka dashboard pengendara, lalu pilih area fakultas yang masih memiliki sisa slot kosong.
                                </p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="d-flex align-items-start gap-3 p-2.5 rounded-3 bg-light border border-light">
                            <div class="step-icon">2</div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1 fs-6">Tunjukkan Barcode Identitas</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.8rem;">
                                    Scan / perlihatkan Barcode QR Identitas pada layar ponsel Anda ke Petugas Jukir di gerbang masuk.
                                </p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="d-flex align-items-start gap-3 p-2.5 rounded-3 bg-light border border-light">
                            <div class="step-icon">3</div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1 fs-6">Selesai Parkir (Check-Out)</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.8rem;">
                                    Tekan tombol <strong>Check-Out Mandiri</strong> ketika Anda hendak keluar area agar kuota slot dilepaskan kembali.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="pt-3 border-top mt-3 d-flex align-items-center justify-content-between text-muted small">
                    <span style="font-size: 0.75rem;"><i class="bi bi-shield-check text-success me-1"></i> Terintegrasi Pos Jukir Kampus</span>
                    <span style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i> Akses 24/7</span>
                </div>
            </div>
        </div>

    </div>

    <!-- VISUAL MONITORING GRID SLOT KOSONG / TERISI PER FAKULTAS -->
    <div class="mb-5" data-aos="fade-up" data-aos-delay="100">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-grid-3x3-gap-fill text-primary fs-4"></i> Monitoring Slot Parkir Real-Time
                </h4>
                <p class="text-muted small mb-0">Lihat denah slot kosong (hijau) dan terisi (merah) di seluruh area fakultas.</p>
            </div>
            <span class="badge bg-white text-muted border px-3 py-2 rounded-pill small fw-medium d-none d-md-inline-block">
                <i class="bi bi-arrow-repeat me-1 text-primary"></i> Live Monitoring
            </span>
        </div>

        @if(isset($semua_area) && count($semua_area) > 0)
            <div class="row g-3">
                @foreach($semua_area as $areaItem)
                    @php
                        $terisi = $areaItem->parkir_aktif_count ?? 0;
                        $sisaSlot = max(0, $areaItem->kapasitas_total - $terisi);
                        $persentaseTerisi = $areaItem->kapasitas_total > 0 ? ($terisi / $areaItem->kapasitas_total) * 100 : 100;

                        // Ambil daftar nomor slot yang sedang terpakai
                        $slotTerpakai = $areaItem->parkir ? $areaItem->parkir->pluck('nomor_slot')->toArray() : [];

                        if ($sisaSlot <= 0) {
                            $badgeStatus = 'PENUH';
                            $badgeColor = 'bg-danger text-danger';
                            $progressColor = 'bg-danger';
                        } elseif ($persentaseTerisi >= 80) {
                            $badgeStatus = 'HAMPIR PENUH';
                            $badgeColor = 'bg-warning text-dark';
                            $progressColor = 'bg-warning';
                        } else {
                            $badgeStatus = 'TERSEDIA';
                            $badgeColor = 'bg-success text-success';
                            $progressColor = 'bg-success';
                        }
                    @endphp

                    <div class="col-12">
                        <div class="bento-card p-3 p-md-4">
                            <!-- HEADER AREA FAKULTAS -->
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">📍 {{ $areaItem->nama_area }}</h6>
                                    <small class="text-muted">Kapasitas: {{ $areaItem->kapasitas_total }} Slot | Terisi: {{ $terisi }} | Sisa Kosong: <strong class="text-success">{{ $sisaSlot }}</strong></small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge {{ $badgeColor }} bg-opacity-10 border border-current px-3 py-1.5 rounded-pill small fw-bold">
                                        {{ $badgeStatus }}
                                    </span>
                                </div>
                            </div>

                            <!-- PROGRESS BAR KETERISIAN -->
                            <div class="progress rounded-pill mb-3" style="height: 8px;">
                                <div class="progress-bar {{ $progressColor }} rounded-pill" role="progressbar" style="width: {{ $persentaseTerisi }}%;"></div>
                            </div>

                            <!-- COLLAPSE INTERAKTIF VISUAL GRID SLOT (DENGAN CONTAINER SCROLLING) -->
                            <div class="collapse mt-3" id="gridArea{{ $areaItem->id }}">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="fw-bold text-muted">Denah Virtual Slot ({{ $areaItem->kode_area }}) - Total {{ $areaItem->kapasitas_total }} Slot</small>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1" style="font-size: 0.65rem;">🟢 Kosong</span>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1" style="font-size: 0.65rem;">🔴 Terisi</span>
                                        </div>
                                    </div>
                                    
                                    <!-- KOTAK WRAPPER SCROLL VERTIKAL UTUH UNTUK SELURUH SLOT -->
                                    <div class="pe-1" style="max-height: 280px; overflow-y: auto;">
                                        <div class="row g-1.5">
                                            @for($i = 1; $i <= $areaItem->kapasitas_total; $i++)
                                                @php
                                                    $isSlotTerisi = in_array($i, $slotTerpakai);
                                                    $kodeSlot = $areaItem->kode_area . str_pad($i, 3, '0', STR_PAD_LEFT);
                                                @endphp
                                                <div class="col-4 col-sm-3 col-md-2 col-lg-1">
                                                    <div class="slot-pill {{ $isSlotTerisi ? 'slot-terisi' : 'slot-kosong' }}" title="Slot {{ $kodeSlot }}: {{ $isSlotTerisi ? 'Terisi' : 'Kosong' }}">
                                                        {{ $kodeSlot }}
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>

                                    <small class="text-muted d-block mt-2 text-center" style="font-size: 0.7rem;">
                                        *Gulir (scroll) kotak di atas untuk melihat seluruh {{ $areaItem->kapasitas_total }} slot parkir.
                                    </small>
                                </div>
                            </div>

                            <!-- FOOTER CARD PARKIR -->
                            <div class="d-flex justify-content-end mt-3">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold small" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#gridArea{{ $areaItem->id }}"
                                            aria-expanded="false">
                                        <i class="bi bi-eye me-1"></i> Lihat Grid Slot
                                    </button>

                                    <a href="{{ route('pengendara.dashboard', ['area_id' => $areaItem->id]) }}" 
                                    class="btn btn-primary rounded-3 fw-semibold px-4 py-2 d-flex align-items-center gap-2 {{ $sisaSlot <= 0 ? 'disabled' : '' }}">
                                        <span>{{ $sisaSlot <= 0 ? 'Lahan Penuh' : 'Parkir di Sini' }}</span>
                                        <i class="bi bi-chevron-right small"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info bg-info bg-opacity-10 border border-info border-opacity-25 rounded-4 p-4 text-center text-info shadow-sm">
                <i class="bi bi-info-circle-fill fs-4 d-block mb-1"></i>
                <h6 class="fw-bold mb-0">Data Area Parkir Belum Dimuat</h6>
            </div>
        @endif
    </div>

</div>
@endsection