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
        Schema::create('pesanan_opsi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pesanan')->constrained('pesanans')->cascadeOnDelete();
            
            // Reference to the option, can be null if the option is deleted
            $table->foreignId('id_opsi_layanan')->nullable()->constrained('opsi_layanans')->nullOnDelete();
            
            // Snapshot fields in case the original option is modified or deleted
            $table->string('kategori', 50);
            $table->string('nama_opsi', 100);
            $table->integer('harga'); // Harga snapshot pada saat dipesan
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_opsi');
    }
};
