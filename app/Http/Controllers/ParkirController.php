<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parkir;
use App\Models\Area;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ParkirController extends Controller
{

    // Update Plat Nomor & Jenis Kendaraan
    // Dashboard Pengendara (Tampil QR Permanen, Riwayat & Info Slot Kampus)
    public function indexPengendara(Request $request)
    {
        $user = Auth::user();
        $pengendara = $user->pengendara;
        $platUser = strtoupper(str_replace(' ', '', $pengendara->plat_nomor ?? ''));

        // 1. Ambil Parkir Aktif (Berdasarkan user_id ATAU plat_nomor)
        $parkir_aktif = Parkir::with('area')
            ->where('status', 'AKTIF')
            ->where(function ($query) use ($user, $platUser) {
                $query->where('user_id', $user->id);
                if (!empty($platUser) && $platUser !== '-') {
                    $query->orWhere('plat_nomor', $platUser);
                }
            })
            ->first();

        // 2. Data Lahan Area & Kuota Kampus
        $semua_area = Area::withCount(['parkir as parkir_aktif_count' => function ($q) {
            $q->where('status', 'AKTIF');
        }])->get();

        // 3. Ambil Riwayat Parkir Terakhir
        $riwayat_terakhir = Parkir::with('area')
            ->where(function ($query) use ($user, $platUser) {
                $query->where('user_id', $user->id);
                if (!empty($platUser) && $platUser !== '-') {
                    $query->orWhere('plat_nomor', $platUser);
                }
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('pengendara', compact(
            'pengendara',
            'parkir_aktif', 
            'semua_area', 
            'riwayat_terakhir'
        ));
    }

    // Update Plat Nomor & Jenis Kendaraan di Tabel pengendaras
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pengendaraId = $user->pengendara->id ?? 'NULL';

        $request->validate([
            'plat_nomor' => [
                'required',
                'string',
                'max:12',
                'unique:pengendaras,plat_nomor,' . $pengendaraId,
                'regex:/^[A-Za-z]{1,2}\s?[1-9][0-9]{0,3}\s?[A-Za-z]{1,3}$/'
            ],
            'jenis_kendaraan' => 'required|in:Motor,Mobil,MOTOR,MOBIL',
        ], [
            'plat_nomor.regex'  => 'Format plat nomor tidak valid! Gunakan format standar Indonesia (Contoh: Z1234AB).',
            'plat_nomor.unique' => 'Plat nomor ini sudah terdaftar pada akun pengendara lain.'
        ]);

        $plat_clean      = strtoupper(str_replace(' ', '', trim($request->plat_nomor)));
        $jenis_kendaraan = strtoupper($request->jenis_kendaraan);

        // Simpan ke tabel pengendaras (qr_code_token otomatis ter-generate oleh Model jika record baru)
        $user->pengendara()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'plat_nomor'      => $plat_clean,
                'jenis_kendaraan' => $jenis_kendaraan,
            ]
        );

        return redirect()->back()->with('success', 'Profil kendaraan & Plat Nomor berhasil diperbarui!');
    }

    // POS Jukir: Mode 1 Scan QR Universal (Masuk & Keluar) + Mode 2 Input Manual (Masuk)
    public function checkInJukir(Request $request)
    {
        // 1. Validasi Umum
        $request->validate([
            'area_id'         => 'required|exists:areas,id',
            'qr_code_token'   => 'nullable|string',
            'plat_nomor'      => [
                'nullable',
                'string',
                'max:12',
                'regex:/^[A-Za-z]{1,2}\s?[1-9][0-9]{0,3}\s?[A-Za-z]{1,3}$/'
            ],
            'jenis_kendaraan' => 'nullable|in:MOTOR,MOBIL',
            'nomor_slot'      => 'nullable|integer|min:1',
        ], [
            'plat_nomor.regex' => 'Format plat nomor tidak valid! Contoh yang benar: Z1234AB.'
        ]);

        // =========================================================================
        // MODE 1: SCAN QR UNIVERSAL (OTOMATIS CHECK-IN ATAU CHECK-OUT)
        // =========================================================================
        if ($request->filled('qr_code_token')) {
            $token = trim($request->qr_code_token);

            $pengendara = \App\Models\Pengendara::with('user')
                ->where('qr_code_token', $token)
                ->first();

            if (!$pengendara || !$pengendara->user) {
                return back()->with('error', 'Token QR tidak dikenali atau belum terdaftar!');
            }

            $user = $pengendara->user;

            // Cek apakah pengendara sedang parkir AKTIF (Check-Out Universal tetap diizinkan di gerbang mana pun)
            $parkirAktif = Parkir::with('area')
                ->where('user_id', $user->id)
                ->where('status', 'AKTIF')
                ->first();

            if ($parkirAktif) {
                $namaAreaAsal = $parkirAktif->area->nama_area ?? 'Area Kampus';
                $parkirAktif->update([
                    'status'       => 'SELESAI',
                    'waktu_keluar' => Carbon::now('Asia/Jakarta'),
                ]);

                return back()->with('success', "Check-Out Universal Berhasil! {$user->name} ({$pengendara->kategori} - {$parkirAktif->plat_nomor}) keluar dari {$namaAreaAsal}.");
            }

            // Validasi kelengkapan plat nomor sebelum Check-In
            $platUser = strtoupper(str_replace(' ', '', trim($pengendara->plat_nomor ?? '')));
            if (empty($platUser) || $platUser === '-') {
                return back()->with('error', "Pengendara {$user->name} belum mengatur Plat Nomor di profilnya!");
            }

            $area            = Area::findOrFail($request->area_id);
            $kategoriUser    = strtoupper($pengendara->kategori ?? 'MAHASISWA');
            $isDosen         = ($kategoriUser === 'DOSEN');
            $batasDosen      = (int) ($area->kapasitas_dosen ?? 0);
            $isAreaFullDosen = (bool) $area->khusus_dosen || ($batasDosen > 0 && $batasDosen >= (int) $area->kapasitas_total);

            // TOLAK JIKA AREA DIKUNCI KHUSUS DOSEN & YANG SCAN BUKAN DOSEN
            if ($isAreaFullDosen && !$isDosen) {
                return back()->with('error', "AKSES DITOLAK! {$area->nama_area} adalah area parkir khusus DOSEN. Akun {$user->name} terdaftar sebagai {$kategoriUser}.");
            }

            // TOLAK JIKA MAHASISWA DIPILIHKAN SLOT DI DALAM ZONA DOSEN
            if (!$isDosen && $request->filled('nomor_slot') && (int) $request->nomor_slot <= $batasDosen) {
                $kodeTarget = $area->kode_area . str_pad($request->nomor_slot, 3, '0', STR_PAD_LEFT);
                return back()->with('error', "AKSES DITOLAK! Slot {$kodeTarget} berada di Zona Khusus Dosen.");
            }

            $slotTerpakai = Parkir::where('area_id', $area->id)
                ->where('status', 'AKTIF')
                ->pluck('nomor_slot')
                ->toArray();

            if (count($slotTerpakai) >= $area->kapasitas_total) {
                return back()->with('error', "Gagal Check-In! Lahan {$area->nama_area} sudah penuh.");
            }

            $nomorSlot = null;

            if ($isDosen) {
                // Dosen: Prioritas slot 1 s/d batasDosen, lalu lanjut ke slot umum jika zona dosen penuh
                for ($i = 1; $i <= $area->kapasitas_total; $i++) {
                    if (!in_array($i, $slotTerpakai)) {
                        $nomorSlot = $i;
                        break;
                    }
                }
            } else {
                // Mahasiswa: Hanya boleh masuk mulai slot batasDosen + 1 s/d kapasitas_total
                for ($i = $batasDosen + 1; $i <= $area->kapasitas_total; $i++) {
                    if (!in_array($i, $slotTerpakai)) {
                        $nomorSlot = $i;
                        break;
                    }
                }

                // Jika slot umum habis sementara slot dosen masih kosong, tolak akses Mahasiswa
                if (!$nomorSlot) {
                    $akhirZonaDosen = $area->kode_area . str_pad($batasDosen, 3, '0', STR_PAD_LEFT);
                    return back()->with('error', "AKSES DITOLAK! Kuota parkir Mahasiswa di {$area->nama_area} sudah penuh. Sisa slot ({$area->kode_area}001–{$akhirZonaDosen}) dikhususkan untuk DOSEN.");
                }
            }

            Parkir::create([
                'user_id'         => $user->id,
                'area_id'         => $area->id,
                'plat_nomor'      => $platUser,
                'jenis_kendaraan' => strtoupper($pengendara->jenis_kendaraan ?? 'MOTOR'),
                'nomor_slot'      => $nomorSlot,
                'status'          => 'AKTIF',
                'input_oleh'      => 'JUKIR',
            ]);

            $kodeSlot  = $area->kode_area . str_pad($nomorSlot, 3, '0', STR_PAD_LEFT);
            $zonaLabel = ($nomorSlot <= $batasDosen) ? 'Zona Khusus Dosen' : 'Zona Umum';

            return back()->with('success', "Check-In QR Berhasil! {$user->name} [{$kategoriUser}] ({$platUser}) masuk ke Slot {$kodeSlot} ({$zonaLabel}).");
        }

        // =========================================================================
        // MODE 2: INPUT MANUAL (KHUSUS CHECK-IN KENDARAAN NON-AKUN / TAMU)
        // =========================================================================
        $request->validate([
            'plat_nomor' => [
                'required',
                'string',
                'max:12',
                'regex:/^[A-Za-z]{1,2}\s?[1-9][0-9]{0,3}\s?[A-Za-z]{1,3}$/'
            ],
            'jenis_kendaraan' => 'required|in:MOTOR,MOBIL',
        ], [
            'plat_nomor.regex' => 'Format plat nomor tidak valid! Contoh yang benar: Z1234AB.'
        ]);

        $plat = strtoupper(str_replace(' ', '', trim($request->plat_nomor)));

        $cekPlatAktif = Parkir::where('plat_nomor', $plat)->where('status', 'AKTIF')->first();
        if ($cekPlatAktif) {
            return back()->with('error', "Kendaraan dengan plat {$plat} masih tercatat parkir aktif!");
        }

        $area            = Area::findOrFail($request->area_id);
        $batasDosen      = (int) ($area->kapasitas_dosen ?? 0);
        $isAreaFullDosen = (bool) $area->khusus_dosen || ($batasDosen > 0 && $batasDosen >= (int) $area->kapasitas_total);

        if ($isAreaFullDosen) {
            return back()->with('error', "AKSES DITOLAK! {$area->nama_area} adalah area parkir khusus DOSEN (Wajib Scan QR Dosen).");
        }

        $slotTerpakai = Parkir::where('area_id', $area->id)
            ->where('status', 'AKTIF')
            ->pluck('nomor_slot')
            ->toArray();

        $nomorSlot = null;

        if (
            $request->filled('nomor_slot') &&
            (int) $request->nomor_slot > $batasDosen &&
            (int) $request->nomor_slot <= $area->kapasitas_total &&
            !in_array((int) $request->nomor_slot, $slotTerpakai)
        ) {
            $nomorSlot = (int) $request->nomor_slot;
        } else {
            for ($i = $batasDosen + 1; $i <= $area->kapasitas_total; $i++) {
                if (!in_array($i, $slotTerpakai)) {
                    $nomorSlot = $i;
                    break;
                }
            }
        }

        if (!$nomorSlot) {
            return back()->with('error', "AKSES DITOLAK! Kuota parkir umum di {$area->nama_area} sudah penuh.");
        }

        Parkir::create([
            'user_id'         => null,
            'area_id'         => $area->id,
            'plat_nomor'      => $plat,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'nomor_slot'      => $nomorSlot,
            'status'          => 'AKTIF',
            'input_oleh'      => 'JUKIR',
        ]);

        $kodeSlot = $area->kode_area . str_pad($nomorSlot, 3, '0', STR_PAD_LEFT);
        return back()->with('success', "Check-In Manual Berhasil! Kendaraan {$plat} masuk ke Slot {$kodeSlot}.");
    }

    // Process Check-Out (Melepaskan Slot Parkir)
    public function checkOut($id)
    {
        try {
            $parkir = Parkir::findOrFail($id);

            if ($parkir->status === 'SELESAI') {
                return back()->with('error', 'Kendaraan ini sudah melakukan Check-Out sebelumnya.');
            }

            $parkir->update([
                'status'       => 'SELESAI',
                'waktu_keluar' => Carbon::now('Asia/Jakarta'),
            ]);

            return back()->with('success', 'Check-Out berhasil! Slot parkir telah dikosongkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan Check-Out. Silakan coba beberapa saat lagi.');
        }
    }
}