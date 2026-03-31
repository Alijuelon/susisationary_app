<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_pelanggan')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('id_layanan')
                ->constrained('layanans') // WAJIB tulis ini
                ->cascadeOnDelete();

            $table->string('file_dokumen');

            $table->text('catatan')->nullable();

            $table->enum('status', [
                'Menunggu',
                'Diproses',
                'Siap Diambil',
                'Selesai',
                'Dibatalkan'
            ])->default('Menunggu');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
