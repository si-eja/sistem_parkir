<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parkir;
use App\Models\Area;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Str;

class ParkirController extends Controller
{
    // Dashboard Pengendara (Check-In Mandiri & Tampil QR Permanen)
    public function indexPengendara(Request $request)
    {
        $user = Auth::user();
        
        // Hilangkan spasi pada plat nomor user untuk pencarian presisi
        $platUser = strtoupper(str_replace(' ', '', $user->plat_nomor));

        // 1. Ambil Parkir Aktif (Berdasarkan user_id ATAU plat_nomor)
        $parkir_aktif = Parkir::with('area')
            ->where('status', 'AKTIF')
            ->where(function ($query) use ($user, $platUser) {
                $query->where('user_id', $user->id);
                if (!empty($platUser)) {
                    $query->orWhere('plat_nomor', $platUser);
                }
            })
            ->first();

        // 2. Data Lahan Area & Kuota
        $semua_area = Area::withCount(['parkir as parkir_aktif_count' => function ($q) {
            $q->where('status', 'AKTIF');
        }])->get();

        $selected_area_id = $request->query('area_id', $semua_area->first()->id ?? null);
        $area = Area::find($selected_area_id);

        $sisa_kuota = 0;
        $slot_kosong_list = [];

        if ($area) {
            $terisi = Parkir::where('area_id', $area->id)->where('status', 'AKTIF')->count();
            $sisa_kuota = max(0, $area->kapasitas_total - $terisi);

            $slot_terpakai = Parkir::where('area_id', $area->id)
                ->where('status', 'AKTIF')
                ->pluck('nomor_slot')
                ->toArray();

            for ($i = 1; $i <= $area->kapasitas_total; $i++) {
                if (!in_array($i, $slot_terpakai)) {
                    $slot_kosong_list[] = $i;
                }
            }
        }

        // 3. AMBIL RIWAYAT PARKIR TERAKHIR (USER_ID ATAU PLAT NOMOR)
        $riwayat_terakhir = Parkir::with('area')
            ->where(function ($query) use ($user, $platUser) {
                $query->where('user_id', $user->id);
                if (!empty($platUser)) {
                    $query->orWhere('plat_nomor', $platUser);
                }
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('pengendara', compact(
            'parkir_aktif', 
            'semua_area', 
            'area', 
            'sisa_kuota', 
            'slot_kosong_list', 
            'riwayat_terakhir'
        ));
    }

    // METHOD BARU: Update Plat Nomor & Jenis Kendaraan
    public function updateProfile(Request $request)
    {
        // 1. Validasi Input (Gunakan uppercase MOTOR / MOBIL)
        $request->validate([
            'plat_nomor'      => 'required|string|max:15',
            'jenis_kendaraan' => 'required|in:Motor,Mobil,MOTOR,MOBIL',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 2. Format Plat Nomor & Jenis Kendaraan ke KAPITAL (Sesuai SQLite Check Constraint)
        $plat_clean      = strtoupper(trim(preg_replace('/\s+/', ' ', $request->plat_nomor)));
        $jenis_kendaraan = strtoupper($request->jenis_kendaraan); // Mencegah error CHECK constraint

        // 3. Token QR Code Safe
        $newToken = 'PARKIR-' . Str::random(10);

        // 4. Update Database
        $user->update([
            'plat_nomor'      => $plat_clean,
            'jenis_kendaraan' => $jenis_kendaraan, // Nilai yang disimpan pasti 'MOTOR' atau 'MOBIL'
            'qr_code_token'   => $user->qr_code_token ?? $newToken,
        ]);

        return redirect()->back()->with('success', 'Profil kendaraan & Plat Nomor berhasil diperbarui!');
    }

    // Check-In Mandiri oleh Pengendara yang Login
    public function checkIn(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'area_id' => 'required|exists:areas,id'
        ]);

        $platFormatted = strtoupper(str_replace(' ', '', $request->plat_nomor ?? $user->plat_nomor));

        // Pencegahan: Cek apakah kendaraan pengendara ini sudah parkir dan statusnya masih AKTIF
        $platAktif = Parkir::where('plat_nomor', $platFormatted)
            ->where('status', 'AKTIF')
            ->first();

        if ($platAktif) {
            return back()->with('error', 'Kendaraan dengan plat ' . $platFormatted . ' sudah terdaftar dan masih berada di dalam area parkir!')->withInput();
        }

        $area = Area::findOrFail($request->area_id);

        // Algoritma Alokasi Virtual Slot Kosong & Rekomendasi
        $alokasi = $this->alokasikanSlotDanArea($area);

        if (!$alokasi['success']) {
            return back()->with('error', $alokasi['message'])->withInput();
        }

        // Simpan Transaksi Parkir
        $parkir = Parkir::create([
            'plat_nomor'       => $platFormatted,
            'jenis_kendaraan'  => $user->jenis_kendaraan ?? 'MOTOR',
            'kategori_pengguna' => $user->kategori_pengguna ?? 'MAHASISWA',
            'area_id'          => $alokasi['area_id'],
            'nomor_slot'       => $alokasi['nomor_slot'],
            'user_id'          => $user->id,
            'input_oleh'       => 'MANDIRI',
            'status'           => 'AKTIF',
        ]);

        $kodeSlot = $alokasi['kode_area'] . str_pad($alokasi['nomor_slot'], 3, '0', STR_PAD_LEFT);

        return back()->with('success', 'Berhasil Check-In! Silakan memarkirkan kendaraan di Slot: ' . $kodeSlot);
    }

    // Check-In yang Dilakukan oleh Jukir (Bisa Manual Plat atau Scan Token QR Pengendara)
    public function checkInJukir(Request $request)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'plat_nomor' => 'required_without:qr_code_token',
        ]);

        $userPengendara = null;
        $platFormatted = '';
        $jenisKendaraan = 'MOTOR';
        $kategoriPengguna = 'UMUM';

        // Jika Jukir melakukan Scan Token QR Permanen Pengendara
        if ($request->filled('qr_code_token')) {
            $userPengendara = User::where('qr_code_token', $request->qr_code_token)->first();
            if (!$userPengendara) {
                return back()->with('error', 'QR Code / Token Pengendara tidak valid!')->withInput();
            }
            $platFormatted = $userPengendara->plat_nomor;
            $jenisKendaraan = $userPengendara->jenis_kendaraan;
            $kategoriPengguna = $userPengendara->kategori_pengguna;
        } else {
            $platFormatted = strtoupper(str_replace(' ', '', $request->plat_nomor));
        }

        // Cek Kendaraan Aktif
        $platAktif = Parkir::where('plat_nomor', $platFormatted)
            ->where('status', 'AKTIF')
            ->first();

        if ($platAktif) {
            return back()->with('error', 'Kendaraan (' . $platFormatted . ') sudah berada di dalam area parkir!')->withInput();
        }

        $area = Area::findOrFail($request->area_id);
        $alokasi = $this->alokasikanSlotDanArea($area);

        if (!$alokasi['success']) {
            return back()->with('error', $alokasi['message'])->withInput();
        }

        Parkir::create([
            'plat_nomor'        => $platFormatted,
            'jenis_kendaraan'   => $jenisKendaraan,
            'kategori_pengguna' => $kategoriPengguna,
            'area_id'           => $alokasi['area_id'],
            'nomor_slot'        => $alokasi['nomor_slot'],
            'user_id'           => $userPengendara ? $userPengendara->id : null,
            'input_oleh'        => 'JUKIR',
            'status'            => 'AKTIF',
        ]);

        $kodeSlot = $alokasi['kode_area'] . str_pad($alokasi['nomor_slot'], 3, '0', STR_PAD_LEFT);

        return back()->with('success', 'Check-In Sukses oleh Jukir! Alokasi Slot: ' . $kodeSlot);
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
                'status' => 'SELESAI',
                'waktu_keluar' => Carbon::now('Asia/Jakarta'),
            ]);

            return back()->with('success', 'Check-Out berhasil! Slot parkir telah dikosongkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan Check-Out. Silakan coba beberapa saat lagi.');
        }
    }

    // Helper Private: Cari Nomor Slot Kosong Paling Awal / Rekomendasi Area Lain jika Penuh
    private function alokasikanSlotDanArea(Area $areaTarget)
    {
        $slotTerpakai = Parkir::where('area_id', $areaTarget->id)
            ->where('status', 'AKTIF')
            ->pluck('nomor_slot')
            ->toArray();

        $nomorSlotKosong = null;
        for ($i = 1; $i <= $areaTarget->kapasitas_total; $i++) {
            if (!in_array($i, $slotTerpakai)) {
                $nomorSlotKosong = $i;
                break;
            }
        }

        // Jika Area Target Masih Ada Slot Kosong
        if ($nomorSlotKosong) {
            return [
                'success'    => true,
                'area_id'    => $areaTarget->id,
                'kode_area'  => $areaTarget->kode_area,
                'nomor_slot' => $nomorSlotKosong
            ];
        }

        // JIKA PENUH -> Sistem Otomatis Cari Rekomendasi Area Fakultas Lain
        $areaRekomendasi = Area::all()->filter(function ($a) use ($areaTarget) {
            if ($a->id == $areaTarget->id) return false;
            $terisi = Parkir::where('area_id', $a->id)->where('status', 'AKTIF')->count();
            return $terisi < $a->kapasitas_total;
        })->first();

        if ($areaRekomendasi) {
            return [
                'success' => false,
                'message' => "Area {$areaTarget->nama_area} Penuh! Disarankan memarkirkan kendaraan di {$areaRekomendasi->nama_area}."
            ];
        }

        return [
            'success' => false,
            'message' => 'Maaf, seluruh area lahan parkir kampus saat ini penuh!'
        ];
    }
}