<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Pengendara;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. AKUN ADMIN
        User::create([
            'name'     => 'Admin E-Parkir UNSIL',
            'email'    => 'admin@unsil.ac.id',
            'password' => Hash::make('password123'),
            'role'     => 'ADMIN',
        ]);

        // 2. AKUN PETUGAS JURU PARKIR (JUKIR)
        User::create([
            'name'     => 'Kang Asep (Jukir FT)',
            'email'    => 'jukir@unsil.ac.id',
            'password' => Hash::make('password123'),
            'role'     => 'JUKIR',
        ]);

        // 3. AKUN PENGENDARA: MAHASISWA
        $mhsUser = User::create([
            'name'     => 'Rizky Pratama',
            'email'    => 'mahasiswa@student.unsil.ac.id',
            'password' => Hash::make('password123'),
            'role'     => 'PENGENDARA',
        ]);

        Pengendara::create([
            'user_id'         => $mhsUser->id,
            'kategori'        => 'MAHASISWA',
            'nomor_identitas' => '227006045',
            'plat_nomor'      => 'Z4521HB',
            'jenis_kendaraan' => 'MOTOR',
        ]);

        // 4. AKUN PENGENDARA: DOSEN
        $dosenUser = User::create([
            'name'     => 'Dr. Hendra Gunawan, M.T.',
            'email'    => 'dosen@unsil.ac.id',
            'password' => Hash::make('password123'),
            'role'     => 'PENGENDARA',
        ]);

        Pengendara::create([
            'user_id'         => $dosenUser->id,
            'kategori'        => 'DOSEN',
            'nomor_identitas' => '198405122014041002',
            'plat_nomor'      => 'Z1024AA',
            'jenis_kendaraan' => 'MOBIL',
        ]);


        // 2. MASTER LAHAN PARKIR KAMPUS 1 (KAHURIPAN)
        Area::create([
            'nama_area'       => 'Kampus 1 - Gedung Parkir Utama',
            'kode_area'       => 'K1-GP',
            'kapasitas_total' => 200,
        ]);

        Area::create([
            'nama_area'       => 'Kampus 1 - Fakultas Ekonomi & Bisnis (FEB)',
            'kode_area'       => 'FEB',
            'kapasitas_total' => 50,
        ]);

        Area::create([
            'nama_area'       => 'Kampus 1 - Fakultas Ilmu Kesehatan (FIK)',
            'kode_area'       => 'FIK',
            'kapasitas_total' => 40,
        ]);

        Area::create([
            'nama_area'       => 'Kampus 1 - Fakultas Keguruan & Ilmu Pendidikan (FKIP)',
            'kode_area'       => 'FKIP',
            'kapasitas_total' => 60,
        ]);

        Area::create([
            'nama_area'       => 'Kampus 1 - Fakultas Agama Islam (FAI)',
            'kode_area'       => 'FAI',
            'kapasitas_total' => 30,
        ]);

        Area::create([
            'nama_area'       => 'Kampus 1 - Fakultas Ilmu Sosial & Ilmu Politik (FISIP)',
            'kode_area'       => 'FISIP',
            'kapasitas_total' => 40,
        ]);

        Area::create([
            'nama_area'       => 'Kampus 1 - Area Masjid Al-Hidayah',
            'kode_area'       => 'K1-MSJ',
            'kapasitas_total' => 25,
        ]);


        // 3. MASTER LAHAN PARKIR KAMPUS 2 (TAMANSARI)
        Area::create([
            'nama_area'       => 'Kampus 2 - Fakultas Teknik (FT)',
            'kode_area'       => 'FT',
            'kapasitas_total' => 150,
        ]);

        Area::create([
            'nama_area'       => 'Kampus 2 - Fakultas Pertanian (FP)',
            'kode_area'       => 'FP',
            'kapasitas_total' => 80,
        ]);

        Area::create([
            'nama_area'       => 'Kampus 2 - Gedung Rektorat',
            'kode_area'       => 'K2-REK',
            'kapasitas_total' => 50,
        ]);

        Area::create([
            'nama_area'       => 'Kampus 2 - Area Masjid Kampus 2',
            'kode_area'       => 'K2-MSJ',
            'kapasitas_total' => 30,
        ]);
    }
}