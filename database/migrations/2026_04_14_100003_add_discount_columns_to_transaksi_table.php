<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->foreignId('id_membership')
                ->nullable()
                ->after('id_pesanan_online')
                ->constrained('memberships')
                ->nullOnDelete();

            $table->decimal('diskon_persen', 5, 2)->default(0)->after('id_membership');
            $table->decimal('total_sebelum_diskon', 15, 2)->nullable()->after('diskon_persen');
            $table->string('nama_pelanggan')->nullable()->after('total_sebelum_diskon');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['id_membership']);
            $table->dropColumn(['id_membership', 'diskon_persen', 'total_sebelum_diskon', 'nama_pelanggan']);
        });
    }
};
