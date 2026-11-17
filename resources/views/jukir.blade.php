@extends('layout.dashboard-master')

@section('title', 'POS Universal Jukir - E-Parkir UNSIL')

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

    .universal-scan-box {
        background: linear-gradient(145deg, #f0fdf4 0%, #e6f4ea 100%);
        border: 2px solid #86efac;
        border-radius: 1rem;
        padding: 1.15rem;
    }

    .text-unsil { color: var(--unsil-green) !important; }
    .bg-unsil-soft { background-color: var(--unsil-green-light) !important; }

    .badge-unsil-yellow { background-color: var(--unsil-yellow); color: #062818; font-weight: 700; }
    .badge-unsil-green { background-color: var(--unsil-green); color: #ffffff; font-weight: 700; }

    .btn-unsil-primary {
        background-color: var(--unsil-green);
        border-color: var(--unsil-green);
        color: #ffffff;
        transition: all 0.2s ease;
    }
    .btn-unsil-primary:hover {
        background-color: var(--unsil-green-dark);
        color: var(--unsil-yellow);
    }

    .btn-unsil-yellow {
        background-color: var(--unsil-yellow);
        border-color: var(--unsil-yellow);
        color: #062818;
        font-weight: 700;
        transition: all 0.2s ease;
    }
    .btn-unsil-yellow:hover {
        background-color: var(--unsil-yellow-hover);
        color: #000000;
    }

    .btn-checkout-minimal {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: var(--unsil-red);
        transition: all 0.2s ease;
    }
    .btn-checkout-minimal:hover {
        background-color: var(--unsil-red);
        border-color: var(--unsil-red);
        color: #ffffff;
    }

    #reader {
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        border: 2px dashed var(--unsil-green);
        background-color: #ffffff;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0" data-aos="fade-up">

    <!-- HEADER RINGKAS STATUS POS -->
    <div class="dash-card card-unsil-hero mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="badge badge-unsil-yellow rounded-pill px-3 py-1 mb-2 small">
                    <i class="bi bi-qr-code-scan me-1"></i> POS Gerbang Universal
                </span>
                <h4 class="fw-bold text-white mb-1">📍 {{ $area->nama_area }}</h4>
                <small style="color: #bbf7d0;">
                    Kode Area: <strong class="text-white">{{ $area->kode_area }}</strong> &bull;
                    Sisa Kuota: <strong class="text-warning">{{ $sisa_kuota }}</strong> dari {{ $area->kapasitas_total }} Slot
                </small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-white bg-opacity-10 text-white border border-warning border-opacity-50 px-3 py-2 rounded-pill">
                    <i class="bi bi-car-front-fill text-warning me-1"></i> {{ $kendaraan_aktif->count() }} Kendaraan Aktif
                </span>
            </div>
        </div>
    </div>

    <!-- BARIS 1: 2 CARD SEJAJAR (CARD 1: SCANNER QR | CARD 2: INPUT MANUAL & PILIHAN POS) -->
    <div class="row g-4 mb-4">
        
        <!-- ==================== CARD 1: SCANNER QR UNIVERSAL ==================== -->
        <div class="col-lg-6 col-12">
            <div class="dash-card h-100 d-flex flex-column justify-content-between" style="border-top: 4px solid var(--unsil-green);">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <h5 class="fw-bold m-0 text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-upc-scan text-unsil"></i> Scanner QR Universal
                        </h5>
                        <span class="badge badge-unsil-green rounded-pill px-3 py-1 small">
                            Auto Masuk / Keluar
                        </span>
                    </div>

                    <div class="universal-scan-box">
                        <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.5;">
                            Scan QR pengendara untuk <strong>Check-In</strong> otomatis ke area aktif, atau scan ulang saat keluar untuk <strong>Check-Out</strong> dari area manapun.
                        </p>

                        <!-- Tombol Kamera -->
                        <button type="button" class="btn btn-unsil-yellow w-100 py-2.5 rounded-3 d-flex align-items-center justify-content-center gap-2 shadow-sm mb-3" id="btnToggleCamera">
                            <i class="bi bi-camera-fill fs-5"></i>
                            <span id="btnCameraText">Buka Kamera Scan Universal</span>
                        </button>

                        <div id="cameraContainer" class="my-3 d-none">
                            <div id="reader"></div>
                        </div>

                        <!-- Form Scan / Barcode Gun / Paste Token Universal -->
                        <form action="{{ route('jukir.checkin') }}" method="POST" id="formUniversalQr">
                            @csrf
                            <input type="hidden" name="area_id" value="{{ $area->id }}">
                            <label class="form-label text-unsil small fw-semibold mb-1">Input Barcode Gun / Token QR</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-unsil">
                                    <i class="bi bi-qr-code"></i>
                                </span>
                                <input type="text" name="qr_code_token" id="inputQrToken" 
                                       class="form-control border-start-0" 
                                       placeholder="Scan barcode gun / tempel token..." 
                                       autocomplete="off" autofocus required>
                                <button type="submit" class="btn btn-unsil-primary fw-semibold px-4">
                                    Proses
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between text-muted small">
                    <span><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Mendukung Webcam & Barcode Gun USB</span>
                    <span class="fw-semibold text-unsil">{{ $area->kode_area }}</span>
                </div>
            </div>
        </div>

        <!-- ==================== CARD 2: INPUT DATA MANUAL & PILIHAN POS ==================== -->
        <div class="col-lg-6 col-12">
            <div class="dash-card h-100 d-flex flex-column justify-content-between" style="border-top: 4px solid var(--unsil-yellow);">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <h5 class="fw-bold m-0 text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-keyboard text-unsil"></i> Input Data Manual
                        </h5>
                        <span class="badge badge-unsil-yellow rounded-pill px-3 py-1 small">
                            Khusus Masuk (Non-QR)
                        </span>
                    </div>

                    <!-- PILIHAN LOKASI POS PENJAGAAN AKTIF (DIPINDAHKAN KE SINI) -->
                    <div class="p-3 rounded-3 mb-3" style="background-color: var(--unsil-green-light); border: 1px solid #b7e4c7;">
                        <label class="form-label text-unsil small fw-bold mb-1">
                            <i class="bi bi-geo-alt-fill me-1"></i> Lokasi Pos Penjagaan Aktif:
                        </label>
                        <select class="form-select fw-bold border-success border-opacity-25 rounded-3 shadow-sm" onchange="location = '?area_id=' + this.value;">
                            @foreach($semua_area as $item)
                                <option value="{{ $item->id }}" {{ $area->id == $item->id ? 'selected' : '' }}>
                                    📍 {{ $item->nama_area }} (Sisa: {{ max(0, $item->kapasitas_total - ($item->parkir_aktif_count ?? 0)) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- FORM INPUT DATA MANUAL -->
                    <form action="{{ route('jukir.checkin') }}" method="POST" id="formManualCheckin">
                        @csrf
                        <input type="hidden" name="area_id" value="{{ $area->id }}">

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">Plat Nomor Kendaraan</label>
                            <input type="text" 
                                name="plat_nomor" 
                                class="form-control text-uppercase fw-bold rounded-3" 
                                placeholder="Contoh: Z1234AB"
                                maxlength="11"
                                pattern="[A-Za-z]{1,2}\s?[1-9][0-9]{0,3}\s?[A-Za-z]{1,3}"
                                title="Format plat nomor Indonesia yang valid (1-2 huruf depan, 1-4 angka, 1-3 huruf belakang. Contoh: Z1234AB)"
                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                value="{{ old('plat_nomor', Auth::user()->plat_nomor ?? '') }}" 
                                required>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label text-muted small fw-semibold">Jenis Kendaraan</label>
                                <select name="jenis_kendaraan" class="form-select rounded-3 fw-semibold">
                                    <option value="MOTOR">🛵 Motor</option>
                                    <option value="MOBIL">🚗 Mobil</option>
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

                        <button type="submit" class="btn {{ $sisa_kuota <= 0 ? 'btn-secondary' : 'btn-unsil-primary' }} w-100 py-2.5 rounded-3 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2" {{ $sisa_kuota <= 0 ? 'disabled' : '' }}>
                            <i class="bi bi-box-arrow-in-right text-warning"></i>
                            <span>{{ $sisa_kuota <= 0 ? 'Lahan Parkir Penuh' : 'Check-In Manual' }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- BARIS 2 (DI BAWAH): TABEL KENDARAAN PARKIR AKTIF & AKSI CHECK-OUT -->
    <div class="row">
        <div class="col-12">
            <div class="dash-card" style="border-top: 4px solid var(--unsil-green);">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-2 border-bottom">
                    <div>
                        <h5 class="fw-bold m-0 text-dark">
                            <i class="bi bi-list-check text-unsil me-1"></i> Kendaraan Parkir Aktif ({{ $kendaraan_aktif->count() }})
                        </h5>
                        <small class="text-muted">Daftar kendaraan yang sedang berada di area <strong>{{ $area->nama_area }}</strong></small>
                    </div>
                    
                    <div style="min-width: 240px;">
                        <input type="text" id="searchTable" class="form-control form-control-sm rounded-3" placeholder="🔍 Cari Plat Nomor / Slot...">
                    </div>
                </div>

                <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" id="tableParkirAktif">
                        <thead class="sticky-top" style="background-color: var(--unsil-green-light); color: var(--unsil-green-dark);">
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
                                    <td><span class="badge badge-unsil-green fs-6 px-2.5 py-1.5">{{ $item->kode_slot_formatted }}</span></td>
                                    <td>
                                        <strong class="text-uppercase text-dark">{{ $item->plat_nomor }}</strong>
                                        @if($item->user_id)
                                            <span class="badge bg-unsil-soft text-unsil ms-1" style="font-size: 0.65rem;">QR</span>
                                        @else
                                            <span class="badge bg-light text-muted border ms-1" style="font-size: 0.65rem;">Manual</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-unsil-soft text-unsil border border-success border-opacity-25">
                                            {{ $item->jenis_kendaraan ?? 'MOTOR' }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</td>
                                    <td class="text-end">
                                        <form action="{{ route('parkir.checkout', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-checkout-minimal btn-sm rounded-3 fw-semibold px-3">
                                                <i class="bi bi-box-arrow-right me-1"></i> Check-Out
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-car-front fs-2 d-block mb-2 opacity-50"></i>
                                        Belum ada kendaraan parkir aktif di area ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('searchTable');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const value = this.value.toLowerCase();
                document.querySelectorAll('#tableParkirAktif tbody tr').forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
                });
            });
        }

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
                    document.getElementById('btnCameraText').innerText = "Buka Kamera Scan Universal";
                    if (html5QrcodeScanner) html5QrcodeScanner.clear();
                }
            });
        }

        function onScanSuccess(decodedText) {
            inputQrToken.value = decodedText;
            if (html5QrcodeScanner) html5QrcodeScanner.clear();
            cameraContainer.classList.add('d-none');
            document.getElementById('btnCameraText').innerText = "Buka Kamera Scan Universal";
            // Langsung submit ke form universal QR
            document.getElementById('formUniversalQr').submit();
        }
    });
</script>
@endsection