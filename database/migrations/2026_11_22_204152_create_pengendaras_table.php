<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengendaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('kategori', ['MAHASISWA', 'DOSEN'])->default('MAHASISWA');
            $table->string('nomor_identitas', 30)->nullable(); // NIM untuk Mahasiswa / NIP atau NIDN untuk Dosen
            $table->string('plat_nomor', 15)->unique();
            $table->enum('jenis_kendaraan', ['MOTOR', 'MOBIL'])->default('MOTOR');
            $table->string('qr_code_token', 30)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengendaras');
    }
};
