@extends('layout.dashboard-master')

@section('title', 'Dashboard Jukir - E-Parkir')

@section('custom-css')
<style>
    .dashboard-layout { display: grid; grid-template-columns: 1fr 2fr; gap: 24px; }
    .status-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; background-color: #e0f2fe; color: #0369a1; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; text-align: left; }
    th, td { padding: 12px; border-bottom: 1px solid #e2e8f0; }
    th { background-color: #f8fafc; color: #64748b; font-weight: 600; }
    @media (max-width: 768px) { .dashboard-layout { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<!-- HEADER UTAMA JUKIR -->
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <h3 class="fw-bold mb-1">👮 Area Operasional Jukir</h3>
        <p class="text-muted small mb-0">Lokasi Tugas: <strong>📍 {{ $area->nama_area }}</strong></p>
    </div>
    <div class="bg-dark text-white px-3 py-2 rounded-3 fw-bold shadow-sm">
        Sisa Kuota Area Ini: {{ $sisa_kuota }} / {{ $area->kapasitas_total }} Slot
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm" data-aos="fade-right">{{ session('success') }}</div>
@endif

<!-- GRID LAYOUT UTAMA (INPUT MANUAL & TABEL KENDARAAN AKTIF) -->
<div class="dashboard-layout mb-5">
    <!-- Form Input Manual Jukir -->
    <div class="dash-card" data-aos="fade-right">
        <h4 class="fw-bold mb-3">Input Kendaraan Manual</h4>
        
        <!-- DROPDOWN PILIH LOKASI TUGAS JUKIR -->
        <div class="mb-3">
            <label class="form-label text-muted small fw-semibold">Pilih Area Fakultas Tugas:</label>
            <select class="form-select form-select-lg fw-bold border-primary border-opacity-50" 
                    onchange="location = this.value;">
                @foreach($semua_area as $itemArea)
                    <option value="{{ route('jukir.dashboard', ['area_id' => $itemArea->id]) }}" 
                            {{ $area->id == $itemArea->id ? 'selected' : '' }}>
                        📍 {{ $itemArea->nama_area }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <!-- FORM CHECK-IN JUKIR -->
        <form action="{{ route('parkir.checkin') }}" method="POST">
            @csrf
            <!-- ID Area mengikuti area yang sedang dipilih di dropdown -->
            <input type="hidden" name="area_id" value="{{ $area->id }}">
            <input type="hidden" name="input_oleh" value="JUKIR">
            
            <div class="mb-3">
                <label class="form-label text-muted small fw-semibold">Plat Nomor Kendaraan</label>
                <input type="text" name="plat_nomor" class="form-control form-control-lg text-uppercase fw-bold" placeholder="Z 5432 AB" required autocomplete="off">
            </div>
            
            <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold shadow-sm" {{ $sisa_kuota <= 0 ? 'disabled' : '' }}>
                {{ $sisa_kuota <= 0 ? 'Kuota Parkir Penuh' : 'Check-In Kendaraan' }}
            </button>
        </form>
    </div>

    <!-- Tabel Kendaraan Aktif -->
    <div class="dash-card" data-aos="fade-left">
        <h4 class="fw-bold mb-3">Daftar Kendaraan Aktif Parkir ({{ $area->nama_area }})</h4>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Plat Nomor</th>
                        <th>Jam Masuk</th>
                        <th>Input Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kendaraan_aktif as $k)
                    <tr>
                        <td class="fw-bold">{{ $k->plat_nomor }}</td>
                        <td class="text-muted">{{ $k->created_at->format('H:i:s') }} WIB</td>
                        <td><span class="status-badge">{{ $k->input_oleh }}</span></td>
                        <td>
                            <form action="{{ route('parkir.checkout', $k->id) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm px-3">Check-Out</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada kendaraan yang parkir di area ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SECTION TAMBAHAN: MONITORING SLOT PARKIR SELURUH FAKULTAS -->
<div class="dash-card" data-aos="fade-up" data-aos-delay="200">
    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="bi bi-p-square-fill text-primary fs-4"></i> Monitoring Kuota Seluruh Fakultas
            </h4>
            <p class="text-muted small mb-0">Pantau ketersediaan slot parkir di area fakultas lain secara komprehensif.</p>
        </div>
        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill small fw-semibold">
            <i class="bi bi-arrow-repeat me-1"></i> Update Real-time
        </span>
    </div>

    @if(isset($semua_area) && count($semua_area) > 0)
        <div class="row g-3">
            @foreach($semua_area as $areaItem)
                @php
                    // Perhitungan Kuota Terisi & Sisa Slot
                    $terisi = $areaItem->parkir_aktif_count ?? ($areaItem->parkirAktif ? $areaItem->parkirAktif->count() : 0);
                    $sisaSlot = max(0, $areaItem->kapasitas_total - $terisi);
                    $persentaseTerisi = $areaItem->kapasitas_total > 0 ? ($terisi / $areaItem->kapasitas_total) * 100 : 100;
                    $isAreaJukir = ($areaItem->id == $area->id);

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

                <!-- ITEM KARTU PARKIR FAKULTAS (VERTIKAL UTUH) -->
                <div class="col-12">
                    <div class="card border rounded-4 bg-light p-3 p-md-4 {{ $isAreaJukir ? 'border-primary border-2 shadow-sm' : 'border-secondary border-opacity-10' }}">
                        
                        <!-- Header Nama Area & Badge Status -->
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-secondary border-opacity-10">
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="fw-bold text-dark mb-0">
                                    📍 {{ $areaItem->nama_area }}
                                </h5>
                                @if($isAreaJukir)
                                    <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 small fw-bold">
                                        <i class="bi bi-geo-alt-fill me-1"></i> LOKASI PARKIR
                                    </span>
                                @endif
                            </div>
                            <span class="badge {{ $badgeColor }} bg-opacity-10 border border-current px-3 py-1.5 rounded-pill small fw-bold" style="font-size: 0.75rem;">
                                {{ $badgeStatus }}
                            </span>
                        </div>

                        <!-- Detail Statistik & Progress Bar Kuota -->
                        <div class="row align-items-center g-3">
                            <div class="col-md-9 col-sm-8 col-12">
                                <div class="d-flex justify-content-between align-items-center small text-muted mb-1" style="font-size: 0.85rem;">
                                    <span>Lahan Terisi: <strong class="text-dark">{{ $terisi }}</strong></span>
                                    <span>Kapasitas Total: <strong class="text-dark">{{ $areaItem->kapasitas_total }}</strong> Slot</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 10px; background-color: #cbd5e1;">
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

                            <!-- Angka Sisa Slot Kosong -->
                            <div class="col-md-3 col-sm-4 col-12 text-start text-sm-end border-start-sm">
                                <span class="fs-2 fw-bold text-dark d-block lh-1">{{ $sisaSlot }}</span>
                                <small class="text-muted fw-semibold d-block" style="font-size: 0.75rem;">Slot Kosong Tersedia</small>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info bg-info bg-opacity-10 border border-info border-opacity-25 rounded-3 p-3 text-center text-info mb-0">
            <i class="bi bi-info-circle-fill me-1"></i> Data monitoring area fakultas belum dimuat.
        </div>
    @endif
</div>
@endsection