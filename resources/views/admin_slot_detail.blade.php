@extends('layout.master')

@section('title', 'Detail Slot Area - E-Parkir')

@section('content')
@php
    $batasDosen = (int) ($area->kapasitas_dosen ?? 0);
    $slotDosen  = collect($listSlot)->filter(fn($s, $idx) => ($idx + 1) <= $batasDosen);
    $slotUmum   = collect($listSlot)->filter(fn($s, $idx) => ($idx + 1) > $batasDosen);
@endphp

<div class="container py-3" data-aos="fade-up">
    
    <!-- HEADER NAVIGASI -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 mb-2">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
            <h4 class="fw-bold mb-0">Monitoring Grid Slot: {{ $area->nama_area }}</h4>
            <span class="text-muted small">
                Total Kapasitas: <strong>{{ $area->kapasitas_total }} Slot</strong> 
                ({{ $batasDosen }} Khusus Dosen &bull; {{ max(0, $area->kapasitas_total - $batasDosen) }} Mahasiswa/Umum) &bull; Prefix: <strong>{{ $area->kode_area }}</strong>
            </span>
        </div>
    </div>

    <!-- ZONA 1: KHUSUS DOSEN -->
    @if($batasDosen > 0)
        <div class="card border shadow-sm rounded-4 p-4 mb-4" style="background-color: #fefce8; border-color: #fde047 !important;">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-2 border-bottom border-warning border-opacity-50">
                <span class="badge bg-dark text-warning px-3 py-2 rounded-pill">
                    <i class="bi bi-star-fill me-1"></i> ZONA KHUSUS DOSEN ({{ $area->kode_area }}001 — {{ $area->kode_area . str_pad($batasDosen, 3, '0', STR_PAD_LEFT) }})
                </span>
                <small class="fw-semibold text-dark">Alokasi Prioritas: {{ $batasDosen }} Slot</small>
            </div>

            <div class="row g-2">
                @foreach($slotDosen as $slot)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="p-2 border rounded-3 text-center {{ $slot['status'] === 'TERISI' ? 'bg-danger bg-opacity-10 border-danger' : 'bg-white border-warning' }}">
                            <small class="fw-bold d-block {{ $slot['status'] === 'TERISI' ? 'text-danger' : 'text-dark' }}">
                                {{ $slot['kode_slot'] }}
                            </small>

                            @if($slot['status'] === 'TERISI')
                                <span class="badge bg-danger d-block mt-1" style="font-size: 0.65rem;">
                                    {{ $slot['data']->plat_nomor }}
                                </span>
                                <small class="text-muted d-block" style="font-size: 0.6rem;">{{ $slot['data']->jenis_kendaraan }}</small>
                            @else
                                <span class="badge bg-warning bg-opacity-25 text-dark d-block mt-1" style="font-size: 0.65rem;">KOSONG (DOSEN)</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ZONA 2: MAHASISWA & UMUM -->
    <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-2 border-bottom">
            <span class="badge px-3 py-2 rounded-pill" style="background-color: #e6f4ea; color: #005a32;">
                <i class="bi bi-mortarboard-fill me-1"></i> ZONA MAHASISWA & UMUM ({{ $area->kode_area . str_pad($batasDosen + 1, 3, '0', STR_PAD_LEFT) }} — {{ $area->kode_area . str_pad($area->kapasitas_total, 3, '0', STR_PAD_LEFT) }})
            </span>
            <small class="fw-semibold text-muted">Alokasi Reguler: {{ max(0, $area->kapasitas_total - $batasDosen) }} Slot</small>
        </div>

        <div class="row g-2">
            @foreach($slotUmum as $slot)
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="p-2 border rounded-3 text-center {{ $slot['status'] === 'TERISI' ? 'bg-danger bg-opacity-10 border-danger' : 'bg-success bg-opacity-10 border-success' }}">
                        <small class="fw-bold d-block {{ $slot['status'] === 'TERISI' ? 'text-danger' : 'text-success' }}">
                            {{ $slot['kode_slot'] }}
                        </small>

                        @if($slot['status'] === 'TERISI')
                            <span class="badge bg-danger d-block mt-1" style="font-size: 0.65rem;">
                                {{ $slot['data']->plat_nomor }}
                            </span>
                            <small class="text-muted d-block" style="font-size: 0.6rem;">{{ $slot['data']->jenis_kendaraan }}</small>
                        @else
                            <span class="badge bg-success bg-opacity-25 text-success d-block mt-1" style="font-size: 0.65rem;">KOSONG</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection