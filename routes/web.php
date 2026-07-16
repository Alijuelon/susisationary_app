<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Import Controllers Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\PengeluaranController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\MembershipController as AdminMembershipController;

// Import Controllers Kasir
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\Kasir\PesananMasukController;
use App\Http\Controllers\Kasir\RiwayatController;
use App\Http\Controllers\Kasir\PengaturanController;
use App\Http\Controllers\Kasir\MembershipKasirController;

// Import Controllers Pelanggan
use App\Http\Controllers\Pelanggan\PelangganDashboardController;
use App\Http\Controllers\Pelanggan\PesananOnlineController;
use App\Http\Controllers\Pelanggan\MembershipController as PelangganMembershipController;

use App\Models\Pengaturan;
use App\Models\Layanan;
use App\Models\Barang;
use App\Models\Membership;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // Ambil data pengaturan toko (nama, alamat, dll)
    $toko = Pengaturan::first();
    
    // Ambil maksimal 6 layanan untuk dipajang
    $layanan = Layanan::take(6)->get();
    
    // Ambil maksimal 8 barang yang stoknya masih ada secara acak
    $barang = Barang::where('stok', '>', 0)->inRandomOrder()->take(8)->get();

    return view('welcome', compact('toko', 'layanan', 'barang'));
});
// Smart Dashboard Redirector
// Rute ini menangani default redirect setelah login dari Laravel Breeze/Jetstream
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    
    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'kasir') {
        return redirect()->route('kasir.dashboard');
    } elseif ($role === 'pelanggan') {
        return redirect()->route('pelanggan.dashboard');
    }
    
    abort(403, 'Unauthorized action.');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute Profil Bawaan Laravel
Route::middleware('auth')->group(function () {
// Rute Global Update Profil & Password
    Route::put('/update-profil-global', [ProfileController::class, 'updateGlobal'])->name('profile.update.global');
});

/*
|--------------------------------------------------------------------------
| 1. RUTE ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // API Cek Pesanan Baru
    Route::get('/api/cek-pesanan', function () {
        $count = \App\Models\Transaksi::where('tipe_transaksi', 'Online')->where('status', 'Menunggu')->count();
        return response()->json(['count' => $count]);
    })->name('api.cek-pesanan');

    // Kelola Master Data
    Route::delete('/barang/bulk', [BarangController::class, 'destroyBulk'])->name('barang.destroyBulk');
    Route::resource('/barang', BarangController::class); // Menghasilkan route index, create, store, edit, update, destroy
    Route::delete('/layanan/bulk', [LayananController::class, 'destroyBulk'])->name('layanan.destroyBulk');
    Route::resource('layanan', LayananController::class);
    
    // Revisi 1: Kelola Pengeluaran
    Route::delete('/pengeluaran/bulk', [PengeluaranController::class, 'destroyBulk'])->name('pengeluaran.destroyBulk');
    Route::resource('pengeluaran', PengeluaranController::class)->except(['create', 'edit', 'show']);
    
    // Laporan Pendapatan & Pengeluaran
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetakPdf'])->name('laporan.cetak');

    // Kelola Pengguna
    Route::delete('/users/bulk', [UserManagementController::class, 'destroyBulk'])->name('users.destroyBulk');
    Route::resource('users', UserManagementController::class)->except(['show', 'create']);
    Route::patch('users/{id}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');

    // Kelola Keanggotaan (Membership)
    Route::delete('/membership/bulk', [AdminMembershipController::class, 'destroyBulk'])->name('membership.destroyBulk');
    Route::get('/membership', [AdminMembershipController::class, 'index'])->name('membership.index');
    Route::get('/membership/pengaturan', [AdminMembershipController::class, 'settings'])->name('membership.settings');
    Route::post('/membership/pengaturan', [AdminMembershipController::class, 'updateSettings'])->name('membership.update_settings');
    Route::patch('/membership/{id}/approve', [AdminMembershipController::class, 'approve'])->name('membership.approve');
    Route::patch('/membership/{id}/reject', [AdminMembershipController::class, 'reject'])->name('membership.reject');
    Route::delete('/membership/{id}', [AdminMembershipController::class, 'destroy'])->name('membership.destroy');

    // Kelola Antrean Pesanan Online (Admin)
    Route::get('/pesanan-masuk', [\App\Http\Controllers\Admin\PesananMasukController::class, 'index'])->name('pesanan.masuk');
    Route::patch('/pesanan-masuk/{id}/status', [\App\Http\Controllers\Admin\PesananMasukController::class, 'updateStatus'])->name('pesanan.updateStatus');
    Route::delete('/pesanan-masuk/bulk', [\App\Http\Controllers\Admin\PesananMasukController::class, 'destroyBulk'])->name('pesanan.destroyBulk');
    Route::delete('/pesanan-masuk/{id}', [\App\Http\Controllers\Admin\PesananMasukController::class, 'destroy'])->name('pesanan.destroy');

});

/*
|--------------------------------------------------------------------------
| 2. RUTE KASIR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    
    // Dashboard Kasir
    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');
    
    // Input Transaksi (POS) & Revisi 2 (Struk)
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/bayar', [PosController::class, 'store'])->name('pos.store');
    Route::get('/pos/struk/{id}', [PosController::class, 'cetakStruk'])->name('pos.struk');
    
    // API: Cari Member untuk POS
    Route::get('/pos/cari-member', function (\Illuminate\Http\Request $request) {
        $q = $request->input('q', '');
        $membership = Membership::with('pelanggan')
            ->where('status', 'aktif')
            ->where(function ($query) use ($q) {
                $query->where('no_kartu', 'like', "%{$q}%")
                      ->orWhereHas('pelanggan', function ($q2) use ($q) {
                          $q2->where('nama_lengkap', 'like', "%{$q}%");
                      });
            })
            ->first();
        
        if ($membership) {
            return response()->json(['found' => true, 'membership' => $membership]);
        }
        return response()->json(['found' => false, 'message' => 'Member tidak ditemukan atau belum aktif.']);
    })->name('pos.cari-member');

    // Kelola Antrean Pesanan Online
    Route::get('/pesanan-masuk', [PesananMasukController::class, 'index'])->name('pesanan.masuk');
    Route::patch('/pesanan-masuk/{id}/status', [PesananMasukController::class, 'updateStatus'])->name('pesanan.updateStatus');
    Route::delete('/pesanan-masuk/bulk', [PesananMasukController::class, 'destroyBulk'])->name('pesanan.destroyBulk');
    Route::delete('/pesanan-masuk/{id}', [PesananMasukController::class, 'destroy'])->name('pesanan.destroy');
    
    // Riwayat Transaksi Kasir
    Route::get('/riwayat-transaksi', [RiwayatController::class, 'index'])->name('riwayat');
    Route::delete('/riwayat-transaksi/bulk', [RiwayatController::class, 'destroyBulk'])->name('riwayat.destroyBulk');
    Route::delete('/riwayat-transaksi/{id}', [RiwayatController::class, 'destroy'])->name('riwayat.destroy');

    // Kelola Pengaturan Struk
    Route::get('/pengaturan-struk', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan-struk', [PengaturanController::class, 'update'])->name('pengaturan.update');

    // Kelola Keanggotaan (Membership)
    Route::delete('membership/bulk', [MembershipKasirController::class, 'destroyBulk'])->name('membership.destroyBulk');
    Route::get('membership', [MembershipKasirController::class, 'index'])->name('membership.index');
    Route::patch('membership/{id}/approve', [MembershipKasirController::class, 'approve'])->name('membership.approve');
    Route::delete('membership/{id}', [MembershipKasirController::class, 'destroy'])->name('membership.destroy');

    // Sistem Antrian Online (Kasir)

});

/*
|--------------------------------------------------------------------------
| 3. RUTE PELANGGAN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    
    // Dashboard Pelanggan (Termasuk Revisi 3: Notifikasi status pesanan)
    Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');
    // Upload & Pemesanan Layanan Cetak Online
    Route::get('/pesanan/baru', [PesananOnlineController::class, 'create'])->name('pesanan.create');
    Route::post('/pesanan/baru', [PesananOnlineController::class, 'store'])->name('pesanan.store');
    
    // Riwayat Transaksi Saya & Download PDF
    Route::get('/riwayat', [PesananOnlineController::class, 'riwayat'])->name('riwayat');
    Route::delete('/riwayat/bulk', [PesananOnlineController::class, 'destroyBulk'])->name('pesanan.destroyBulk');
    Route::delete('/riwayat/{id}', [PesananOnlineController::class, 'destroy'])->name('pesanan.destroy');
    Route::get('/riwayat/{id}/download', [PesananOnlineController::class, 'downloadStruk'])->name('riwayat.download');

    // Keanggotaan Pelanggan
    Route::get('/membership', [PelangganMembershipController::class, 'index'])->name('membership.index');
    Route::post('/membership', [PelangganMembershipController::class, 'store'])->name('membership.store');

    // Sistem Antrian Online (Pelanggan)


});

require __DIR__.'/auth.php';