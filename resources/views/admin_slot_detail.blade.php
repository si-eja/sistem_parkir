@extends('layout.master')

@section('title', 'Detail Slot Area - E-Parkir')

@section('content')
<div class="container py-3" data-aos="fade-up">
    
    <!-- HEADER NAVIGASI -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 mb-2">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
            <h4 class="fw-bold mb-0">Monitoring Grid Slot: {{ $area->nama_area }}</h4>
            <span class="text-muted small">Total Kapasitas: <strong>{{ $area->kapasitas_total }} Slot</strong> (Prefix: {{ $area->kode_area }})</span>
        </div>
    </div>

    <!-- GRID FLEX 200 SLOT -->
    <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="row g-2">
            @foreach($listSlot as $slot)
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