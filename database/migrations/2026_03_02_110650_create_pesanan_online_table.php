<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_online', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_pelanggan')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('id_layanan')
                ->constrained('layanans')
                ->onDelete('cascade');

            $table->string('file_path');
            $table->string('nama_file');

            $table->integer('jumlah_rangkap')->default(1);

            $table->string('ukuran_kertas', 50);

            $table->text('catatan')->nullable();

            $table->enum('status', [
                'Menunggu',
                'Diproses',
                'Selesai',
                'Diambil'
            ])->default('Menunggu');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_online');
    }
};
