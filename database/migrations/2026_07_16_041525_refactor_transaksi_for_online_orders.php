<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Transaksi table alterations
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('tipe_transaksi', 50)->default('POS')->after('id');
            $table->string('metode_pembayaran', 50)->default('Cash')->after('kembalian');
        });

        // Use raw statement to modify enum and nullability
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN status VARCHAR(50) DEFAULT 'Berhasil'");
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN id_kasir BIGINT UNSIGNED NULL");
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN uang_bayar DECIMAL(15,2) DEFAULT 0");
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN kembalian DECIMAL(15,2) DEFAULT 0");

        // 2. Detail Transaksi alterations
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->string('nama_item', 255)->nullable()->after('id_item');
            $table->string('file_dokumen')->nullable()->after('subtotal');
            $table->text('catatan')->nullable()->after('file_dokumen');
        });

        // 3. Drop pesanan_opsi if exists and create detail_transaksi_opsi
        Schema::dropIfExists('pesanan_opsi');
        
        Schema::create('detail_transaksi_opsi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_detail_transaksi')->constrained('detail_transaksi')->cascadeOnDelete();
            $table->foreignId('id_opsi_layanan')->nullable()->constrained('opsi_layanans')->nullOnDelete();
            $table->string('kategori', 50);
            $table->string('nama_opsi', 100);
            $table->integer('harga');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi_opsi');

        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->dropColumn(['nama_item', 'file_dokumen', 'catatan']);
        });

        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['tipe_transaksi', 'metode_pembayaran']);
        });
        
        DB::statement("ALTER TABLE transaksi MODIFY COLUMN id_kasir BIGINT UNSIGNED NOT NULL");
    }
};
