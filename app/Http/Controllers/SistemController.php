<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SistemController extends Controller
{
    // Registrasi Akun Pengendara Mandiri (5 Data Universal)
    public function prosesRegister(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:users',
            'password'        => 'required|string|min:6|confirmed',
            'plat_nomor'      => 'required|string|max:12',
            'jenis_kendaraan' => 'required|in:MOTOR,MOBIL',
        ]);

        $platFormatted = strtoupper(str_replace(' ', '', $request->plat_nomor));

        $user = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'role'             => 'PENGENDARA',
            'plat_nomor'       => $platFormatted,
            'jenis_kendaraan'  => $request->jenis_kendaraan,
            'kategori_pengguna'=> $request->kategori_pengguna ?? 'MAHASISWA',
        ]);

        // Otomatis Login setelah registrasi berhasil
        Auth::login($user);

        return redirect()->route('pengendara.dashboard')->with('success', 'Pendaftaran Berhasil! Barcode Parkir Anda telah dibuat.');
    }

    // Kelola Petugas / User oleh Admin
    public function kelolaUser()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin_users', compact('users'));
    }
}