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
        Schema::create('opsi_layanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_layanan')->constrained('layanans')->cascadeOnDelete();
            $table->string('kategori', 50); // Ukuran, Jenis Kertas, Warna Cetak, Sisi Cetak, Finishing
            $table->string('nama_opsi', 100);
            $table->integer('harga'); // Harga bisa 0 (misal Hitam Putih 0, Warna +500)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opsi_layanans');
    }
};
