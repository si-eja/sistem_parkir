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
    // Dashboard Pengendara (Tampil QR Permanen, Riwayat & Info Slot Kampus)
    public function indexPengendara(Request $request)
    {
        $user = Auth::user();
        $platUser = strtoupper(str_replace(' ', '', $user->plat_nomor ?? ''));

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
            'parkir_aktif', 
            'semua_area', 
            'riwayat_terakhir'
        ));
    }

    // Update Plat Nomor & Jenis Kendaraan
    public function updateProfile(Request $request)
    {
        $request->validate([
            'plat_nomor'      => 'required|string|max:15',
            'jenis_kendaraan' => 'required|in:Motor,Mobil,MOTOR,MOBIL',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Standarisasi plat tanpa spasi & huruf kapital agar seragam dengan query pencarian
        $plat_clean      = strtoupper(str_replace(' ', '', trim($request->plat_nomor)));
        $jenis_kendaraan = strtoupper($request->jenis_kendaraan);
        $newToken        = 'PARKIR-' . strtoupper(Str::random(10));

        $user->update([
            'plat_nomor'      => $plat_clean,
            'jenis_kendaraan' => $jenis_kendaraan,
            'qr_code_token'   => $user->qr_code_token ?? $newToken,
        ]);

        return redirect()->back()->with('success', 'Profil kendaraan & Plat Nomor berhasil diperbarui!');
    }

    // POS Jukir: Mode 1 Scan QR Universal (Masuk & Keluar) + Mode 2 Input Manual (Masuk)
    public function checkInJukir(Request $request)
    {
        $request->validate([
            'area_id'         => 'required|exists:areas,id',
            'qr_code_token'   => 'nullable|string',
            'plat_nomor'      => 'nullable|string|max:20',
            'jenis_kendaraan' => 'nullable|in:MOTOR,MOBIL',
            'nomor_slot'      => 'nullable|integer|min:1',
        ]);

        // =========================================================================
        // MODE 1: SCAN QR UNIVERSAL (OTOMATIS CHECK-IN ATAU CHECK-OUT)
        // =========================================================================
        if ($request->filled('qr_code_token')) {
            $token = trim($request->qr_code_token);
            $user = User::where('qr_code_token', $token)->first();

            if (!$user) {
                return back()->with('error', 'Token QR tidak dikenali atau belum terdaftar!');
            }

            // Cek apakah user sedang parkir AKTIF di area manapun
            $parkirAktif = Parkir::with('area')
                ->where('user_id', $user->id)
                ->where('status', 'AKTIF')
                ->first();

            // JIKA SUDAH AKTIF -> OTOMATIS CHECK-OUT UNIVERSAL
            if ($parkirAktif) {
                $namaAreaAsal = $parkirAktif->area->nama_area ?? 'Area Kampus';
                $parkirAktif->update([
                    'status'       => 'SELESAI',
                    'waktu_keluar' => Carbon::now('Asia/Jakarta'),
                ]);

                return back()->with('success', "Check-Out Universal Berhasil! {$user->name} ({$parkirAktif->plat_nomor}) keluar dari {$namaAreaAsal}.");
            }

            // Validasi kelengkapan plat nomor pengendara sebelum Check-In
            $platUser = strtoupper(str_replace(' ', '', trim($user->plat_nomor ?? '')));
            if (empty($platUser) || $platUser === '-') {
                return back()->with('error', "Pengendara {$user->name} belum mengatur Plat Nomor di profilnya!");
            }

            // JIKA BELUM PARKIR -> OTOMATIS CHECK-IN KE AREA TUGAS SAAT INI
            $area = Area::findOrFail($request->area_id);
            $slotTerpakai = Parkir::where('area_id', $area->id)
                ->where('status', 'AKTIF')
                ->pluck('nomor_slot')
                ->toArray();

            if (count($slotTerpakai) >= $area->kapasitas_total) {
                return back()->with('error', "Gagal Check-In! Lahan {$area->nama_area} sudah penuh.");
            }

            $nomorSlot = 1;
            while (in_array($nomorSlot, $slotTerpakai)) {
                $nomorSlot++;
            }

            Parkir::create([
                'user_id'         => $user->id,
                'area_id'         => $area->id,
                'plat_nomor'      => $platUser,
                'jenis_kendaraan' => strtoupper($user->jenis_kendaraan ?? 'MOTOR'),
                'nomor_slot'      => $nomorSlot,
                'status'          => 'AKTIF',
                'input_oleh'      => 'JUKIR',
            ]);

            $kodeSlot = $area->kode_area . str_pad($nomorSlot, 3, '0', STR_PAD_LEFT);
            return back()->with('success', "Check-In QR Berhasil! {$user->name} ({$platUser}) masuk ke Slot {$kodeSlot}.");
        }

        // =========================================================================
        // MODE 2: INPUT MANUAL (KHUSUS CHECK-IN KENDARAAN NON-AKUN / TAMU)
        // =========================================================================
        $request->validate([
            'plat_nomor'      => 'required|string|max:20',
            'jenis_kendaraan' => 'required|in:MOTOR,MOBIL',
        ]);

        // Standarisasi plat nomor tanpa spasi
        $plat = strtoupper(str_replace(' ', '', trim($request->plat_nomor)));

        // Cegah duplikasi check-in untuk plat yang sedang parkir aktif
        $cekPlatAktif = Parkir::where('plat_nomor', $plat)->where('status', 'AKTIF')->first();
        if ($cekPlatAktif) {
            return back()->with('error', "Kendaraan dengan plat {$plat} masih tercatat parkir aktif!");
        }

        $area = Area::findOrFail($request->area_id);
        $slotTerpakai = Parkir::where('area_id', $area->id)->where('status', 'AKTIF')->pluck('nomor_slot')->toArray();

        if (count($slotTerpakai) >= $area->kapasitas_total) {
            return back()->with('error', "Lahan {$area->nama_area} sudah penuh!");
        }

        if ($request->filled('nomor_slot') && $request->nomor_slot <= $area->kapasitas_total && !in_array($request->nomor_slot, $slotTerpakai)) {
            $nomorSlot = (int) $request->nomor_slot;
        } else {
            $nomorSlot = 1;
            while (in_array($nomorSlot, $slotTerpakai)) {
                $nomorSlot++;
            }
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