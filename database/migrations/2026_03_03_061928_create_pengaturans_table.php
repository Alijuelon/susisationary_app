<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko')->default('SUSI STATIONARY');
            $table->text('alamat')->nullable();
            $table->string('no_telp')->nullable();
            $table->text('pesan_penutup')->nullable();
            $table->boolean('membership_aktif')->default(false);
            $table->decimal('diskon_member', 5, 2)->default(0);
            $table->boolean('antrian_aktif')->default(true);
            $table->integer('waktu_proses_antrian')->default(5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};