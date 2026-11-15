@extends('layout.dashboard-master')

@section('title', 'Monitoring Slot Lapangan - E-Parkir UNSIL')

@section('custom-css')
<style>
    :root {
        --unsil-green: #005a32;
        --unsil-green-dark: #003d22;
        --unsil-green-light: #e6f4ea;
        --unsil-yellow: #facc15;
        --unsil-red: #dc2626;
    }

    .dash-card {
        background: #ffffff;
        border: 1px solid #d1e2d8;
        border-radius: 1.25rem;
        padding: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 90, 50, 0.04);
    }

    .card-unsil-hero {
        background: linear-gradient(135deg, var(--unsil-green-dark) 0%, var(--unsil-green) 100%);
        color: #ffffff;
        border: none;
        border-bottom: 4px solid var(--unsil-yellow);
    }

    .text-unsil { color: var(--unsil-green) !important; }
    .bg-unsil-soft { background-color: var(--unsil-green-light) !important; }
    .badge-unsil-yellow { background-color: var(--unsil-yellow); color: #062818; font-weight: 700; }

    /* Grid Slot Lapangan Interaktif */
    .slot-box {
        border-radius: 12px;
        padding: 10px 6px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
        user-select: none;
    }
    .slot-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
    }

    .slot-box.kosong {
        background-color: #e6f4ea;
        border-color: #86efac;
        color: #005a32;
    }
    .slot-box.terisi {
        background-color: #fef2f2;
        border-color: #fca5a5;
        color: #b91c1c;
    }

    .btn-checkout-minimal {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: var(--unsil-red);
    }
    .btn-checkout-minimal:hover {
        background-color: var(--unsil-red);
        color: #ffffff;
    }
</style>
@endsection

@section('content')
@php
    $terisiCount = $kendaraan_aktif->count();
    $persenTerisi = $area->kapasitas_total > 0 ? round(($terisiCount / $area->kapasitas_total) * 100) : 0;
    $jumlahMotor = $kendaraan_aktif->where('jenis_kendaraan', 'MOTOR')->count();
    $jumlahMobil = $kendaraan_aktif->where('jenis_kendaraan', 'MOBIL')->count();
    $mapSlotTerisi = $kendaraan_aktif->keyBy('nomor_slot');
@endphp

