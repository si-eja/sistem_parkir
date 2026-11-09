@extends('layout.master')

@section('title', 'Dashboard Pengendara - E-Parkir UNSIL')

@section('custom-css')
<style>
    body {
        background-color: #f8fafc;
        color: #0f172a;
    }
    
    .bento-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .bento-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 12px 24px -10px rgba(15, 23, 42, 0.05) !important;
    }

    .badge-soft-primary {
        background-color: rgba(37, 99, 235, 0.1);
        color: #2563eb;
    }
</style>
@endsection

@section('content')
<!-- NOTIFIKASI SYSTEM -->
@if(session('success'))
    <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-4 p-3 mb-4 shadow-sm d-flex align-items-center justify-content-between" role="alert" data-aos="fade-down">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>
                <h6 class="fw-bold mb-0">Berhasil!</h6>
                <span class="small">{{ session('success') }}</span>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-4 p-3 mb-4 shadow-sm d-flex align-items-center justify-content-between" role="alert" data-aos="fade-down">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>
                <h6 class="fw-bold mb-0">Gagal!</h6>
                <span class="small">{{ session('error') }}</span>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4 mb-4" data-aos="zoom-in">
    
    <!-- ==================== 1. KARTU QR CODE PERMANEN (PROFIL PENGENDARA) ==================== -->
    <div class="col-lg-5 col-md-12">
        <div class="bento-card p-4 text-center h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge badge-soft-primary px-3 py-2 rounded-pill small fw-semibold">
                        <i class="bi bi-qr-code me-1"></i> QR Identitas Parkir
                    </span>
                    <!-- FITUR 1: EDIT PLAT NOMOR CEPAT -->
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1" data-bs-toggle="modal" data-bs-target="#modalEditPlat">
                        <i class="bi bi-pencil-square me-1"></i> Ubah Plat
                    </button>
                </div>

                <h5 class="fw-bold text-dark mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>

                <!-- CONTAINER BARCODE (AUTO FIT & FLUID) -->
                <div class="p-3 bg-light rounded-3 border w-100 overflow-hidden mb-3">
                    <svg id="barcodePermanent" style="max-width: 100%; height: auto; display: block; margin: 0 auto;"></svg>
                </div>

                <!-- DETAIL INFORMASI KENDARAAN USER -->
                <div class="bg-light rounded-3 p-3 text-start border small mb-2">
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span class="text-muted">Plat Nomor Kendaraan:</span>
                        <strong class="text-primary fs-6">{{ Auth::user()->plat_nomor ?? 'BELUM SET' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Jenis Kendaraan:</span>
                        <strong class="text-dark">{{ Auth::user()->jenis_kendaraan ?? 'Motor' }}</strong>
                    </div>
                </div>
            </div>

            <small class="text-muted mt-2 d-block" style="font-size: 0.72rem;">
                *Tunjukkan QR Code ini kepada Petugas Jukir di pos pintu masuk area parkir.
            </small>
        </div>
    </div>

    <!-- ==================== 2. KONTEN KANAN: STATUS / CHECK-IN MANDIRI ==================== -->
    <div class="col-lg-7 col-md-12">
        <div class="bento-card p-4 h-100 d-flex flex-column justify-content-between">
            
            @if(isset($parkir_aktif) && $parkir_aktif)
                <!-- STATE A: KENDARAAN SEDANG AKTIF PARKIR -->
                @php
                    $waktuMasuk = \Carbon\Carbon::parse($parkir_aktif->created_at)->setTimezone('Asia/Jakarta');
                @endphp
                
                <div class="bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-4 p-4 text-center my-auto">
                    <span class="badge bg-success px-3 py-2 rounded-pill small mb-3 shadow-sm">
                        <i class="bi bi-clock-history me-1"></i> STATUS: SEDANG PARKIR
                    </span>
                    
                    <small class="text-muted d-block mt-1">Nomor Slot Parkir Anda:</small>
                    <h1 class="display-3 fw-bold text-primary mb-1">{{ $parkir_aktif->kode_slot_formatted }}</h1>
                    
                    <p class="text-dark fw-bold mb-3 fs-5">📍 {{ $parkir_aktif->area->nama_area }}</p>
                    
                    <!-- FITUR 2: LIVE STOPWATCH DAKAT REAL-TIME -->
                    <div class="row g-2 justify-content-center mb-4">
                        <div class="col-6 col-sm-4">
                            <div class="bg-white rounded-3 p-2 border">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Waktu Masuk</small>
                                <strong class="text-dark">{{ $waktuMasuk->format('H:i') }} WIB</strong>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <div class="bg-white rounded-3 p-2 border">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Durasi Parkir</small>
                                <strong class="text-primary" id="stopwatch-pengendara">Memuat...</strong>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('parkir.checkout', $parkir_aktif->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-lg w-100 py-3 rounded-3 fw-semibold shadow-sm">
                            <i class="bi bi-box-arrow-right me-1"></i> Selesai Parkir (Check-Out Mandiri)
                        </button>
                    </form>
                </div>

                <script>
                    (function() {
                        const masukTimestamp = new Date("{{ $waktuMasuk->toIso8601String() }}").getTime();

                        function updateStopwatch() {
                            const now = new Date().getTime();
                            const selisihDetik = Math.floor((now - masukTimestamp) / 1000);
                            if (selisihDetik < 0) return;

                            const jam = Math.floor(selisihDetik / 3600);
                            const menit = Math.floor((selisihDetik % 3600) / 60);
                            const detik = selisihDetik % 60;

                            let txt = '';
                            if (jam > 0) txt += jam + 'j ';
                            if (menit > 0 || jam > 0) txt += menit + 'm ';
                            txt += detik + 'd';

                            const el = document.getElementById('stopwatch-pengendara');
                            if (el) el.innerText = txt;
                        }
                        updateStopwatch();
                        setInterval(updateStopwatch, 1000);
                    })();
                </script>

            @else
                <!-- STATE B: FORM CHECK-IN MANDIRI DETIL -->
                <div class="my-auto">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-p-circle-fill text-primary fs-3"></i>
                        <div>
                            <h5 class="fw-bold mb-0">Check-In Parkir Mandiri</h5>
                            <p class="text-muted small mb-0">Pilih lokasi area parkir tujuan Anda di kampus</p>
                        </div>
                    </div>

                    <form action="{{ route('pengendara.checkin') }}" method="POST">
                        @csrf
                        
                        <!-- Pilihan Area Parkir -->
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">Pilih Lokasi Area Parkir Kampus</label>
                            <select name="area_id" class="form-select form-select-lg fw-bold border-secondary border-opacity-25 rounded-3" onchange="location = '?area_id=' + this.value;">
                                @foreach($semua_area as $item)
                                    @php
                                        $terisi = $item->parkir_aktif_count ?? $item->parkirAktif()->count();
                                        $sisa = max(0, $item->kapasitas_total - $terisi);
                                    @endphp
                                    <option value="{{ $item->id }}" {{ $area && $area->id == $item->id ? 'selected' : '' }}>
                                        📍 {{ $item->nama_area }} (Sisa Slot: {{ $sisa }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- FITUR 3: PILIH NOMOR SLOT PARKING MANDIRI -->
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">Pilih Slot Kosong (Opsional)</label>
                            <select name="nomor_slot" class="form-select border-secondary border-opacity-25 rounded-3">
                                <option value="">-- Otomatis Pilih Slot Kosong Pertama --</option>
                                @if(isset($slot_kosong_list))
                                    @foreach($slot_kosong_list as $slotNum)
                                        <option value="{{ $slotNum }}">Slot {{ $area->kode_area }}{{ str_pad($slotNum, 3, '0', STR_PAD_LEFT) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="alert alert-info border-0 bg-info bg-opacity-10 text-dark rounded-3 p-3 mb-4 small">
                            <i class="bi bi-info-circle me-1 text-info"></i> Check-in akan otomatis menggunakan Plat Nomor: <strong>{{ Auth::user()->plat_nomor }}</strong>.
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-3 fw-semibold shadow-sm" {{ $sisa_kuota <= 0 ? 'disabled' : '' }}>
                            <i class="bi bi-box-arrow-in-right me-1"></i> {{ $sisa_kuota <= 0 ? 'Lahan Parkir Penuh' : 'Mulai Parkir (Check-In)' }}
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>

</div>

<!-- FITUR 4: TABEL RIWAYAT PARKIR TERAKHIR PENGENDARA -->
<div class="row" data-aos="fade-up" data-aos-delay="100">
    <div class="col-12">
        <div class="bento-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-history text-primary"></i> Riwayat Parkir Terakhir
                </h5>
                <span class="badge bg-light text-muted border">5 Aktivitas Terakhir</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>TANGGAL & WAKTU</th>
                            <th>LOKASI AREA</th>
                            <th>SLOT</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($riwayat_terakhir) && count($riwayat_terakhir) > 0)
                            @foreach($riwayat_terakhir as $h)
                                <tr>
                                    <td class="small">
                                        <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($h->created_at)->format('d M Y, H:i') }} WIB</div>
                                    </td>
                                    <td class="small fw-semibold text-primary">
                                        📍 {{ $h->area->nama_area }}
                                    </td>
                                    <td class="small">
                                        <span class="badge bg-light text-dark border">{{ $h->kode_slot_formatted }}</span>
                                    </td>
                                    <td>
                                        @if($h->status === 'AKTIF')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill small">AKTIF</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1 rounded-pill small">SELESAI</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4 small">
                                    Belum ada riwayat transaksi parkir.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT PLAT NOMOR -->
<div class="modal fade" id="modalEditPlat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Perbarui Plat Nomor Kendaraan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pengendara.pengendara.update_profile') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small text-muted">Plat Nomor Kendaraan</label>
                        <input type="text" name="plat_nomor" class="form-control text-uppercase fw-bold" value="{{ Auth::user()->plat_nomor }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="form-select">
                            <option value="MOTOR" {{ strtoupper(Auth::user()->jenis_kendaraan) == 'MOTOR' ? 'selected' : '' }}>Motor</option>
                            <option value="MOBIL" {{ strtoupper(Auth::user()->jenis_kendaraan) == 'MOBIL' ? 'selected' : '' }}>Mobil</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3">Simpan Pembaruan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT GENERATE QR / BARCODE PERMANEN -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        JsBarcode("#barcodePermanent", "{{ Auth::user()->qr_code_token }}", {
            format: "CODE128",
            lineColor: "#0f172a",
            width: 1.8,
            height: 60,
            fontSize: 14,
            displayValue: true
        });
    });
</script>
@endsection