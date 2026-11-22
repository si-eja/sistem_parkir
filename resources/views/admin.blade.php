@extends('layout.dashboard-master')

@section('title', 'Admin Command Center - E-Parkir UNSIL')

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

    /* Executive Command Header */
    .admin-hero-banner {
        background: linear-gradient(135deg, var(--unsil-green-dark) 0%, var(--unsil-green) 100%);
        color: #ffffff;
        border-radius: 1.25rem;
        padding: 1.75rem;
        border-bottom: 4px solid var(--unsil-yellow);
        box-shadow: 0 10px 25px -8px rgba(0, 61, 34, 0.25);
    }

    /* Kartu Panel Admin */
    .admin-panel {
        background: #ffffff;
        border: 1px solid #d1e2d8;
        border-radius: 1.25rem;
        padding: 1.5rem;
        box-shadow: 0 4px 14px rgba(0, 90, 50, 0.04);
    }

    .kpi-card {
        background: #ffffff;
        border: 1px solid #d1e2d8;
        border-radius: 1.15rem;
        padding: 1.25rem;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(0, 90, 50, 0.03);
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        border-color: var(--unsil-green);
        box-shadow: 0 10px 20px -8px rgba(0, 90, 50, 0.12);
    }

    .kpi-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* Warna & Badge Khas UNSIL */
    .text-unsil { color: var(--unsil-green) !important; }
    .bg-unsil { background-color: var(--unsil-green) !important; }
    .bg-unsil-soft { background-color: var(--unsil-green-light) !important; }

    .badge-unsil-yellow {
        background-color: var(--unsil-yellow);
        color: #062818;
        font-weight: 700;
    }
    .badge-unsil-green {
        background-color: var(--unsil-green-light);
        color: var(--unsil-green);
        border: 1px solid rgba(0, 90, 50, 0.2);
        font-weight: 700;
    }
    .badge-unsil-solid {
        background-color: var(--unsil-green);
        color: #ffffff;
        font-weight: 700;
    }

    /* Tombol Admin */
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

    .btn-unsil-outline {
        border: 1px solid var(--unsil-green);
        color: var(--unsil-green);
        background-color: transparent;
        transition: all 0.2s ease;
    }
    .btn-unsil-outline:hover {
        background-color: var(--unsil-green);
        color: #ffffff;
    }

    .btn-gear-yellow {
        background-color: #fefce8;
        border: 1px solid #fde047;
        color: #854d0e;
        transition: all 0.2s ease;
    }
    .btn-gear-yellow:hover {
        background-color: var(--unsil-yellow);
        border-color: var(--unsil-yellow);
        color: #062818;
    }

    .form-control:focus {
        border-color: var(--unsil-green);
        box-shadow: 0 0 0 0.2rem rgba(0, 90, 50, 0.15);
    }
</style>
@endsection

@section('content')
@php
    // Agregasi KPI Global Kampus dari $semua_area
    $totalKapasitas = $semua_area->sum('kapasitas_total');
    $totalTerisi = $semua_area->sum('parkir_aktif_count');
    $totalSisa = max(0, $totalKapasitas - $totalTerisi);
    $okupansiGlobal = $totalKapasitas > 0 ? round(($totalTerisi / $totalKapasitas) * 100, 1) : 0;
    $jumlahAreaAktif = $semua_area->count();
@endphp

