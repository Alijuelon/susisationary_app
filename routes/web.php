<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Import Controllers Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\PengeluaranController;
use App\Http\Controllers\Admin\LaporanController;

// Import Controllers Kasir
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\Kasir\PesananMasukController;
use App\Http\Controllers\Kasir\RiwayatController; // <-- Controller baru ditambahkan
use App\Http\Controllers\Kasir\PengaturanController;

// Import Controllers Pelanggan
use App\Http\Controllers\Pelanggan\PelangganDashboardController;
use App\Http\Controllers\Pelanggan\PesananOnlineController;
use App\Models\Pengaturan;
use App\Models\Layanan;
use App\Models\Barang;


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
    
    // Kelola Master Data
    Route::resource('barang', BarangController::class); // Menghasilkan route index, create, store, edit, update, destroy
    Route::resource('layanan', LayananController::class);
    
    // Revisi 1: Kelola Pengeluaran
    Route::resource('pengeluaran', PengeluaranController::class);
    
    // Laporan Pendapatan & Pengeluaran
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetakPdf'])->name('laporan.cetak');

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
    Route::get('/pos/struk/{id}', [PosController::class, 'cetakStruk'])->name('pos.struk'); // URL untuk pop-up / cetak struk
    
    // Kelola Antrean Pesanan Online
    Route::get('/pesanan-masuk', [PesananMasukController::class, 'index'])->name('pesanan.masuk');
    Route::patch('/pesanan-masuk/{id}/status', [PesananMasukController::class, 'updateStatus'])->name('pesanan.updateStatus');
    
    // Riwayat Transaksi Kasir (Diperbarui agar tidak error undefined method)
    Route::get('/riwayat-transaksi', [RiwayatController::class, 'index'])->name('riwayat');

    // Kelola Pengaturan Struk
    Route::get('/pengaturan-struk', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan-struk', [PengaturanController::class, 'update'])->name('pengaturan.update');
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
    Route::get('/riwayat/{id}/download', [PesananOnlineController::class, 'downloadStruk'])->name('riwayat.download');

});

require __DIR__.'/auth.php';