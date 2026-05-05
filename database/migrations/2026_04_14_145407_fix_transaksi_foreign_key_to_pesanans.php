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
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['id_pesanan_online']);
            $table->foreign('id_pesanan_online')->references('id')->on('pesanans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['id_pesanan_online']);
            $table->foreign('id_pesanan_online')->references('id')->on('pesanan_online')->onDelete('set null');
        });
    }
};
