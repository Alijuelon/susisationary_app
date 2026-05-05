<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'memberships';

    protected $fillable = [
        'id_pelanggan',
        'no_kartu',
        'status',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    /**
     * Relasi ke User (pelanggan pemilik membership)
     */
    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'id_pelanggan');
    }

    /**
     * Relasi ke User (kasir/admin yang memproses)
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Generate nomor kartu unik format MBR-XXXXXX
     */
    public static function generateNoKartu(): string
    {
        do {
            $noKartu = 'MBR-' . strtoupper(\Illuminate\Support\Str::random(6));
        } while (self::where('no_kartu', $noKartu)->exists());

        return $noKartu;
    }
}
