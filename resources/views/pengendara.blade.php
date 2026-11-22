@extends('layout.master')

@section('title', 'Dashboard Pengendara - E-Parkir UNSIL')

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

    body {
        background-color: #f4f7f5;
        color: #0f172a;
    }
    
    .bento-card {
        background: #ffffff;
        border: 1px solid #d1e2d8;
        border-radius: 1.25rem;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 12px rgba(0, 90, 50, 0.04);
    }
    
    .bento-card:hover {
        border-color: var(--unsil-green);
        box-shadow: 0 12px 24px -10px rgba(0, 90, 50, 0.12) !important;
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

    /* Tombol Utama & Aksen UNSIL */
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
        transform: translateY(-1px);
    }

    /* Banner Status Sedang Parkir (Jika sedang aktif di-scan Jukir) */
    .active-parking-banner {
        background: linear-gradient(135deg, var(--unsil-green-dark) 0%, var(--unsil-green) 100%);
        color: #ffffff;
        border-radius: 1rem;
        border-bottom: 3px solid var(--unsil-yellow);
    }

    /* Kartu Info Area Parkir di Bagian Bawah */
    .area-info-item {
        background-color: #f8faf9;
        border: 1px solid #d1e2d8;
        border-radius: 1rem;
        padding: 1.15rem;
        transition: all 0.2s ease;
    }
    .area-info-item:hover {
        background-color: #ffffff;
        border-color: var(--unsil-green);
        transform: translateY(-2px);
        box-shadow: 0 8px 18px -6px rgba(0, 90, 50, 0.1);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--unsil-green);
        box-shadow: 0 0 0 0.2rem rgba(0, 90, 50, 0.15);
    }
</style>
@endsection

@section('content')
@php
    $profil = $pengendara ?? Auth::user()->pengendara;
    $qrToken = $profil?->qr_code_token ?? 'PARKIR-' . Auth::id();
    $platNomor = $profil?->plat_nomor ?? 'BELUM SET';
    $jenisKendaraan = $profil?->jenis_kendaraan ?? 'MOTOR';
    $kategori = strtoupper($profil?->kategori ?? 'MAHASISWA');
    $nomorIdentitas = $profil?->nomor_identitas ?? null;
@endphp

<div class="container py-4">

    <!-- ==================== BARIS 1: KARTU QR (KIRI) & RIWAYAT PARKIR (KANAN) ==================== -->
    <div class="row g-4 mb-4" data-aos="zoom-in">
        
        <!-- 1. KARTU QR CODE PERMANEN (IDENTITAS PENGENDARA) -->
        <div class="col-lg-5 col-md-12">
            <div class="bento-card p-4 text-center h-100 d-flex flex-column justify-content-between" style="border-top: 4px solid var(--unsil-green);">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge badge-unsil-yellow px-3 py-2 rounded-pill small">
                            <i class="bi bi-qr-code me-1"></i> QR Identitas Parkir
                        </span>
                        <button class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalEditPlat">
                            <i class="bi bi-pencil-square me-1"></i> Ubah Plat
                        </button>
                    </div>

                    <h5 class="fw-bold text-dark mb-1">{{ Auth::user()->name }}</h5>
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                        <span class="badge {{ $kategori === 'DOSEN' ? 'bg-dark text-warning' : 'badge-unsil-green' }} px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">
                            <i class="bi {{ $kategori === 'DOSEN' ? 'bi-person-workspace' : 'bi-mortarboard-fill' }} me-1"></i> {{ $kategori }}
                        </span>
                        @if($nomorIdentitas)
                            <small class="text-muted font-monospace">{{ $nomorIdentitas }}</small>
                        @else
                            <small class="text-muted">{{ Auth::user()->email }}</small>
                        @endif
                    </div>

                    <!-- KOTAK QR CODE -->
                    <div class="rounded-4 p-4 d-flex flex-column align-items-center justify-content-center border shadow-sm my-3" 
                         style="background-color: #f8faf9; border-color: #d1e2d8 !important;">
                        <img id="qrImageDisplay"
                             src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ $qrToken }}" 
                             alt="QR Identitas {{ Auth::user()->name }}" 
                             class="img-fluid rounded-3 border p-2 bg-white shadow-sm"
                             style="width: 185px; height: 185px; object-fit: contain; border-color: var(--unsil-green) !important;">
                        
                        <span class="badge font-monospace mt-3 px-3 py-2 rounded-3" 
                              style="background-color: var(--unsil-green-dark); color: var(--unsil-yellow); letter-spacing: 0.05em;">
                            {{ $qrToken }}
                        </span>

                        <!-- TOMBOL DOWNLOAD & CETAK KARTU QR -->
                        <div class="d-flex gap-2 w-100 mt-3 pt-3 border-top border-success border-opacity-10">
                            <button type="button" onclick="downloadKartuQr()" class="btn btn-unsil-primary btn-sm rounded-3 fw-semibold flex-fill py-2 d-flex align-items-center justify-content-center gap-1">
                                <i class="bi bi-download"></i> Download PNG
                            </button>
                            <button type="button" onclick="cetakKartuQr()" class="btn btn-sm rounded-3 fw-bold flex-fill py-2 d-flex align-items-center justify-content-center gap-1"
                                    style="background-color: var(--unsil-yellow); color: #062818;">
                                <i class="bi bi-printer-fill"></i> Cetak Kartu
                            </button>
                        </div>
                    </div>

                    <!-- DETAIL INFORMASI KENDARAAN USER -->
                    <div class="bg-unsil-soft bg-opacity-50 rounded-3 p-3 text-start border border-success border-opacity-10 small mb-2">
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom border-success border-opacity-10">
                            <span class="text-muted">Plat Nomor Kendaraan:</span>
                            <strong class="text-unsil fs-6 text-uppercase">{{ $platNomor }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom border-success border-opacity-10">
                            <span class="text-muted">Jenis Kendaraan:</span>
                            <strong class="text-dark text-uppercase">{{ $jenisKendaraan }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Hak Akses Area:</span>
                            <strong class="text-dark">{{ $kategori === 'DOSEN' ? 'Area Umum & Khusus Dosen' : 'Area Umum Mahasiswa' }}</strong>
                        </div>
                    </div>
                </div>

                <small class="text-muted mt-2 d-block" style="font-size: 0.74rem;">
                    *Tunjukkan atau cetak QR Code ini untuk dipindai Petugas Jukir saat <strong>Masuk</strong> & <strong>Keluar</strong> area parkir UNSIL.
                </small>
            </div>
        </div>

        <!-- 2. RIWAYAT PARKIR SELAMA DI UNSIL -->
        <div class="col-lg-7 col-md-12">
            <div class="bento-card p-4 h-100 d-flex flex-column justify-content-between" style="border-top: 4px solid var(--unsil-yellow);">
                <div>
                    <!-- Header Riwayat -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-2 border-bottom">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-clock-history text-unsil"></i> Riwayat Parkir di UNSIL
                            </h5>
                            <small class="text-muted">Catatan keluar-masuk kendaraan Anda di area kampus</small>
                        </div>
                        <span class="badge badge-unsil-yellow px-3 py-1.5 rounded-pill small">
                            Aktivitas Terbaru
                        </span>
                    </div>

                    <!-- BANNER STATUS AKTIF (JIKA KENDARAAN SEDANG TERPARKIR) -->
                    @if(isset($parkir_aktif) && $parkir_aktif)
                        @php
                            $waktuMasuk = \Carbon\Carbon::parse($parkir_aktif->created_at)->setTimezone('Asia/Jakarta');
                        @endphp
                        <div class="active-parking-banner p-3 mb-3 shadow-sm">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <div>
                                    <span class="badge badge-unsil-yellow rounded-pill px-2.5 py-1 mb-1" style="font-size: 0.7rem;">
                                        <i class="bi bi-p-circle-fill me-1"></i> SEDANG TERPARKIR
                                    </span>
                                    <div class="fw-bold text-white fs-6">
                                        📍 {{ $parkir_aktif->area->nama_area }}
                                    </div>
                                    <small style="color: #bbf7d0;">
                                        Masuk pukul {{ $waktuMasuk->format('H:i') }} WIB &bull; Durasi: <strong class="text-warning" id="stopwatch-pengendara">Memuat...</strong>
                                    </small>
                                </div>
                                <div class="text-end">
                                    <small class="d-block" style="color: #bbf7d0; font-size: 0.7rem;">Slot Parkir</small>
                                    <span class="badge fs-5 px-3 py-2 rounded-3" style="background-color: var(--unsil-yellow); color: #062818;">
                                        {{ $parkir_aktif->kode_slot_formatted }}
                                    </span>
                                </div>
                            </div>
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
                    @endif

                    <!-- TABEL RIWAYAT PARKIR -->
                    <div class="table-responsive" style="max-height: 360px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="sticky-top" style="background-color: var(--unsil-green-light); color: var(--unsil-green-dark);">
                                <tr class="small">
                                    <th>WAKTU MASUK</th>
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
                                                <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($h->created_at)->format('d M Y') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($h->created_at)->format('H:i') }} WIB</small>
                                            </td>
                                            <td class="small fw-semibold text-unsil">
                                                📍 {{ $h->area->nama_area ?? '-' }}
                                            </td>
                                            <td class="small">
                                                <span class="badge badge-unsil-green px-2.5 py-1">{{ $h->kode_slot_formatted }}</span>
                                            </td>
                                            <td>
                                                @if($h->status === 'AKTIF')
                                                    <span class="badge badge-unsil-yellow px-2.5 py-1 rounded-pill small">AKTIF</span>
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1 rounded-pill small">SELESAI</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5 small">
                                            <i class="bi bi-clock-history fs-2 d-block mb-2 opacity-50"></i>
                                            Belum ada riwayat parkir Anda selama di UNSIL.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3 pt-2 border-top small text-muted d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-shield-check text-unsil me-1"></i> Sistem QR Gate Terintegrasi</span>
                    <span>Universitas Siliwangi</span>
                </div>
            </div>
        </div>

    </div>

    <!-- ==================== BARIS 2 (DI BAWAH): INFO TEMPAT & KETERSEDIAAN SLOT PARKIR UNSIL ==================== -->
    <div class="row" data-aos="fade-up" data-aos-delay="100">
        <div class="col-12">
            <div class="bento-card p-4" style="border-top: 4px solid var(--unsil-green);">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 pb-2 border-bottom">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-geo-alt-fill text-unsil"></i> Informasi Tempat & Ketersediaan Slot Parkir UNSIL
                        </h5>
                        <small class="text-muted">Pantau sisa kuota lahan parkir di setiap area fakultas secara langsung sebelum menuju gerbang</small>
                    </div>
                    <span class="badge badge-unsil-green px-3 py-2 rounded-pill small">
                        <i class="bi bi-broadcast me-1"></i> Live Kapasitas
                    </span>
                </div>

                <div class="row g-3">
                    @forelse($semua_area as $item)
                        @php
                            $terisi = $item->parkir_aktif_count ?? $item->parkirAktif()->count();
                            $sisa = max(0, $item->kapasitas_total - $terisi);
                            $persentase = $item->kapasitas_total > 0 ? round(($terisi / $item->kapasitas_total) * 100) : 0;

                            if ($sisa <= 0) {
                                $statusText = 'PENUH';
                                $statusBadge = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25';
                                $barClass = 'bg-danger';
                            } elseif ($persentase >= 80) {
                                $statusText = 'HAMPIR PENUH';
                                $statusBadge = 'badge-unsil-yellow';
                                $barClass = 'bg-warning';
                            } else {
                                $statusText = 'TERSEDIA';
                                $statusBadge = 'badge-unsil-green';
                                $barClass = 'bg-unsil';
                            }
                        @endphp

                        <div class="col-md-6 col-lg-4">
                            <div class="area-info-item h-100 d-flex flex-column justify-content-between"
                                 style="{{ !empty($item->khusus_dosen) && $item->khusus_dosen ? 'border-color: #eab308; background-color: #fefce8;' : '' }}">
                                <div>
                                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                        <div class="d-flex flex-wrap align-items-center gap-1">
                                            <span class="badge px-2.5 py-1 rounded-3 small fw-bold" 
                                                  style="background-color: var(--unsil-green); color: var(--unsil-yellow);">
                                                {{ $item->kode_area }}
                                            </span>
                                            @if(!empty($item->khusus_dosen) && $item->khusus_dosen)
                                                <span class="badge bg-dark text-warning px-2 py-1 rounded-3" style="font-size: 0.68rem;">
                                                    <i class="bi bi-star-fill me-1"></i> KHUSUS DOSEN
                                                </span>
                                            @endif
                                        </div>
                                        <span class="badge {{ $statusBadge }} px-2.5 py-1 rounded-pill" style="font-size: 0.7rem;">
                                            {{ $statusText }}
                                        </span>
                                    </div>

                                    <h6 class="fw-bold text-dark mb-3">📍 {{ $item->nama_area }}</h6>
                                </div>

                                <div>
                                    <div class="d-flex justify-content-between align-items-end mb-1">
                                        <small class="text-muted">Sisa Slot Kosong:</small>
                                        <div>
                                            <strong class="fs-5 {{ $sisa <= 0 ? 'text-danger' : 'text-unsil' }}">{{ $sisa }}</strong>
                                            <small class="text-muted">/ {{ $item->kapasitas_total }} Slot</small>
                                        </div>
                                    </div>

                                    <div class="progress rounded-pill" style="height: 7px; background-color: #d1e2d8;">
                                        <div class="progress-bar {{ $barClass }} rounded-pill" style="width: {{ $persentase }}%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-4 small">
                            Belum ada data lokasi area parkir yang tersedia.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