<div class="container-fluid p-0" data-aos="fade-up">

    <!-- 1. HEADER AREA MONITORING & SWITCH AREA -->
    <div class="dash-card card-unsil-hero mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-7">
                <span class="badge badge-unsil-yellow rounded-pill px-3 py-1 mb-2 small">
                    <i class="bi bi-grid-3x3-gap-fill me-1"></i> Pemantauan Denah Slot Lapangan
                </span>
                <h4 class="fw-bold text-white mb-1">📍 {{ $area->nama_area }}</h4>
                <small style="color: #bbf7d0;">Klik kotak slot mana pun untuk melihat detail kendaraan atau check-out cepat.</small>
            </div>
            <div class="col-lg-5">
                <label class="form-label small mb-1" style="color: #bbf7d0;">Pilih Area Pemantauan:</label>
                <select class="form-select fw-bold border-0 rounded-3 shadow-sm" onchange="location = '?area_id=' + this.value;">
                    @foreach($semua_area as $item)
                        <option value="{{ $item->id }}" {{ $area->id == $item->id ? 'selected' : '' }}>
                            📍 {{ $item->nama_area }} (Sisa: {{ max(0, $item->kapasitas_total - ($item->parkir_aktif_count ?? 0)) }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- 2. RINGKASAN STATISTIK LAPANGAN (4 KARTU) -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dash-card h-100" style="border-left: 4px solid var(--unsil-green);">
                <small class="text-muted d-block">Slot Tersedia</small>
                <h4 class="fw-bold text-unsil mb-0">{{ $sisa_kuota }} <span class="fs-6 fw-normal text-muted">/ {{ $area->kapasitas_total }}</span></h4>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dash-card h-100" style="border-left: 4px solid var(--unsil-yellow);">
                <small class="text-muted d-block">Okupansi Lahan</small>
                <h4 class="fw-bold text-dark mb-1">{{ $persenTerisi }}%</h4>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar {{ $persenTerisi >= 85 ? 'bg-danger' : 'bg-success' }}" style="width: {{ $persenTerisi }}%;"></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dash-card h-100" style="border-left: 4px solid var(--unsil-green);">
                <small class="text-muted d-block">Rincian Kendaraan Aktif</small>
                <div class="fw-bold text-dark mt-1">
                    <span class="me-2">🛵 {{ $jumlahMotor }} Motor</span>
                    <span>🚗 {{ $jumlahMobil }} Mobil</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dash-card h-100" style="border-left: 4px solid var(--unsil-yellow);">
                <small class="text-muted d-block">Selesai Keluar Hari Ini</small>
                <h4 class="fw-bold text-dark mb-0">{{ $riwayat_hari_ini->count() }} <span class="fs-6 fw-normal text-muted">Kendaraan</span></h4>
            </div>
        </div>
    </div>

    <!-- 3. VISUAL DENAH GRID SLOT LAPANGAN INTERAKTIF -->
    <div class="dash-card mb-4" style="border-top: 4px solid var(--unsil-green);">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-3 border-bottom">
            <div>
                <h5 class="fw-bold text-dark mb-0">Denah Virtual Slot ({{ $area->kode_area }})</h5>
                <small class="text-muted">Hijau = Kosong siap ditempati | Merah = Sedang terisi kendaraan</small>
            </div>

            <!-- Filter Cepat Status & Pencarian Slot/Plat -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-success active filter-slot-btn" data-filter="ALL">Semua ({{ $area->kapasitas_total }})</button>
                    <button type="button" class="btn btn-outline-success filter-slot-btn" data-filter="KOSONG">Kosong ({{ $sisa_kuota }})</button>
                    <button type="button" class="btn btn-outline-danger filter-slot-btn" data-filter="TERISI">Terisi ({{ $terisiCount }})</button>
                </div>
                <input type="text" id="searchSlotInput" class="form-control form-control-sm rounded-3" style="width: 180px;" placeholder="🔍 Cari Slot / Plat...">
            </div>
        </div>

        <!-- KOTAK GRID SLOT -->
        <div class="row g-2" id="gridSlotContainer">
            @for($i = 1; $i <= $area->kapasitas_total; $i++)
                @php
                    $dataParkir = $mapSlotTerisi->get($i);
                    $isTerisi = !is_null($dataParkir);
                    $kodeSlot = $area->kode_area . str_pad($i, 3, '0', STR_PAD_LEFT);
                @endphp

                <div class="col-4 col-sm-3 col-md-2 col-lg-1 slot-item"
                     data-status="{{ $isTerisi ? 'TERISI' : 'KOSONG' }}"
                     data-search="{{ strtolower($kodeSlot . ' ' . ($isTerisi ? $dataParkir->plat_nomor : '')) }}">
                    
                    <div class="slot-box {{ $isTerisi ? 'terisi' : 'kosong' }}"
                         onclick="openSlotDetailModal(
                             '{{ $kodeSlot }}',
                             {{ $isTerisi ? 'true' : 'false' }},
                             '{{ $isTerisi ? $dataParkir->plat_nomor : '' }}',
                             '{{ $isTerisi ? ($dataParkir->jenis_kendaraan ?? 'MOTOR') : '' }}',
                             '{{ $isTerisi ? \Carbon\Carbon::parse($dataParkir->created_at)->format('H:i') . ' WIB' : '' }}',
                             '{{ $isTerisi ? route('parkir.checkout', $dataParkir->id) : route('jukir.dashboard', ['area_id' => $area->id]) }}'
                         )">
                        <div class="fw-bold small">{{ $kodeSlot }}</div>
                        <div class="text-truncate" style="font-size: 0.68rem;">
                            {{ $isTerisi ? $dataParkir->plat_nomor : 'Kosong' }}
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- 4. LOG KENDARAAN KELUAR SHIFT HARI INI -->
    <div class="dash-card" style="border-top: 4px solid var(--unsil-yellow);">
        <h6 class="fw-bold text-dark mb-3">
            <i class="bi bi-clock-history text-unsil me-1"></i> Riwayat Kendaraan Keluar Hari Ini (10 Terakhir)
        </h6>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead style="background-color: var(--unsil-green-light); color: var(--unsil-green-dark);">
                    <tr>
                        <th>Slot</th>
                        <th>Plat Nomor</th>
                        <th>Jenis</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat_hari_ini as $log)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $log->kode_slot_formatted }}</span></td>
                            <td class="fw-bold text-uppercase">{{ $log->plat_nomor }}</td>
                            <td class="small">{{ $log->jenis_kendaraan ?? 'MOTOR' }}</td>
                            <td class="small text-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }} WIB</td>
                            <td class="small text-unsil fw-semibold">{{ \Carbon\Carbon::parse($log->updated_at)->format('H:i') }} WIB</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3 small">Belum ada kendaraan yang check-out hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL DETAIL SLOT SAAT KOTAK SLOT DIKLIK -->
