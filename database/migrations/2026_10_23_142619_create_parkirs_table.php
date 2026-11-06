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
            $table->string('plat_nomor');
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            
            // Tambahkan relasi ke tabel users. Kolom ini bisa bernilai kosong (nullable) 
            // karena jika pengendara masuk MANDIRI, tidak ada jukir yang mencatatnya.
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamp('waktu_keluar')->nullable();
            $table->enum('status', ['AKTIF', 'SELESAI'])->default('AKTIF');
            $table->enum('input_oleh', ['MANDIRI', 'JUKIR'])->default('MANDIRI');
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