<!-- MODAL EDIT PLAT NOMOR -->
<div class="modal fade" id="modalEditPlat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header text-white border-0" style="background-color: var(--unsil-green); border-bottom: 3px solid var(--unsil-yellow) !important;">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square text-warning me-1"></i> Perbarui Plat Nomor Kendaraan
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pengendara.update_profile') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Plat Nomor Kendaraan</label>
                        <input type="text" 
                            name="plat_nomor" 
                            class="form-control text-uppercase fw-bold rounded-3" 
                            placeholder="Contoh: Z1234AB"
                            maxlength="11"
                            pattern="[A-Za-z]{1,2}\s?[1-9][0-9]{0,3}\s?[A-Za-z]{1,3}"
                            title="Format plat nomor Indonesia yang valid (1-2 huruf depan, 1-4 angka, 1-3 huruf belakang. Contoh: Z1234AB)"
                            oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                            value="{{ old('plat_nomor', $profil?->plat_nomor ?? '') }}" 
                            required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small text-muted fw-semibold">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="form-select rounded-3 fw-semibold">
                            <option value="MOTOR" {{ strtoupper($jenisKendaraan) == 'MOTOR' ? 'selected' : '' }}>Motor</option>
                            <option value="MOBIL" {{ strtoupper($jenisKendaraan) == 'MOBIL' ? 'selected' : '' }}>Mobil</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-unsil-primary rounded-3 fw-semibold px-4">Simpan Pembaruan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const qrData = {
        token: @json($qrToken),
        nama: @json(Auth::user()->name),
        kategori: @json($kategori),
        plat: @json(strtoupper($platNomor)),
        jenis: @json(strtoupper($jenisKendaraan)),
        qrUrl: "https://api.qrserver.com/v1/create-qr-code/?size=400x400&margin=10&data=" + encodeURIComponent(@json($qrToken))
    };

    // 1. FUNGSI DOWNLOAD KARTU QR SEBAGAI FILE PNG
    async function downloadKartuQr() {
        try {
            const response = await fetch(qrData.qrUrl);
            const blob = await response.blob();
            const imgBitmap = await createImageBitmap(blob);

            // Buat Canvas Kartu Parkir UNSIL (600 x 840 px)
            const canvas = document.createElement('canvas');
            canvas.width = 600;
            canvas.height = 840;
            const ctx = canvas.getContext('2d');

            // Background Kartu Putih
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Header Hijau Tua UNSIL
            ctx.fillStyle = '#003d22';
            ctx.fillRect(0, 0, canvas.width, 135);

            // Garis Aksen Kuning Emas
            ctx.fillStyle = '#facc15';
            ctx.fillRect(0, 135, canvas.width, 10);

            // Teks Header
            ctx.fillStyle = '#facc15';
            ctx.font = 'bold 28px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('E-PARKIR UNIVERSITAS SILIWANGI', canvas.width / 2, 62);

            ctx.fillStyle = '#bbf7d0';
            ctx.font = '18px sans-serif';
            ctx.fillText('KARTU AKSES PARKIR DIGITAL (' + qrData.kategori + ')', canvas.width / 2, 102);

            // Gambar QR Code di Tengah
            const qrSize = 360;
            const qrX = (canvas.width - qrSize) / 2;
            const qrY = 185;
            ctx.drawImage(imgBitmap, qrX, qrY, qrSize, qrSize);

            // Kotak Token QR
            ctx.fillStyle = '#005a32';
            ctx.fillRect(120, 565, 360, 46);
            ctx.fillStyle = '#facc15';
            ctx.font = 'bold 20px monospace';
            ctx.fillText(qrData.token, canvas.width / 2, 595);

            // Nama & Plat Nomor
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 26px sans-serif';
            ctx.fillText(qrData.nama, canvas.width / 2, 665);

            ctx.fillStyle = '#005a32';
            ctx.font = 'bold 34px sans-serif';
            ctx.fillText(qrData.plat + ' (' + qrData.jenis + ')', canvas.width / 2, 718);

            // Footer kecil
            ctx.fillStyle = '#64748b';
            ctx.font = '15px sans-serif';
            ctx.fillText('Pindai QR ini pada petugas jukir saat masuk & keluar area kampus', canvas.width / 2, 785);

            // Border luar kartu
            ctx.strokeStyle = '#005a32';
            ctx.lineWidth = 8;
            ctx.strokeRect(4, 4, canvas.width - 8, canvas.height - 8);

            // Trigger Download File PNG
            const link = document.createElement('a');
            link.download = `QR-Parkir-UNSIL-${qrData.plat.replace(/\s+/g, '-')}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        } catch (err) {
            window.open(qrData.qrUrl, '_blank');
        }
    }

    // 2. FUNGSI CETAK KARTU PARKIR (PRINT-READY POPUP)
    function cetakKartuQr() {
        const printWindow = window.open('', '_blank', 'width=480,height=680');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Cetak Kartu Parkir - ${qrData.nama}</title>
                <style>
                    body {
                        font-family: 'Segoe UI', Arial, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                        margin: 0;
                        background: #f1f5f9;
                    }
                    .card-print {
                        width: 330px;
                        background: #ffffff;
                        border: 3px solid #005a32;
                        border-radius: 16px;
                        overflow: hidden;
                        text-align: center;
                        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
                    }
                    .header {
                        background: #003d22;
                        color: #ffffff;
                        padding: 16px 12px;
                        border-bottom: 4px solid #facc15;
                    }
                    .header h3 {
                        margin: 0;
                        color: #facc15;
                        font-size: 16px;
                        letter-spacing: 0.5px;
                    }
                    .header p {
                        margin: 4px 0 0;
                        font-size: 11px;
                        color: #bbf7d0;
                    }
                    .body {
                        padding: 20px 16px;
                    }
                    .qr-img {
                        width: 210px;
                        height: 210px;
                        border: 2px solid #e2e8f0;
                        border-radius: 12px;
                        padding: 8px;
                    }
                    .token {
                        display: inline-block;
                        margin-top: 12px;
                        background: #005a32;
                        color: #facc15;
                        font-family: monospace;
                        font-weight: bold;
                        font-size: 13px;
                        padding: 6px 14px;
                        border-radius: 8px;
                    }
                    .badge-kat {
                        display: inline-block;
                        margin-top: 10px;
                        background: #fef9c3;
                        color: #854d0e;
                        font-size: 11px;
                        font-weight: 700;
                        padding: 3px 10px;
                        border-radius: 99px;
                    }
                    .nama {
                        margin: 8px 0 4px;
                        font-size: 17px;
                        font-weight: 700;
                        color: #0f172a;
                    }
                    .plat {
                        font-size: 20px;
                        font-weight: 800;
                        color: #005a32;
                        margin: 0;
                    }
                    .footer {
                        background: #f8faf9;
                        border-top: 1px dashed #cbd5e1;
                        padding: 10px;
                        font-size: 10px;
                        color: #475569;
                    }
                    @media print {
                        body { background: #ffffff; }
                        .card-print { box-shadow: none; }
                    }
                </style>
            </head>
            <body>
                <div class="card-print">
                    <div class="header">
                        <h3>E-PARKIR UNSIL</h3>
                        <p>Kartu Identitas Parkir Kampus</p>
                    </div>
                    <div class="body">
                        <img src="${qrData.qrUrl}" class="qr-img" onload="setTimeout(() => { window.print(); window.close(); }, 300);">
                        <div><span class="token">${qrData.token}</span></div>
                        <div><span class="badge-kat">${qrData.kategori}</span></div>
                        <div class="nama">${qrData.nama}</div>
                        <div class="plat">${qrData.plat} &bull; ${qrData.jenis}</div>
                    </div>
                    <div class="footer">
                        Tunjukkan kartu ini kepada petugas pos parkir Universitas Siliwangi
                    </div>
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
@endsection