<div class="modal fade" id="modalSlotDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header text-white border-0" style="background-color: var(--unsil-green); border-bottom: 3px solid var(--unsil-yellow) !important;">
                <h6 class="modal-title fw-bold">Detail Slot <span id="modalSlotCode" class="text-warning"></span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div id="modalSlotOccupied" class="d-none">
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-1 rounded-pill mb-2">TERISI</span>
                    <h4 class="fw-bold text-dark text-uppercase mb-1" id="modalPlatNomor"></h4>
                    <p class="text-muted small mb-3">
                        <span id="modalJenis"></span> &bull; Masuk pukul <strong id="modalWaktuMasuk"></strong>
                    </p>
                    <form id="modalCheckoutForm" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-checkout-minimal w-100 rounded-3 fw-bold py-2">
                            <i class="bi bi-box-arrow-right me-1"></i> Proses Check-Out Slot Ini
                        </button>
                    </form>
                </div>

                <div id="modalSlotEmpty" class="d-none">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-1 rounded-pill mb-2">KOSONG</span>
                    <p class="text-muted small mb-3">Slot parkir ini tersedia dan siap ditempati pengendara.</p>
                    <a id="modalGoCheckin" href="#" class="btn btn-unsil-primary w-100 rounded-3 fw-semibold py-2">
                        <i class="bi bi-qr-code-scan text-warning me-1"></i> Ke POS Check-In
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openSlotDetailModal(kodeSlot, isTerisi, plat, jenis, waktuMasuk, actionUrl) {
        document.getElementById('modalSlotCode').innerText = kodeSlot;
        const occupiedBox = document.getElementById('modalSlotOccupied');
        const emptyBox = document.getElementById('modalSlotEmpty');

        if (isTerisi) {
            occupiedBox.classList.remove('d-none');
            emptyBox.classList.add('d-none');
            document.getElementById('modalPlatNomor').innerText = plat;
            document.getElementById('modalJenis').innerText = jenis;
            document.getElementById('modalWaktuMasuk').innerText = waktuMasuk;
            document.getElementById('modalCheckoutForm').action = actionUrl;
        } else {
            emptyBox.classList.remove('d-none');
            occupiedBox.classList.add('d-none');
            document.getElementById('modalGoCheckin').href = actionUrl;
        }

        new bootstrap.Modal(document.getElementById('modalSlotDetail')).show();
    }

    document.addEventListener("DOMContentLoaded", function() {
        let currentFilter = 'ALL';
        const searchInput = document.getElementById('searchSlotInput');

        function applySlotFilter() {
            const keyword = searchInput.value.toLowerCase();
            document.querySelectorAll('.slot-item').forEach(item => {
                const status = item.getAttribute('data-status');
                const searchData = item.getAttribute('data-search');
                const matchStatus = (currentFilter === 'ALL') || (status === currentFilter);
                const matchKeyword = searchData.includes(keyword);
                item.style.display = (matchStatus && matchKeyword) ? '' : 'none';
            });
        }

        document.querySelectorAll('.filter-slot-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-slot-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.getAttribute('data-filter');
                applySlotFilter();
            });
        });

        searchInput.addEventListener('keyup', applySlotFilter);
    });
</script>
@endsection