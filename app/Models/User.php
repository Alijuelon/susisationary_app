<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nama_lengkap',
        'username',
        'password',
        'email',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi: Pelanggan punya banyak pesanan online
    public function pesananOnline()
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan');
    }

    // Relasi: Kasir melayani banyak transaksi
    public function transaksiKasir()
    {
        return $this->hasMany(Transaksi::class, 'id_kasir');
    }

    // Relasi: Pelanggan melakukan banyak transaksi
    public function transaksiPelanggan()
    {
        return $this->hasMany(Transaksi::class, 'id_pelanggan');
    }

    // Relasi: Admin mencatat banyak pengeluaran
    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_admin');
    }
}