<div class="container-fluid p-0" data-aos="fade-up">

    <!-- ==================== 1. ADMIN COMMAND BANNER ==================== -->
    <div class="admin-hero-banner mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="badge badge-unsil-yellow rounded-pill px-3 py-1 small">
                        <i class="bi bi-shield-lock-fill me-1"></i> Administrator Console
                    </span>
                    <span class="badge rounded-pill px-3 py-1 small" style="background: rgba(255,255,255,0.12); color: #bbf7d0;">
                        <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
                <h3 class="fw-bold text-white mb-1" style="letter-spacing: -0.02em;">
                    Pusat Kendali E-Parkir Universitas Siliwangi
                </h3>
                <p class="mb-0 small" style="color: #bbf7d0;">
                    Pantau okupansi seluruh titik fakultas, kelola kapasitas slot dinamis, area khusus dosen, dan audit log transaksi parkir kampus.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-flex align-items-center gap-3 rounded-4 p-3 text-start" 
                     style="background: rgba(0, 0, 0, 0.22); border: 1px solid rgba(250, 204, 21, 0.35);">
                    <div>
                        <small class="d-block" style="color: #bbf7d0; font-size: 0.72rem;">Tingkat Okupansi Kampus</small>
                        <h4 class="fw-bold mb-0" style="color: var(--unsil-yellow);">{{ $okupansiGlobal }}% Terisi</h4>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 44px; height: 44px; background-color: var(--unsil-yellow); color: #062818;">
                        <i class="bi bi-bar-chart-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== 2. 4 KARTU KPI RINGKASAN EKSEKUTIF ==================== -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="kpi-card h-100" style="border-left: 4px solid var(--unsil-green);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-semibold d-block mb-1">Total Titik Area</small>
                        <h3 class="fw-bold text-dark mb-0">{{ $jumlahAreaAktif }} <span class="fs-6 fw-normal text-muted">Lokasi</span></h3>
                    </div>
                    <div class="kpi-icon-box bg-unsil-soft text-unsil">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="kpi-card h-100" style="border-left: 4px solid var(--unsil-yellow);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-semibold d-block mb-1">Kapasitas Total</small>
                        <h3 class="fw-bold text-dark mb-0">{{ $totalKapasitas }} <span class="fs-6 fw-normal text-muted">Slot</span></h3>
                    </div>
                    <div class="kpi-icon-box" style="background-color: #fefce8; color: #ca8a04;">
                        <i class="bi bi-p-square-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="kpi-card h-100" style="border-left: 4px solid var(--unsil-green);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-semibold d-block mb-1">Kendaraan Aktif</small>
                        <h3 class="fw-bold text-unsil mb-0">{{ $totalTerisi }} <span class="fs-6 fw-normal text-muted">Unit</span></h3>
                    </div>
                    <div class="kpi-icon-box bg-unsil-soft text-unsil">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="kpi-card h-100" style="border-left: 4px solid var(--unsil-yellow);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-semibold d-block mb-1">Slot Kosong Tersedia</small>
                        <h3 class="fw-bold text-dark mb-0">{{ $totalSisa }} <span class="fs-6 fw-normal text-muted">Slot</span></h3>
                    </div>
                    <div class="kpi-icon-box" style="background-color: #fefce8; color: #ca8a04;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== 3. SPLIT WORKSPACE: MANAJEMEN AREA (KIRI) & STATUS KEPADATAN (KANAN) ==================== -->
    <div class="row g-4 mb-4">
        
        <!-- KOLOM KIRI (8): TABEL MANAJEMEN AREA & KUOTA SLOT -->
        <div class="col-lg-8">
            <div class="admin-panel h-100" style="border-top: 4px solid var(--unsil-green);">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-3 border-bottom">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="bi bi-building-gear text-unsil me-1"></i> Manajemen Kapasitas & Area Parkir
                        </h5>
                        <small class="text-muted">Kelola batas kuota slot dinamis, akses khusus dosen, atau tinjau denah grid</small>
                    </div>
                    <div style="min-width: 220px;">
                        <input type="text" id="searchAreaAdmin" class="form-control form-control-sm rounded-3" placeholder="🔍 Cari kode / nama area...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tableAreaAdmin">
                        <thead style="background-color: var(--unsil-green-light); color: var(--unsil-green-dark);">
                            <tr class="small">
                                <th>KODE</th>
                                <th>NAMA AREA & KATEGORI</th>
                                <th style="min-width: 160px;">OKUPANSI SLOT</th>
                                <th class="text-center">STATUS</th>
                                <th class="text-end">KONTROL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($semua_area as $area)
                                @php
                                    $terisi = $area->parkir_aktif_count ?? 0;
                                    $sisa = max(0, $area->kapasitas_total - $terisi);
                                    $persentase = $area->kapasitas_total > 0 ? round(($terisi / $area->kapasitas_total) * 100) : 0;
                                    
                                    if ($persentase >= 100) {
                                        $statusLabel = 'PENUH';
                                        $statusBadge = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25';
                                        $barColor = 'bg-danger';
                                    } elseif ($persentase >= 80) {
                                        $statusLabel = 'PADAT';
                                        $statusBadge = 'badge-unsil-yellow';
                                        $barColor = 'bg-warning';
                                    } else {
                                        $statusLabel = 'TERSEDIA';
                                        $statusBadge = 'badge-unsil-green';
                                        $barColor = 'bg-unsil';
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge badge-unsil-solid px-2.5 py-1.5 rounded-3">{{ $area->kode_area }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                                            <span class="fw-bold text-dark">{{ $area->nama_area }}</span>
                                            @if($area->khusus_dosen || ($area->kapasitas_dosen > 0 && $area->kapasitas_dosen == $area->kapasitas_total))
                                                <span class="badge bg-dark text-warning px-2 py-0.5 rounded-pill" style="font-size: 0.65rem;">
                                                    <i class="bi bi-star-fill me-1"></i> Full Dosen
                                                </span>
                                            @elseif($area->kapasitas_dosen > 0)
                                                <span class="badge bg-warning bg-opacity-25 text-dark border border-warning px-2 py-0.5 rounded-pill" style="font-size: 0.65rem;">
                                                    Campuran (Dosen & Umum)
                                                </span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-0.5 rounded-pill" style="font-size: 0.65rem;">
                                                    Umum
                                                </span>
                                            @endif
                                        </div>
                                        <small class="text-muted d-block">
                                            Alokasi: <strong class="text-dark">{{ $area->kapasitas_dosen ?? 0 }}</strong> Dosen &bull; 
                                            <strong class="text-unsil">{{ max(0, $area->kapasitas_total - ($area->kapasitas_dosen ?? 0)) }}</strong> Umum
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between small fw-semibold mb-1">
                                            <span>{{ $terisi }} / {{ $area->kapasitas_total }}</span>
                                            <span class="text-muted">{{ $persentase }}%</span>
                                        </div>
                                        <div class="progress rounded-pill" style="height: 7px; background-color: #e6f4ea;">
                                            <div class="progress-bar {{ $barColor }} rounded-pill" style="width: {{ $persentase }}%;"></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $statusBadge }} px-2.5 py-1 rounded-pill" style="font-size: 0.7rem;">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1.5">
                                            <a href="{{ route('admin.area.slots', $area->id) }}" 
                                               class="btn btn-unsil-outline btn-sm rounded-3 fw-semibold px-2.5" 
                                               title="Lihat Denah Slot Grid">
                                                <i class="bi bi-grid-3x3-gap-fill me-1"></i> Grid
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-gear-yellow btn-sm rounded-3 px-2.5" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalKuota{{ $area->id }}"
                                                    title="Atur Kapasitas & Kategori Area">
                                                <i class="bi bi-sliders"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data area parkir.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN (4): PANEL RINGKASAN KEPADATAN & INFORMASI SISTEM -->
        <div class="col-lg-4">
            <div class="admin-panel h-100 d-flex flex-column justify-content-between" style="border-top: 4px solid var(--unsil-yellow);">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-activity text-unsil me-1"></i> Peringkat Kepadatan Area
                        </h6>
                        <span class="badge badge-unsil-yellow rounded-pill small">Live</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @foreach($semua_area->sortByDesc('parkir_aktif_count')->take(5) as $topArea)
                            @php
                                $tTerisi = $topArea->parkir_aktif_count ?? 0;
                                $tPersen = $topArea->kapasitas_total > 0 ? round(($tTerisi / $topArea->kapasitas_total) * 100) : 0;
                            @endphp
                            <div class="p-2.5 rounded-3 border" style="background-color: #f8faf9; border-color: #d1e2d8 !important;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark small text-truncate pe-2">
                                        {{ $topArea->nama_area }}
                                        @if(!empty($topArea->khusus_dosen) && $topArea->khusus_dosen)
                                            <span class="badge bg-dark text-warning ms-1" style="font-size: 0.6rem;">Dosen</span>
                                        @endif
                                    </span>
                                    <span class="badge bg-unsil-soft text-unsil small">{{ $tPersen }}%</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 6px; background-color: #d1e2d8;">
                                    <div class="progress-bar {{ $tPersen >= 90 ? 'bg-danger' : 'bg-unsil' }}" style="width: {{ $tPersen }}%;"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1" style="font-size: 0.72rem;">
                                    <span class="text-muted">Terisi: {{ $tTerisi }} unit</span>
                                    <span class="text-unsil fw-semibold">Sisa: {{ max(0, $topArea->kapasitas_total - $tTerisi) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top small text-muted">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill text-unsil"></i>
                        <span>Klik ikon <i class="bi bi-sliders text-warning"></i> pada tabel untuk mengubah kuota slot atau mengaktifkan mode <strong>Khusus Dosen</strong>.</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ==================== 4. LOG AUDIT RIWAYAT PARKIR SELESAI ==================== -->
    <div class="admin-panel" style="border-top: 4px solid var(--unsil-green);">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-2 border-bottom">
            <div>
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-journal-check text-unsil me-1"></i> Log Transaksi Parkir Selesai
                </h5>
                <small class="text-muted">Rekam jejak kendaraan yang telah menyelesaikan sesi parkir</small>
            </div>
            <div style="min-width: 230px;">
                <input type="text" id="searchHistoryAdmin" class="form-control form-control-sm rounded-3" placeholder="🔍 Filter plat / area / slot...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableHistoryAdmin">
                <thead style="background-color: var(--unsil-green-light); color: var(--unsil-green-dark);">
                    <tr class="small">
                        <th>PLAT NOMOR</th>
                        <th>JENIS KENDARAAN</th>
                        <th>LOKASI AREA</th>
                        <th>VIRTUAL SLOT</th>
                        <th>WAKTU KELUAR</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat_parkir as $item)
                        <tr>
                            <td><strong class="text-uppercase text-dark">{{ $item->plat_nomor }}</strong></td>
                            <td>
                                <span class="badge bg-unsil-soft text-unsil border border-success border-opacity-25">
                                    {{ $item->jenis_kendaraan }}
                                </span>
                            </td>
                            <td class="fw-medium text-dark">{{ $item->area->nama_area ?? '-' }}</td>
                            <td><span class="badge badge-unsil-solid px-2.5 py-1">{{ $item->kode_slot_formatted }}</span></td>
                            <td class="small text-muted">
                                <i class="bi bi-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($item->waktu_keluar)->format('d M Y, H:i') }} WIB
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat transaksi parkir selesai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ==================== LOOP KHUSUS UNTUK MODAL KONFIGURASI KUOTA & KATEGORI (DI LUAR CONTAINER AOS) ==================== -->
@foreach($semua_area as $area)
    @php
        $kuotaDosenSaatIni = (int) ($area->kapasitas_dosen ?? 0);
        $kuotaUmumSaatIni  = max(0, (int) $area->kapasitas_total - $kuotaDosenSaatIni);
    @endphp
    <div class="modal fade" id="modalKuota{{ $area->id }}" tabindex="-1" aria-labelledby="modalKuotaLabel{{ $area->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="modal-header text-white border-0" style="background-color: var(--unsil-green); border-bottom: 3px solid var(--unsil-yellow) !important;">
                    <h6 class="modal-title fw-bold" id="modalKuotaLabel{{ $area->id }}">
                        <i class="bi bi-sliders text-warning me-1"></i> Atur Kapasitas — {{ $area->nama_area }}
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.update_kuota') }}" method="POST">
                    @csrf
                    <input type="hidden" name="area_id" value="{{ $area->id }}">
                    <div class="modal-body p-4">
                        <div class="p-3 rounded-3 mb-3 small d-flex justify-content-between" 
                             style="background-color: var(--unsil-green-light); color: var(--unsil-green-dark);">
                            <span>Kode Area: <strong>{{ $area->kode_area }}</strong></span>
                            <span>Sedang Terisi: <strong>{{ $area->parkir_aktif_count ?? 0 }} / {{ $area->kapasitas_total }} Slot</strong></span>
                        </div>

                        <div class="row g-3 mb-3">
                            <!-- INPUT KAPASITAS KHUSUS DOSEN -->
                            <div class="col-6">
                                <label class="form-label small fw-bold text-dark">
                                    <i class="bi bi-star-fill text-warning me-1"></i> Kuota Khusus Dosen
                                </label>
                                <input type="number" 
                                       name="kapasitas_dosen" 
                                       id="inputDosen{{ $area->id }}"
                                       class="form-control form-control-lg rounded-3 fw-bold border-warning" 
                                       value="{{ $kuotaDosenSaatIni }}" 
                                       min="0" 
                                       oninput="hitungTotalSlot({{ $area->id }})"
                                       required>
                                <small class="text-muted" style="font-size: 0.7rem;">Slot {{ $area->kode_area }}001 – awal</small>
                            </div>

                            <!-- INPUT KAPASITAS UMUM / MAHASISWA -->
                            <div class="col-6">
                                <label class="form-label small fw-bold text-unsil">
                                    <i class="bi bi-mortarboard-fill me-1"></i> Kuota Umum / Mhs
                                </label>
                                <input type="number" 
                                       name="kapasitas_umum" 
                                       id="inputUmum{{ $area->id }}"
                                       class="form-control form-control-lg rounded-3 fw-bold" 
                                       value="{{ $kuotaUmumSaatIni }}" 
                                       min="0" 
                                       {{ $area->khusus_dosen ? 'readonly' : '' }}
                                       oninput="hitungTotalSlot({{ $area->id }})"
                                       required>
                                <small class="text-muted" style="font-size: 0.7rem;">Slot setelah zona dosen</small>
                            </div>
                        </div>

                        <!-- PREVIEW TOTAL SLOT OTOMATIS -->
                        <div class="alert alert-light border d-flex justify-content-between align-items-center py-2 px-3 rounded-3 mb-3">
                            <span class="small text-muted fw-semibold">Total Kapasitas Gabungan:</span>
                            <span class="fw-bold fs-6 text-unsil"><span id="previewTotal{{ $area->id }}">{{ $area->kapasitas_total }}</span> Slot</span>
                        </div>

                        <!-- OPSI KUNCI FULL KHUSUS DOSEN -->
                        <div class="p-3 rounded-3 border" style="background-color: #fefce8; border-color: #fde047 !important;">
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" role="switch" id="switchDosen{{ $area->id }}" 
                                       name="khusus_dosen" value="1" 
                                       {{ $area->khusus_dosen ? 'checked' : '' }}
                                       onchange="toggleFullDosen({{ $area->id }})">
                                <label class="form-check-label fw-bold text-dark small" for="switchDosen{{ $area->id }}">
                                    Kunci Area 100% Khusus Dosen
                                </label>
                            </div>
                            <small class="text-muted d-block" style="font-size: 0.72rem;">
                                Jika diaktifkan, kuota umum otomatis menjadi 0 dan seluruh area ini hanya bisa dimasuki oleh Dosen.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-unsil-primary rounded-3 fw-semibold px-4">Simpan Kapasitas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<script>
    function hitungTotalSlot(areaId) {
        const dosen = parseInt(document.getElementById('inputDosen' + areaId).value) || 0;
        const umum  = parseInt(document.getElementById('inputUmum' + areaId).value) || 0;
        document.getElementById('previewTotal' + areaId).innerText = dosen + umum;
    }

    function toggleFullDosen(areaId) {
        const isChecked = document.getElementById('switchDosen' + areaId).checked;
        const inputUmum = document.getElementById('inputUmum' + areaId);
        if (isChecked) {
            inputUmum.value = 0;
            inputUmum.setAttribute('readonly', true);
        } else {
            inputUmum.removeAttribute('readonly');
        }
        hitungTotalSlot(areaId);
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Filter Pencarian Tabel Area
        const searchArea = document.getElementById('searchAreaAdmin');
        if (searchArea) {
            searchArea.addEventListener('keyup', function() {
                const val = this.value.toLowerCase();
                document.querySelectorAll('#tableAreaAdmin tbody tr').forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
                });
            });
        }

        // Filter Pencarian Tabel Riwayat Selesai
        const searchHistory = document.getElementById('searchHistoryAdmin');
        if (searchHistory) {
            searchHistory.addEventListener('keyup', function() {
                const val = this.value.toLowerCase();
                document.querySelectorAll('#tableHistoryAdmin tbody tr').forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection