<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parkir;
use App\Models\Area;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ParkirController extends Controller
{
    // Mengizinkan pengendara memilih area / menerima id area opsional via URL
    public function indexPengendara(Request $request)
    {
        $semua_area = Area::all();
        $selected_area_id = $request->query('area_id', 1); // default ke area 1 jika tidak ada param
        
        $area = Area::find($selected_area_id) ?? Area::first(); 
        
        $terisi = 0;
        $sisa_kuota = 0;

        if ($area) {
            $terisi = Parkir::where('area_id', $area->id)->where('status', 'AKTIF')->count();
            $sisa_kuota = $area->kapasitas_total - $terisi; // DIPERBAIKI
        }

        return view('pengendara', compact('semua_area', 'area', 'sisa_kuota'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:12',
            'area_id'    => 'required|exists:areas,id'
        ]);

        $platFormatted = strtoupper(str_replace(' ', '', $request->plat_nomor));

        // PENCEGAHAN: Cek apakah motor dengan plat ini sudah parkir dan statusnya masih AKTIF
        $platAktif = Parkir::where('plat_nomor', $platFormatted)
            ->where('status', 'AKTIF')
            ->first();

        if ($platAktif) {
            return back()->with('error', 'Motor dengan plat ' . $platFormatted . ' sudah terdaftar dan masih berada di dalam area parkir!')->withInput();
        }

        $area = Area::findOrFail($request->area_id);
        $terisi = Parkir::where('area_id', $request->area_id)->where('status', 'AKTIF')->count();

        // Validasi batasan kapasitas kuota
        if ($terisi >= $area->kapasitas_total) {
            return back()->with('error', 'Gagal Check-In! Kuota area parkir ' . $area->nama_area . ' sudah penuh.')->withInput();
        }

        // Tentukan input_oleh berdasarkan auth & request
        $inputOleh = 'MANDIRI';
        $userId = null;

        if (Auth::check()) {
            $userId = Auth::id();
            if (Auth::user()->role === 'JUKIR') {
                $inputOleh = 'JUKIR';
            }
        }

        Parkir::create([
            'plat_nomor' => $platFormatted,
            'area_id'    => $request->area_id,
            'user_id'    => $userId,
            'input_oleh' => $inputOleh,
            'status'     => 'AKTIF',
        ]);

        return back()->with('success', 'Kendaraan (' . $platFormatted . ') berhasil Check-In!');
    }

    public function checkout($id)
    {
        try {
            $parkir = \App\Models\Parkir::findOrFail($id);

            // Cek jika status kendaraan sudah selesai sebelumnya
            if ($parkir->status === 'SELESAI') {
                return back()->with('error', 'Kendaraan ini sudah melakukan check-out sebelumnya.');
            }

            // Update status dan waktu keluar ke Asia/Jakarta
            $parkir->update([
                'status' => 'SELESAI',
                'waktu_keluar' => \Carbon\Carbon::now('Asia/Jakarta'),
            ]);

            return back()->with('success', 'Check-Out berhasil! Kuota parkir telah dilepaskan. Terima kasih.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan Check-Out. Silakan coba beberapa saat lagi.');
        }
    }
}