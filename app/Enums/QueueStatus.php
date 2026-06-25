<?php

namespace App\Enums;

enum QueueStatus: string
{
    case MENUNGGU = 'menunggu';
    case DIPROSES = 'diproses';
    case SELESAI = 'selesai';
    case DIBATALKAN = 'dibatalkan';

    public function label(): string
    {
        return match($this) {
            self::MENUNGGU => 'Menunggu',
            self::DIPROSES => 'Diproses',
            self::SELESAI => 'Selesai',
            self::DIBATALKAN => 'Dibatalkan',
        };
    }
}
