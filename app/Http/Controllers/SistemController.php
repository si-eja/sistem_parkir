<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SistemController extends Controller
{
    // Registrasi Akun Pengendara
    public function prosesRegister(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:users',
            'password'        => 'required|string|min:6|confirmed',
            'plat_nomor'      => [
                'required',
                'string',
                'max:12',
                'regex:/^[A-Za-z]{1,2}\s?[1-9][0-9]{0,3}\s?[A-Za-z]{1,3}$/'
            ],
            'jenis_kendaraan' => 'required|in:MOTOR,MOBIL',
        ], [
            'plat_nomor.regex' => 'Format plat nomor tidak valid! Gunakan format standar Indonesia (Contoh: Z1234AB).'
        ]);

        $platFormatted = strtoupper(str_replace(' ', '', trim($request->plat_nomor)));

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => 'PENGENDARA',
            'plat_nomor'        => $platFormatted,
            'jenis_kendaraan'   => $request->jenis_kendaraan,
            'kategori_pengguna' => $request->kategori_pengguna ?? 'MAHASISWA',
            'qr_code_token'     => 'PARKIR-' . strtoupper(Str::random(10)),
        ]);

        Auth::login($user);

        return redirect()->route('pengendara.dashboard')->with('success', 'Pendaftaran Berhasil! Kartu QR Parkir Anda siap digunakan.');
    }

    // Tambah Akun Petugas Parkir Baru
    public function storePetugas(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'JUKIR',
            'plat_nomor' => '-',
        ]);

        return back()->with('success', "Akun petugas parkir {$request->name} berhasil ditambahkan!");
    }

    // Edit Data / Reset Password Petugas Parkir
    public function updatePetugas(Request $request, $id)
    {
        $petugas = User::where('role', 'JUKIR')->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $petugas->id,
            'password' => 'nullable|string|min:6',
        ]);

        $dataUpdate = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $dataUpdate['password'] = Hash::make($request->password);
        }

        $petugas->update($dataUpdate);

        return back()->with('success', "Data petugas {$petugas->name} berhasil diperbarui!");
    }

    // Hapus Akun Petugas Parkir
    public function deletePetugas($id)
    {
        $petugas = User::where('role', 'JUKIR')->findOrFail($id);
        $nama = $petugas->name;
        $petugas->delete();

        return back()->with('success', "Akun petugas {$nama} telah dihapus dari sistem.");
    }
}