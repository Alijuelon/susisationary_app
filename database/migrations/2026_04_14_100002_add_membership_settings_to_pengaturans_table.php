<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->boolean('membership_aktif')->default(false)->after('pesan_penutup');
            $table->decimal('diskon_member', 5, 2)->default(0)->after('membership_aktif');
        });
    }

    public function down(): void
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->dropColumn(['membership_aktif', 'diskon_member']);
        });
    }
};
