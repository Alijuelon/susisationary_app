<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
    $table->id(); 

    // kode transaksi untuk format TRX-0001
    $table->string('kode_transaksi')->unique();

    $table->foreignId('id_kasir')->constrained('users')->onDelete('cascade');
    $table->foreignId('id_pelanggan')->nullable()->constrained('users')->onDelete('set null');
    $table->unsignedBigInteger('id_pesanan_online')->nullable();
    $table->foreign('id_pesanan_online')->references('id')->on('pesanans')->onDelete('set null');

    $table->decimal('total_harga', 15, 2);
    $table->decimal('uang_bayar', 15, 2);
    $table->decimal('kembalian', 15, 2);

    $table->enum('status', ['Berhasil', 'Dibatalkan'])->default('Berhasil');

    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};