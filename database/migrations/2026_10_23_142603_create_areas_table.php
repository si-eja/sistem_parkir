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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_area');       // Contoh: "Fakultas Teknik"
            $table->string('kode_area');       // Contoh: "FT", "FEB", "FKIP" (Digunakan untuk prefix slot: FT001)
            $table->integer('kapasitas_total')->default(100); // Admin bisa ubah misal dari 100 ke 200 tanpa ubah struktur DB
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
