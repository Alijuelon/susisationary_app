<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// Route for Hardware Output (LCD)
Route::get('/queue/active', function () {
    $activeQueue = \App\Models\Transaksi::where('tipe_transaksi', 'Online')
        ->where('status', 'Diproses')
        ->orderBy('updated_at', 'desc')
        ->first();

    if ($activeQueue) {
        $queueNumber = \App\Models\Transaksi::where('tipe_transaksi', 'Online')
                ->whereIn('status', ['Menunggu', 'Diproses'])
                ->where('created_at', '<=', $activeQueue->created_at)
                ->count();
        
        return response()->json([
            'status' => 'success',
            'queue_number' => str_pad($queueNumber, 3, '0', STR_PAD_LEFT),
            'kode_transaksi' => $activeQueue->kode_transaksi
        ]);
    }
    
    return response()->json([
        'status' => 'empty',
        'queue_number' => '---',
        'kode_transaksi' => null
    ]);
});
