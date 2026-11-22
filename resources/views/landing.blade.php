@extends('layout.master')

@section('title', 'Smart Campus Parking - Universitas Siliwangi')

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

    body {
        background-color: #f4f7f5;
        color: #0f172a;
    }
    
    .tracking-wide { 
        letter-spacing: -0.02em; 
    }
    
    .bento-card {
        background: #ffffff;
        border: 1px solid #d1e2d8;
        border-radius: 1.25rem;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .bento-card:hover {
        border-color: var(--unsil-green);
        box-shadow: 0 12px 24px -10px rgba(0, 90, 50, 0.12) !important;
    }
    
    .hero-minimal {
        background: rgba(0, 45, 25, 0.72);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(250, 204, 21, 0.3);
        border-radius: 1.25rem;
    }

    .text-unsil { color: var(--unsil-green) !important; }
    .bg-unsil { background-color: var(--unsil-green) !important; }
    .bg-unsil-soft { background-color: var(--unsil-green-light) !important; }

    .badge-unsil-yellow {
        background-color: var(--unsil-yellow);
        color: #14281d;
        font-weight: 700;
    }

    .badge-unsil-green {
        background-color: var(--unsil-green-light);
        color: var(--unsil-green);
        border: 1px solid rgba(0, 90, 50, 0.2);
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
    }

    .btn-unsil-yellow {
        background-color: var(--unsil-yellow);
        border-color: var(--unsil-yellow);
        color: #062818;
        font-weight: 700;
    }
    .btn-unsil-yellow:hover {
        background-color: var(--unsil-yellow-hover);
        border-color: var(--unsil-yellow-hover);
        color: #000000;
    }

    .step-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--unsil-green);
        color: var(--unsil-yellow);
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0, 90, 50, 0.15);
    }

    .slot-pill {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 6px 10px;
        border-radius: 8px;
        text-align: center;
        transition: all 0.2s ease;
    }
    .slot-kosong {
        background-color: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .slot-kosong-dosen {
        background-color: #fefce8;
        color: #854d0e;
        border: 1px solid #fde047;
    }
    .slot-terisi {
        background-color: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .area-nav-section-fluid {
        background: linear-gradient(135deg, #003d22 0%, #005a32 100%);
        border-top: 3px solid #facc15;
        border-bottom: 4px solid #facc15;
        padding: 2.25rem 0 2.5rem 0;
    }

    .area-nav-scroller {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
        padding: 4px 4px 8px 4px;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .area-nav-scroller::-webkit-scrollbar {
        display: none;
    }

    .area-nav-card {
        flex: 0 0 295px;
        scroll-snap-align: start;
        background-color: #ffffff;
        color: #0f172a;
        border-radius: 1.15rem;
        padding: 1.2rem;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        user-select: none;
        box-shadow: 0 8px 20px rgba(0, 25, 14, 0.22);
    }

    @media (min-width: 768px) {
        .area-nav-card {
            flex: 0 0 325px;
        }
    }

    .area-nav-card:hover {
        transform: translateY(-3px);
        border-color: rgba(250, 204, 21, 0.8);
    }

    .area-nav-card.active {
        background-color: #fffbeb;
        border: 3px solid #facc15;
        box-shadow: 0 12px 26px rgba(0, 0, 0, 0.32);
    }

    .area-card-title-row {
        min-height: 46px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.5rem;
        margin-bottom: 0.85rem;
    }

    .stat-mini-box {
        background-color: #f4f7f5;
        border: 1px solid #d1e2d8;
        border-radius: 0.75rem;
        padding: 0.5rem 0.25rem;
        text-align: center;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .stat-mini-box.highlight-sisa {
        background-color: #e6f4ea;
        border-color: #86efac;
    }

    .scroll-arrow-btn-light {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(250, 204, 21, 0.45);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .scroll-arrow-btn-light:hover {
        background-color: #facc15;
        color: #062818;
        border-color: #facc15;
    }

    .slot-grid-container {
        background-color: #ffffff;
        border: 1px solid #d1e2d8;
        border-radius: 1.25rem;
        padding: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 90, 50, 0.04);
    }
</style>
@endsection

@section('content')

<!-- HERO BANNER -->
<div id="gambar-unsil" class="w-100 position-relative d-flex align-items-center mb-5 overflow-hidden shadow-sm" 
     style="min-height: 380px; border-bottom: 4px solid var(--unsil-yellow); background: linear-gradient(135deg, rgba(0, 61, 34, 0.88) 0%, rgba(0, 90, 50, 0.80) 100%), url('{{ asset('images/unsil.jpg') }}') center/cover no-repeat;">
    
    <div class="container py-5">
        <div class="hero-minimal text-white p-4 p-md-5" data-aos="fade-down">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <span class="badge badge-unsil-yellow px-3 py-2 rounded-pill mb-3 shadow-sm">
                        <i class="bi bi-shield-check me-1"></i> E-Parkir Universitas Siliwangi
                    </span>
                    <h1 class="display-5 fw-bold mb-3 tracking-wide text-white">Sistem Parkir Digital Kampus</h1>
                    <p class="text-white text-opacity-75 lead fs-6 mb-0">
                        Pantau posisi virtual slot parkir yang kosong per fakultas secara real-time dan gunakan akses QR mandiri dengan praktis.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- BENTO GRID: CARD PENGENDARA & PANDUAN PETUNJUK -->
    <div class="row g-4 mb-5 align-items-stretch" data-aos="fade-up">
        <!-- CARD AKSES PENGENDARA MANDIRI (KIRI) -->
        <div class="col-lg-5 col-md-12">
            <div class="bento-card h-100 p-4 d-flex flex-column justify-content-between" style="border-top: 4px solid var(--unsil-green);">
                <div>
                    <div class="d-inline-flex align-items-center justify-content-center bg-unsil-soft text-unsil rounded-3 p-3 mb-3" style="width: 52px; height: 52px;">
                        <i class="bi bi-qr-code-scan fs-4"></i>
                    </div>
                    <span class="badge badge-unsil-yellow px-3 py-1 rounded-pill small mb-2 d-inline-block">Akses Pengendara</span>
                    <h4 class="fw-bold text-dark mb-2">Pengendara Mandiri</h4>
                    <p class="text-muted small mb-4">
                        Masuk area parkir kampus, pilih lokasi tujuan Anda, dan tunjukkan Barcode QR Identitas kepada petugas jukir di pintu masuk.
                    </p>
                </div>
                <div>
                    <a href="{{ route('pengendara.dashboard') }}" class="btn btn-unsil-primary btn-lg rounded-3 fw-semibold w-100 shadow-sm py-3 d-flex align-items-center justify-content-center gap-2">
                        <span>Masuk Area Parkir</span>
                        <i class="bi bi-arrow-right text-warning"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- CARD ALUR & CARA PARKIR MANDIRI (KANAN) -->
        <div class="col-lg-7 col-md-12">
            <div class="bento-card h-100 p-4 d-flex flex-column justify-content-between bg-white" style="border-top: 4px solid var(--unsil-yellow);">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-info-circle-fill text-unsil fs-4"></i>
                        <h4 class="fw-bold text-dark mb-0">Petunjuk Parkir Digital</h4>
                    </div>
                    <p class="text-muted small mb-4">Prosedur singkat penggunaan sistem E-Parkir mandiri di area Universitas Siliwangi.</p>

                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-unsil-soft bg-opacity-50 border border-success border-opacity-10">
                            <div class="step-icon">1</div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1 fs-6">Pilih Lokasi & Check-In Mandiri</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.8rem;">
                                    Buka dashboard pengendara, lalu pilih area fakultas yang masih memiliki sisa slot kosong.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-unsil-soft bg-opacity-50 border border-success border-opacity-10">
                            <div class="step-icon">2</div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1 fs-6">Tunjukkan Barcode Identitas</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.8rem;">
                                    Scan / perlihatkan Barcode QR Identitas pada layar ponsel Anda ke Petugas Jukir di gerbang masuk.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-unsil-soft bg-opacity-50 border border-success border-opacity-10">
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
                    <span style="font-size: 0.75rem;"><i class="bi bi-shield-check text-unsil me-1"></i> Terintegrasi Pos Jukir UNSIL</span>
                    <span style="font-size: 0.75rem;"><i class="bi bi-clock text-warning me-1"></i> Akses 24/7</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- VISUAL MONITORING GRID SLOT KOSONG / TERISI PER FAKULTAS -->
<div class="mb-5" data-aos="fade-up" data-aos-delay="100" id="monitoringParkirSection">
    @if(isset($semua_area) && count($semua_area) > 0)
        <!-- ==================== 1. SECTION NAVIGASI CARD ==================== -->
        <section class="area-nav-section-fluid mb-4" data-aos="fade-up">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div>
                        <h4 class="fw-bold text-white mb-1 d-flex align-items-center gap-2">
                            <i class="bi bi-grid-3x3-gap-fill" style="color: #facc15;"></i> Monitoring Slot Parkir Real-Time
                        </h4>
                        <p class="text-white text-opacity-75 small mb-0">
                            Geser dan pilih kartu area fakultas di bawah untuk melihat denah slot secara langsung.
                        </p>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill px-3 py-2 small fw-semibold d-none d-sm-inline-block" 
                            style="background-color: rgba(255,255,255,0.12); color: #bbf7d0; border: 1px solid rgba(255,255,255,0.2);">
                            <i class="bi bi-arrow-repeat me-1"></i> Live Monitoring
                        </span>
                        <button type="button" class="scroll-arrow-btn-light shadow-sm" id="btnSlideLeft" title="Geser Kiri">
                            <i class="bi bi-chevron-left fw-bold"></i>
                        </button>
                        <button type="button" class="scroll-arrow-btn-light shadow-sm" id="btnSlideRight" title="Geser Kanan">
                            <i class="bi bi-chevron-right fw-bold"></i>
                        </button>
                    </div>
                </div>

                <div class="area-nav-scroller" id="areaNavScroller" role="tablist">
                    @foreach($semua_area as $index => $areaItem)
                        @php
                            $terisi = $areaItem->parkir_aktif_count ?? 0;
                            $sisaSlot = max(0, $areaItem->kapasitas_total - $terisi);
                            $persentaseTerisi = $areaItem->kapasitas_total > 0 ? ($terisi / $areaItem->kapasitas_total) * 100 : 100;

                            if ($sisaSlot <= 0) {
                                $badgeStatus = 'PENUH';
                                $badgeClass = 'bg-danger text-white';
                            } elseif ($persentaseTerisi >= 80) {
                                $badgeStatus = 'HAMPIR PENUH';
                                $badgeClass = 'badge-unsil-yellow';
                            } else {
                                $badgeStatus = 'TERSEDIA';
                                $badgeClass = 'badge-unsil-green';
                            }
                        @endphp

                        <div class="area-nav-card {{ $index === 0 ? 'active' : '' }}"
                            onclick="selectAreaTab(this, '#paneArea{{ $areaItem->id }}')">
                            
                            <div class="area-card-title-row">
                                <h6 class="fw-bold text-dark mb-0 lh-sm" style="font-size: 0.95rem;">
                                    📍 {{ $areaItem->nama_area }}
                                </h6>
                                <span class="badge {{ $badgeClass }} rounded-pill px-2.5 py-1 flex-shrink-0" style="font-size: 0.65rem;">
                                    {{ $badgeStatus }}
                                </span>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-4">
                                    <div class="stat-mini-box">
                                        <small class="text-muted d-block" style="font-size: 0.68rem;">Kapasitas</small>
                                        <strong class="text-dark fs-6">{{ $areaItem->kapasitas_total }}</strong>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-mini-box">
                                        <small class="text-muted d-block" style="font-size: 0.68rem;">Terisi</small>
                                        <strong class="text-dark fs-6">{{ $terisi }}</strong>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-mini-box highlight-sisa">
                                        <small class="text-unsil fw-semibold d-block" style="font-size: 0.68rem;">Tersisa</small>
                                        <strong class="text-unsil fs-6">{{ $sisaSlot }}</strong>
                                    </div>
                                </div>
                            </div>

                            <button type="button"
                                    class="btn {{ $sisaSlot <= 0 ? 'btn-secondary' : 'btn-unsil-yellow' }} btn-sm w-100 rounded-3 py-2 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm"
                                    onclick="event.stopPropagation(); selectAreaTab(this.closest('.area-nav-card'), '#paneArea{{ $areaItem->id }}', true);">
                                <span>{{ $sisaSlot <= 0 ? 'Lahan Penuh (Lihat Grid)' : 'Parkir di Sini' }}</span>
                                <i class="bi bi-arrow-down-circle-fill"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ==================== 2. KONTEN SLOT GRID REAL-TIME (ZONA DOSEN & ZONA UMUM) ==================== -->
        <div class="container mb-5" data-aos="fade-up" data-aos-delay="100">
            <div class="slot-grid-container" id="slotGridDisplayBox">
                @foreach($semua_area as $index => $areaItem)
                    @php
                        $terisi = $areaItem->parkir_aktif_count ?? 0;
                        $sisaSlot = max(0, $areaItem->kapasitas_total - $terisi);
                        $slotTerpakai = $areaItem->parkir ? $areaItem->parkir->pluck('nomor_slot')->toArray() : [];
                        $batasDosen = (int) ($areaItem->kapasitas_dosen ?? 0);
                    @endphp

                    <div class="area-grid-pane {{ $index === 0 ? '' : 'd-none' }}" id="paneArea{{ $areaItem->id }}">
                        
                        <!-- Header Denah Area Terpilih -->
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pb-3 mb-3 border-bottom">
                            <div>
                                <span class="badge badge-unsil-green rounded-pill px-3 py-1 mb-1">
                                    Kode Area: {{ $areaItem->kode_area }}
                                </span>
                                <h5 class="fw-bold text-dark mb-0">
                                    Denah Slot Parkir — {{ $areaItem->nama_area }}
                                </h5>
                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 small">
                                    🟢 Kosong ({{ $sisaSlot }})
                                </span>
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 small">
                                    🔴 Terisi ({{ $terisi }})
                                </span>

                                @if($sisaSlot > 0)
                                    <a href="{{ route('pengendara.dashboard', ['area_id' => $areaItem->id]) }}" 
                                       class="btn btn-unsil-primary btn-sm rounded-3 px-3 py-1.5 fw-semibold ms-1 shadow-sm">
                                        <i class="bi bi-box-arrow-in-right text-warning me-1"></i> Check-In Area Ini
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Grid Terpisah: Zona Dosen & Zona Mahasiswa/Umum -->
                        <div class="pe-1" style="max-height: 380px; overflow-y: auto;">
                            @if($batasDosen > 0)
                                <div class="p-3 rounded-3 border mb-3" style="background-color: #fefce8; border-color: #fde047 !important;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-dark text-warning px-2.5 py-1 rounded-pill" style="font-size: 0.7rem;">
                                            <i class="bi bi-star-fill me-1"></i> ZONA KHUSUS DOSEN ({{ $areaItem->kode_area }}001 — {{ $areaItem->kode_area . str_pad($batasDosen, 3, '0', STR_PAD_LEFT) }})
                                        </span>
                                        <small class="text-muted fw-semibold" style="font-size: 0.72rem;">{{ $batasDosen }} Slot</small>
                                    </div>
                                    <div class="row g-2">
                                        @for($i = 1; $i <= min($batasDosen, $areaItem->kapasitas_total); $i++)
                                            @php
                                                $isSlotTerisi = in_array($i, $slotTerpakai);
                                                $kodeSlot = $areaItem->kode_area . str_pad($i, 3, '0', STR_PAD_LEFT);
                                            @endphp
                                            <div class="col-4 col-sm-3 col-md-2 col-lg-1">
                                                <div class="slot-pill {{ $isSlotTerisi ? 'slot-terisi' : 'slot-kosong-dosen' }}" 
                                                     title="Slot {{ $kodeSlot }} (Khusus Dosen): {{ $isSlotTerisi ? 'Terisi' : 'Kosong' }}">
                                                    {{ $kodeSlot }}
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            @endif

                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge badge-unsil-green px-2.5 py-1 rounded-pill" style="font-size: 0.7rem;">
                                        <i class="bi bi-mortarboard-fill me-1"></i> ZONA MAHASISWA & UMUM ({{ $areaItem->kode_area . str_pad($batasDosen + 1, 3, '0', STR_PAD_LEFT) }} — {{ $areaItem->kode_area . str_pad($areaItem->kapasitas_total, 3, '0', STR_PAD_LEFT) }})
                                    </span>
                                    <small class="text-muted fw-semibold" style="font-size: 0.72rem;">{{ max(0, $areaItem->kapasitas_total - $batasDosen) }} Slot</small>
                                </div>
                                <div class="row g-2">
                                    @for($i = $batasDosen + 1; $i <= $areaItem->kapasitas_total; $i++)
                                        @php
                                            $isSlotTerisi = in_array($i, $slotTerpakai);
                                            $kodeSlot = $areaItem->kode_area . str_pad($i, 3, '0', STR_PAD_LEFT);
                                        @endphp
                                        <div class="col-4 col-sm-3 col-md-2 col-lg-1">
                                            <div class="slot-pill {{ $isSlotTerisi ? 'slot-terisi' : 'slot-kosong' }}" 
                                                 title="Slot {{ $kodeSlot }} (Umum): {{ $isSlotTerisi ? 'Terisi' : 'Kosong' }}">
                                                {{ $kodeSlot }}
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <small class="text-muted d-block mt-3 text-center" style="font-size: 0.72rem;">
                            *Menampilkan seluruh {{ $areaItem->kapasitas_total }} slot parkir pada <strong>{{ $areaItem->nama_area }}</strong> ({{ $batasDosen }} Slot Dosen &bull; {{ max(0, $areaItem->kapasitas_total - $batasDosen) }} Slot Umum).
                        </small>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="alert border rounded-4 p-4 text-center shadow-sm" style="background-color: #e6f4ea; color: #005a32; border-color: #bbf7d0 !important;">
            <i class="bi bi-info-circle-fill fs-4 d-block mb-1"></i>
            <h6 class="fw-bold mb-0">Data Area Parkir Belum Dimuat</h6>
        </div>
    @endif
</div>

<script>
    function selectAreaTab(cardElement, targetPaneSelector, scrollToGrid = false) {
        document.querySelectorAll('.area-nav-card').forEach(card => {
            card.classList.remove('active');
        });
        if (cardElement) {
            cardElement.classList.add('active');
        }
        document.querySelectorAll('.area-grid-pane').forEach(pane => {
            pane.classList.add('d-none');
        });
        const activePane = document.querySelector(targetPaneSelector);
        if (activePane) {
            activePane.classList.remove('d-none');
        }
        if (scrollToGrid) {
            const gridBox = document.getElementById('slotGridDisplayBox');
            if (gridBox) {
                gridBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const scroller = document.getElementById('areaNavScroller');
        const btnLeft = document.getElementById('btnSlideLeft');
        const btnRight = document.getElementById('btnSlideRight');

        if (scroller && btnLeft && btnRight) {
            btnLeft.addEventListener('click', () => {
                scroller.scrollBy({ left: -330, behavior: 'smooth' });
            });
            btnRight.addEventListener('click', () => {
                scroller.scrollBy({ left: 330, behavior: 'smooth' });
            });
        }
    });
</script>
@endsection