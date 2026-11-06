@extends('layout.dashboard-master')

@section('title', 'Dashboard Admin - E-Parkir')

@section('custom-css')
<style>
    .grid-management { display: grid; grid-template-columns: 1fr 2fr; gap: 24px; }
    .area-box { border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-top: 12px; background: #f8fafc; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; text-align: left; }
    th, td { padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 0.9rem; }
    th { background-color: #f8fafc; color: #64748b; font-weight: 600; }
    @media (max-width: 900px) { .grid-management { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="mb-4" data-aos="fade-down">
    <h3 class="fw-bold mb-1">🛠️ Panel Pengaturan Admin</h3>
    <p class="text-muted small mb-0">Manajemen batas kapasitas parkir fakultas & riwayat transaksi.</p>
</div>

@if(session('success'))
    <div class="alert alert-success" data-aos="fade-right">{{ session('success') }}</div>
@endif

<div class="grid-management">
    <!-- Konfigurasi Kuota -->
    <div class="dash-card" data-aos="fade-right">
        <h4 class="fw-bold pb-2 border-bottom">Konfigurasi Kuota Area</h4>
        @foreach($semua_area as $area)
            <div class="area-box">
                <h5 class="fw-bold text-dark mb-1">📍 {{ $area->nama_area }}</h5>
                <p class="small text-muted mb-2">Kapasitas Maksimal: <strong>{{ $area->kapasitas_total }} Slot</strong></p>

                <form action="{{ route('admin.update_kuota') }}" method="POST">
                    @csrf
                    <input type="hidden" name="area_id" value="{{ $area->id }}">
                    <div class="input-group input-group-sm">
                        <input type="number" name="kapasitas_baru" class="form-control" value="{{ $area->kapasitas_total }}" min="1" required>
                        <button type="submit" class="btn btn-dark">Update</button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>

    <!-- Riwayat Selesai -->
    <div class="dash-card" data-aos="fade-left">
        <h4 class="fw-bold pb-2 border-bottom">Riwayat Transaksi Parkir Selesai</h4>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Plat Nomor</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th>Input</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat_parkir as $log)
                        <tr>
                            <td class="fw-bold">{{ $log->plat_nomor }}</td>
                            <td class="text-muted">{{ $log->created_at->format('d M, H:i') }}</td>
                            <td class="text-muted">{{ $log->waktu_keluar->format('d M, H:i') }}</td>
                            <td><span class="badge bg-secondary">{{ $log->input_oleh }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat transaksi selesai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection