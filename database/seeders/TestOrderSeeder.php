<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pesanan;
use App\Models\Membership;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TestOrderSeeder extends Seeder
{
    public function run()
    {
        $pelanggan = User::firstOrCreate(
            ['email' => 'pelanggan.test@susi.com'],
            [
                'nama_lengkap' => 'Pelanggan Test E2E',
                'password' => Hash::make('password123'),
                'role' => 'pelanggan',
                'no_telp' => '081234567890',
                'is_active' => true,
            ]
        );

        Membership::firstOrCreate(
            ['id_pelanggan' => $pelanggan->id],
            [
                'no_kartu' => 'MBR-999999',
                'status' => 'aktif',
                'tanggal_bergabung' => Carbon::now()
            ]
        );

        Pesanan::create([
            'id_pelanggan' => $pelanggan->id,
            'kode_pesanan' => 'ORD-E2E-TEST',
            'detail_layanan' => 'Cetak E2E Test',
            'harga_perkiraan' => 15000,
            'qty' => 5,
            'status' => 'Siap Diambil'
        ]);
        
        $kasir = User::where('role', 'kasir')->first();
        if(migratekasir) {
             User::create([
                'nama_lengkap' => 'Kasir Test',
                'email' => 'kasir@susi.com',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
                'is_active' => true,
             ]);
        }
    }
}
