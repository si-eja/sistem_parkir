@extends('layout.dashboard-master')

@section('title', 'Dashboard Admin - E-Parkir')

@section('content')
<div class="container-fluid p-0" data-aos="fade-up">

    <!-- ALERT NOTIFIKASI -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 bg-success bg-opacity-10 text-success rounded-4 p-3 mb-4 shadow-sm" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 bg-danger bg-opacity-10 text-danger rounded-4 p-3 mb-4 shadow-sm" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- TITLE HEADER -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Overview Lahan Parkir</h4>
            <p class="text-muted small mb-0">Manajemen kapasitas dan monitoring virtual slot per area fakultas</p>
        </div>
    </div>

    <!-- GRID CARDS PER AREA -->
    <div class="row g-4 mb-4">
        @foreach($semua_area as $area)
            @php
                $terisi = $area->parkir_aktif_count ?? 0;
                $sisa = max(0, $area->kapasitas_total - $terisi);
                $persentase = $area->kapasitas_total > 0 ? ($terisi / $area->kapasitas_total) * 100 : 0;
                
                $badgeClass = 'bg-success';
                if ($persentase >= 100) {
                    $badgeClass = 'bg-danger';
                } elseif ($persentase >= 80) {
                    $badgeClass = 'bg-warning text-dark';
                }
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="dash-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-2.5 py-1 rounded-pill small">
                                    {{ $area->kode_area }}
                                </span>
                                <h5 class="fw-bold text-dark mt-2 mb-0">{{ $area->nama_area }}</h5>
                            </div>
                            <span class="badge {{ $badgeClass }} px-2.5 py-1 rounded-pill small">
                                {{ $terisi }}/{{ $area->kapasitas_total }} Slot
                            </span>
                        </div>

                        <!-- Progress Bar Status Lahan -->
                        <div class="progress rounded-pill mb-3" style="height: 8px;">
                            <div class="progress-bar {{ $badgeClass }}" role="progressbar" style="width: {{ $persentase }}%;"></div>
                        </div>

                        <div class="d-flex justify-content-between text-muted small mb-4">
                            <span>Terisi: <strong>{{ $terisi }}</strong></span>
                            <span>Sisa Kosong: <strong>{{ $sisa }}</strong></span>
                        </div>
                    </div>

                    <!-- TOMBOL MONITORING VIRTUAL SLOT & EDIT KUOTA -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <a href="{{ route('admin.area.slots', $area->id) }}" class="btn btn-outline-primary btn-sm rounded-3 flex-fill fw-semibold">
                            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Lihat Slot Grid
                        </a>
                        <!-- TRIGGER MODAL -->
                        <button type="button" class="btn btn-light btn-sm rounded-3 border" data-bs-toggle="modal" data-bs-target="#modalKuota{{ $area->id }}">
                            <i class="bi bi-gear-fill text-secondary"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- TABLE RIWAYAT PARKIR SELESAI -->
    <div class="dash-card">
        <h5 class="fw-bold mb-3">Riwayat Parkir Selesai Terakhir</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Plat Nomor</th>
                        <th>Kendaraan</th>
                        <th>Area</th>
                        <th>Virtual Slot</th>
                        <th>Waktu Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat_parkir as $item)
                        <tr>
                            <td><strong class="text-uppercase">{{ $item->plat_nomor }}</strong></td>
                            <td><span class="badge bg-light text-dark border">{{ $item->jenis_kendaraan }}</span></td>
                            <td>{{ $item->area->nama_area ?? '-' }}</td>
                            <td><span class="badge bg-secondary">{{ $item->kode_slot_formatted }}</span></td>
                            <td class="small text-muted">{{ \Carbon\Carbon::parse($item->waktu_keluar)->format('d M Y, H:i') }} WIB</td>
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

<!-- ==================== LOOP KHUSUS UNTUK MODAL (DILETAKKAN DI LUAR CONTAINER AOS) ==================== -->
@foreach($semua_area as $area)
    <div class="modal fade" id="modalKuota{{ $area->id }}" tabindex="-1" aria-labelledby="modalKuotaLabel{{ $area->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalKuotaLabel{{ $area->id }}">Konfigurasi Kuota - {{ $area->nama_area }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.update_kuota') }}" method="POST">
                    @csrf
                    <input type="hidden" name="area_id" value="{{ $area->id }}">
                    <div class="modal-body py-4">
                        <label class="form-label small fw-semibold text-muted">Kapasitas Baru Slot Parkir</label>
                        <input type="number" name="kapasitas_baru" class="form-control form-control-lg rounded-3 fw-bold" value="{{ $area->kapasitas_total }}" min="1" required>
                        <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">
                            *Mengubah angka ini akan memperluas/mempersempit nomor slot dinamis (contoh: {{ $area->kode_area }}001 s/d {{ $area->kode_area }}XXX) secara otomatis.
                        </small>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 fw-semibold">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection