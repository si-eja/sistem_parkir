<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Parkir;

class AreaController extends Controller
{
    public function dashboardJukir(Request $request)
    {
        $semua_area = Area::withCount(['parkir as parkir_aktif_count' => function ($query) {$query->where('status', 'AKTIF');
        }])->get();

        $selectedAreaId = $request->query('area_id',$semua_area->first()->id ?? null);
        $area = $selectedAreaId ? Area::findOrFail($selectedAreaId) : null;

        $kendaraan_aktif = collect();$sisa_kuota = 0;
        $slot_kosong_list = [];

        if ($area) {$kendaraan_aktif = Parkir::with('user')
                ->where('area_id', $area->id)
                ->where('status', 'AKTIF')
                ->orderBy('created_at', 'desc')
                ->get();

            $sisa_kuota = max(0, $area->kapasitas_total -$kendaraan_aktif->count());

            // Hitung daftar slot kosong untuk dropdown pilihan slot opsional
            $slot_terpakai =$kendaraan_aktif->pluck('nomor_slot')->toArray();
            for ($i = 1; $i <= $area->kapasitas_total; $i++) {
                if (!in_array($i,$slot_terpakai)) {
                    $slot_kosong_list[] =$i;
                }
            }
        }

        return view('jukir', compact('area', 'kendaraan_aktif', 'sisa_kuota', 'semua_area', 'slot_kosong_list'));
    }

    public function dashboardAdmin()
    {
        $semua_area = Area::withCount(['parkir as parkir_aktif_count' => function ($query) {$query->where('status', 'AKTIF');
        }])->get();

        $riwayat_parkir = Parkir::with(['area', 'user'])
            ->where('status', 'SELESAI')
            ->orderBy('waktu_keluar', 'desc')
            ->take(50)
            ->get();

        return view('admin', compact('semua_area', 'riwayat_parkir'));
    }

    // Monitoring Grid Virtual Slot (FT001 s/d FT200)
    public function detailSlotArea($id)
    {
        $area = Area::findOrFail($id);

        $parkirAktif = Parkir::with('user')
            ->where('area_id', $area->id)
            ->where('status', 'AKTIF')
            ->get()
            ->keyBy('nomor_slot');

        $listSlot = [];
        for ($i = 1; $i <= $area->kapasitas_total; $i++) {
            $kodeSlot =$area->kode_area . str_pad($i, 3, '0', STR_PAD_LEFT);$terisi = isset($parkirAktif[$i]);

            $listSlot[] = [
                'nomor'     => $i,
                'kode_slot' => $kodeSlot,
                'status'    => $terisi ? 'TERISI' : 'KOSONG',
                'data'      => $terisi ? $parkirAktif[$i] : null,
            ];
        }

        return view('admin_slot_detail', compact('area', 'listSlot'));
    }

    public function updateKuota(Request $request)
    {
        $request->validate([
            'area_id'         => 'required|exists:areas,id',
            'kapasitas_umum'  => 'required|integer|min:0',
            'kapasitas_dosen' => 'required|integer|min:0',
            'khusus_dosen'    => 'nullable|boolean',
        ]);

        $area = Area::findOrFail($request->area_id);

        // Jika mode "100% Khusus Dosen" dicentang, slot umum otomatis 0
        $isFullDosen    = $request->has('khusus_dosen');
        $kapasitasDosen = (int) $request->kapasitas_dosen;
        $kapasitasUmum  = $isFullDosen ? 0 : (int) $request->kapasitas_umum;
        $kapasitasTotal = $kapasitasDosen + $kapasitasUmum;

        if ($kapasitasTotal < 1) {
            return back()->with('error', 'Total kapasitas parkir (Dosen + Umum) minimal harus 1 slot!');
        }

        // Validasi 1: Cek kendaraan yang sedang parkir di Zona Dosen (nomor_slot <= kapasitas_dosen lama)
        $terisiZonaDosen = Parkir::where('area_id', $area->id)
            ->where('status', 'AKTIF')
            ->where('nomor_slot', '<=', $area->kapasitas_dosen)
            ->count();

        if ($kapasitasDosen < $terisiZonaDosen) {
            return back()->with('error', "Gagal! Saat ini ada {$terisiZonaDosen} kendaraan dosen yang aktif di Zona Dosen {$area->nama_area}.");
        }

        // Validasi 2: Cek total kendaraan yang sedang parkir secara keseluruhan
        $totalTerisi = Parkir::where('area_id', $area->id)
            ->where('status', 'AKTIF')
            ->count();

        if ($kapasitasTotal < $totalTerisi) {
            return back()->with('error', "Gagal! Total kapasitas baru ({$kapasitasTotal}) lebih kecil dari jumlah kendaraan yang sedang terparkir ({$totalTerisi} unit).");
        }

        $area->update([
            'kapasitas_dosen' => $kapasitasDosen,
            'kapasitas_total' => $kapasitasTotal,
            'khusus_dosen'    => $isFullDosen,
        ]);

        return back()->with('success', "Kuota {$area->nama_area} diperbarui: {$kapasitasDosen} Slot Dosen & {$kapasitasUmum} Slot Umum (Total: {$kapasitasTotal} Slot).");
    }
}