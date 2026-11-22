<?php

namespace App\Http\Controllers;

use App\Models\Pengendara;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'kategori'        => 'required|in:MAHASISWA,DOSEN',
            'nomor_identitas' => 'nullable|string|max:30',
            'plat_nomor'      => [
                'required',
                'string',
                'max:12',
                'unique:pengendaras,plat_nomor',
                'regex:/^[A-Za-z]{1,2}\s?[1-9][0-9]{0,3}\s?[A-Za-z]{1,3}$/'
            ],
            'jenis_kendaraan' => 'required|in:MOTOR,MOBIL',
        ]);

        $platFormatted = strtoupper(str_replace(' ', '', trim($request->plat_nomor)));

        $user = DB::transaction(function () use ($request, $platFormatted) {
            $newUser = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'PENGENDARA',
            ]);

            Pengendara::create([
                'user_id'         => $newUser->id,
                'kategori'        => $request->kategori,
                'nomor_identitas' => $request->nomor_identitas,
                'plat_nomor'      => $platFormatted,
                'jenis_kendaraan' => $request->jenis_kendaraan,
            ]);

            return $newUser;
        });

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
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'JUKIR',
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