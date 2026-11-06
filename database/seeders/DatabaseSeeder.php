<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat akun Juru Parkir untuk login uji coba
        User::create([
            'name' => 'Budi Jukir',
            'email' => 'budi@parkir.com',
            'password' => Hash::make('password123'), // Gunakan Hash::make
            'role' => 'JUKIR',
        ]);
        User::create([
            'name' => 'Anton Admin',
            'email' => 'anton@admin.com',
            'password' => Hash::make('password123'), // Gunakan Hash::make
            'role' => 'ADMIN',
        ]);

        // 2. Membuat data area lahan parkir awal
        Area::create([
            'nama_area' => 'Parkiran Fakultas Teknik',
            'kapasitas_total' => 20,
        ]);
        Area::create([
            'nama_area' => 'Parkiran Fakultas Ekonomi dan Bisnis',
            'kapasitas_total' => 20,
        ]);
        Area::create([
            'nama_area' => 'Parkiran Fakultas Ilmu Kedokteran',
            'kapasitas_total' => 20,
        ]);
        Area::create([
            'nama_area' => 'Parkiran Fakultas Keguruan dan Ilmu Pendidikan',
            'kapasitas_total' => 20,
        ]);
        Area::create([
            'nama_area' => 'Parkiran Fakultas Agama Islam',
            'kapasitas_total' => 20,
        ]);
        Area::create([
            'nama_area' => 'Parkiran Fakultas Pertanian',
            'kapasitas_total' => 20,
        ]);
        Area::create([
            'nama_area' => 'Parkiran Fakultas Ilmu Sosial dan Ilmu Politik',
            'kapasitas_total' => 20,
        ]);
    }
}
