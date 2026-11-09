<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parkirs', function (Blueprint $table) {
            $table->id();
    
            // Mengacu ke Akun Pengendara
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Snapshot data saat transaksi parkir berlangsung
            $table->string('plat_nomor');
            $table->string('jenis_kendaraan')->default('MOTOR');
            $table->string('kategori_pengguna')->default('MAHASISWA');
            
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->integer('nomor_slot')->nullable(); // Nomor Slot (misal: 4 untuk FT004)
            
            $table->timestamp('waktu_keluar')->nullable();
            $table->enum('status', ['AKTIF', 'SELESAI'])->default('AKTIF');
            $table->enum('input_oleh', ['MANDIRI', 'JUKIR', 'GATE'])->default('MANDIRI');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parkirs');
    }
};
