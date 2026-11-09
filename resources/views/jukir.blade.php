@extends('layout.dashboard-master')

@section('title', 'Dashboard POS Jukir - E-Parkir')

@section('custom-css')
<style>
    .dash-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 1.5rem;
    }
    #reader {
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        border: 2px dashed #cbd5e1;
    }
</style>
@endsection

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

    <!-- SUMMARY HEADER SHIFT JUKIR -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="dash-card bg-primary text-white border-0">
                <small class="opacity-75 d-block">Lokasi Tugas Aktif</small>
                <h5 class="fw-bold mb-0 text-truncate">📍 {{ $area->nama_area }}</h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dash-card bg-white">
                <small class="text-muted d-block">Kapasitas Slot Sisa</small>
                <h5 class="fw-bold mb-0 text-success"><i class="bi bi-p-square me-1"></i> {{ $sisa_kuota }} / {{ $area->kapasitas_total }} Slot Kosong</h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dash-card bg-white">
                <small class="text-muted d-block">Kendaraan Aktif Parkir</small>
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-car-front me-1"></i> {{ $kendaraan_aktif->count() }} Kendaraan</h5>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <!-- KOLOM KIRI: POS CHECK-IN PARKIR & CAMERA SCANNER -->
        <div class="col-lg-5 col-12">
            <div class="dash-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <h5 class="fw-bold m-0 text-dark">POS Check-In Jukir</h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1.5 small fw-semibold">
                            {{ $area->kode_area }}
                        </span>
                    </div>

                    <!-- SELECT AREA TUGAS -->
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Ganti Area Tugas:</label>
                        <select class="form-select fw-bold border-secondary border-opacity-25 rounded-3" onchange="location = '?area_id=' + this.value;">
                            @foreach($semua_area as $item)
                                <option value="{{ $item->id }}" {{ $area->id == $item->id ? 'selected' : '' }}>
                                    📍 {{ $item->nama_area }} (Sisa Slot: {{ max(0, $item->kapasitas_total - ($item->parkir_aktif_count ?? 0)) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- TOMBOL TOGGLE KAMERA SCANNER -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-3 w-100 fw-semibold d-flex align-items-center justify-content-center gap-2" id="btnToggleCamera">
                            <i class="bi bi-camera-fill"></i>
                            <span id="btnCameraText">Buka Kamera Scan QR</span>
                        </button>
                        <div id="cameraContainer" class="mt-2 d-none">
                            <div id="reader"></div>
                        </div>
                    </div>

                    <!-- FORM INPUT CHECK-IN / SCAN TOKEN -->
                    <form action="{{ route('jukir.checkin') }}" method="POST" id="formCheckinJukir">
                        @csrf
                        <input type="hidden" name="area_id" value="{{ $area->id }}">

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">Plat Nomor Kendaraan</label>
                            <input type="text" name="plat_nomor" id="inputPlatNomor" class="form-control form-control-lg text-uppercase fw-bold rounded-3" placeholder="Contoh: Z 1234 AB" autocomplete="off">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label text-muted small fw-semibold">Jenis Kendaraan</label>
                                <select name="jenis_kendaraan" class="form-select rounded-3 fw-semibold">
                                    <option value="MOTOR">Motor</option>
                                    <option value="MOBIL">Mobil</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label text-muted small fw-semibold">Pilih Slot (Opsional)</label>
                                <select name="nomor_slot" class="form-select rounded-3">
                                    <option value="">-- Otomatis --</option>
                                    @if(isset($slot_kosong_list))
                                        @foreach($slot_kosong_list as $slotNum)
                                            <option value="{{ $slotNum }}">Slot {{ $area->kode_area }}{{ str_pad($slotNum, 3, '0', STR_PAD_LEFT) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-semibold">Hasil Scan Token QR Pengendara</label>
                            <input type="text" name="qr_code_token" id="inputQrToken" class="form-control rounded-3" placeholder="Tempel / Scan Barcode QR">
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-3 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2" {{ $sisa_kuota <= 0 ? 'disabled' : '' }}>
                            <i class="bi bi-box-arrow-in-right fs-5"></i>
                            <span>{{ $sisa_kuota <= 0 ? 'Lahan Parkir Penuh' : 'Proses Check-In' }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: DAFTAR KENDARAAN AKTIF DI AREA TUGAS -->
        <div class="col-lg-7 col-12">
            <div class="dash-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                    <h5 class="fw-bold m-0 text-dark">Kendaraan Parkir Aktif ({{ $kendaraan_aktif->count() }})</h5>
                    
                    <!-- FITUR PENCARIAN PLAT NOMOR AKTIF -->
                    <div class="w-50">
                        <input type="text" id="searchTable" class="form-control form-control-sm rounded-3" placeholder="🔍 Cari Plat Nomor...">
                    </div>
                </div>

                <div class="table-responsive" style="max-height: 440px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" id="tableParkirAktif">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Slot</th>
                                <th>Plat Nomor</th>
                                <th>Jenis</th>
                                <th>Waktu Masuk</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kendaraan_aktif as $item)
                                <tr>
                                    <td><span class="badge bg-primary fs-6">{{ $item->kode_slot_formatted }}</span></td>
                                    <td><strong class="text-uppercase text-dark">{{ $item->plat_nomor }}</strong></td>
                                    <td><span class="badge bg-light text-dark border">{{ $item->jenis_kendaraan ?? 'MOTOR' }}</span></td>
                                    <td class="small text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</td>
                                    <td class="text-end">
                                        <form action="{{ route('parkir.checkout', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm rounded-3 fw-semibold px-3">
                                                <i class="bi bi-box-arrow-right me-1"></i> Check-Out
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada kendaraan parkir aktif di area ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- SCRIPT KAMERA SCANNER HTML5 & QUICK SEARCH FILTER -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. FILTER QUICK SEARCH TABLE
        const searchInput = document.getElementById('searchTable');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const value = this.value.toLowerCase();
                const rows = document.querySelectorAll('#tableParkirAktif tbody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(value) ? '' : 'none';
                });
            });
        }

        // 2. KAMERA SCANNER INTEGRATION
        let html5QrcodeScanner = null;
        const btnToggle = document.getElementById('btnToggleCamera');
        const cameraContainer = document.getElementById('cameraContainer');
        const inputQrToken = document.getElementById('inputQrToken');

        if (btnToggle) {
            btnToggle.addEventListener('click', function() {
                if (cameraContainer.classList.contains('d-none')) {
                    cameraContainer.classList.remove('d-none');
                    document.getElementById('btnCameraText').innerText = "Tutup Kamera";

                    html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
                    html5QrcodeScanner.render(onScanSuccess);
                } else {
                    cameraContainer.classList.add('d-none');
                    document.getElementById('btnCameraText').innerText = "Buka Kamera Scan QR";
                    if (html5QrcodeScanner) {
                        html5QrcodeScanner.clear();
                    }
                }
            });
        }

        function onScanSuccess(decodedText) {
            inputQrToken.value = decodedText;
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
            }
            cameraContainer.classList.add('d-none');
            document.getElementById('btnCameraText').innerText = "Buka Kamera Scan QR";
            
            // Auto Submit jika token QR berhasil di-scan
            document.getElementById('formCheckinJukir').submit();
        }
    });
</script>
@endsection