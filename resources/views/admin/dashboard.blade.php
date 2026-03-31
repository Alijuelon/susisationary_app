<x-app-layout>
    <x-slot name="header">Analytics Overview</x-slot>

    <div class="w-full">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 transition-colors">Pantau ringkasan pendapatan, performa penjualan, dan stok barang Susi Stationary.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm p-4 border border-gray-100 dark:border-slate-800 flex flex-col justify-between transition-colors">
                <div>
                    <h6 class="font-bold text-gray-800 dark:text-white text-base">Transaksi Mingguan</h6>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Jumlah transaksi 7 hari terakhir</p>
                </div>
                
                <div class="w-full h-32 bg-gray-50 dark:bg-slate-800/50 rounded-lg flex items-end justify-between px-4 pb-2 pt-6 gap-3 border-b border-gray-200 dark:border-slate-700/50 transition-colors">
                    @foreach($transaksiMingguan as $grafik)
                        <div class="w-full relative group flex flex-col justify-end items-center h-full">
                            <span class="absolute -top-6 text-[10px] font-bold hidden group-hover:block bg-gray-800 dark:bg-slate-700 text-white px-1.5 py-0.5 rounded shadow z-10">
                                {{ $grafik['jumlah'] }}
                            </span>
                            
                            <div class="w-full bg-green-500 rounded-t-sm transition-all duration-300 hover:bg-green-600 dark:hover:bg-green-400" 
                                 style="height: {{ ($grafik['jumlah'] / $maxTransaksi) * 100 }}%; min-height: 4px;">
                            </div>
                            
                            <span class="text-[9px] text-gray-400 dark:text-gray-500 mt-1 uppercase">{{ $grafik['hari'] }}</span>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-auto">
                    <hr class="my-3 border-gray-100 dark:border-slate-800">
                    <p class="text-xs text-gray-400 dark:text-gray-500"><i class="fa-regular fa-clock mr-1"></i> Diperbarui otomatis</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm p-4 border border-gray-100 dark:border-slate-800 flex flex-col justify-between transition-colors">
                <div>
                    <h6 class="font-bold text-gray-800 dark:text-white text-base">Tren Pendapatan</h6>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Pergerakan transaksi harian</p>
                </div>
                <div class="w-full h-32 bg-gray-50 dark:bg-slate-800/50 rounded-lg flex items-center justify-center border-b border-gray-200 dark:border-slate-700/50 transition-colors">
                    <i class="fa-solid fa-chart-line text-5xl text-green-500 opacity-80"></i>
                </div>
                <div class="mt-auto">
                    <hr class="my-3 border-gray-100 dark:border-slate-800">
                    <p class="text-xs text-gray-400 dark:text-gray-500"><i class="fa-regular fa-clock mr-1"></i> Sistem Kasir Aktif</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm p-4 border border-gray-100 dark:border-slate-800 flex flex-col justify-between transition-colors">
                <div>
                    <h6 class="font-bold text-gray-800 dark:text-white text-base">Pesanan Online</h6>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Performa pesanan cetak digital</p>
                </div>
                <div class="w-full h-32 bg-gray-50 dark:bg-slate-800/50 rounded-lg flex items-center justify-center border-b border-gray-200 dark:border-slate-700/50 transition-colors">
                    <i class="fa-solid fa-print text-5xl text-gray-800 dark:text-gray-300 opacity-80"></i>
                </div>
                <div class="mt-auto">
                    <hr class="my-3 border-gray-100 dark:border-slate-800">
                    <p class="text-xs text-gray-400 dark:text-gray-500"><i class="fa-solid fa-check-circle text-green-500 mr-1"></i> Terhubung dengan pelanggan</p>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-800 transition-colors">
                <div class="flex justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium capitalize mb-1">Pemasukan Hari Ini</p>
                        <h5 class="font-bold text-2xl text-gray-800 dark:text-white tracking-tight">Rp {{ number_format($pemasukanHariIni, 0, ',', '.') }}</h5>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-800 to-gray-900 dark:from-slate-700 dark:to-slate-800 rounded-xl shadow-md flex items-center justify-center text-white">
                        <i class="fa-solid fa-wallet text-xl"></i>
                    </div>
                </div>
                <hr class="border-gray-100 dark:border-slate-800 my-4">
                <p class="text-sm text-gray-500 dark:text-gray-400"><i class="fa-solid fa-chart-bar text-green-500 mr-1"></i> Transaksi Kasir</p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-800 transition-colors">
                <div class="flex justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium capitalize mb-1">Pengeluaran Hari Ini</p>
                        <h5 class="font-bold text-2xl text-gray-800 dark:text-white tracking-tight">Rp {{ number_format($pengeluaranHariIni, 0, ',', '.') }}</h5>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-800 to-gray-900 dark:from-slate-700 dark:to-slate-800 rounded-xl shadow-md flex items-center justify-center text-white">
                        <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                    </div>
                </div>
                <hr class="border-gray-100 dark:border-slate-800 my-4">
                <p class="text-sm text-gray-500 dark:text-gray-400"><i class="fa-solid fa-calculator text-red-500 mr-1"></i> Biaya Operasional</p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-800 transition-colors">
                <div class="flex justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium capitalize mb-1">Total Pelanggan</p>
                        <h5 class="font-bold text-2xl text-gray-800 dark:text-white tracking-tight">{{ number_format($totalPelanggan, 0, ',', '.') }}</h5>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-800 to-gray-900 dark:from-slate-700 dark:to-slate-800 rounded-xl shadow-md flex items-center justify-center text-white">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                </div>
                <hr class="border-gray-100 dark:border-slate-800 my-4">
                <p class="text-sm text-gray-500 dark:text-gray-400"><i class="fa-solid fa-user-plus text-green-500 mr-1"></i> Pelanggan Terdaftar</p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-800 transition-colors">
                <div class="flex justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium capitalize mb-1">Item Stok Menipis</p>
                        <h5 class="font-bold text-2xl text-gray-800 dark:text-white tracking-tight">{{ $stokMenipis }} Item</h5>
                    </div>
                    <div class="w-12 h-12 {{ $stokMenipis > 0 ? 'bg-gradient-to-br from-red-500 to-red-600' : 'bg-gradient-to-br from-gray-800 to-gray-900 dark:from-slate-700 dark:to-slate-800' }} rounded-xl shadow-md flex items-center justify-center text-white">
                        <i class="fa-solid fa-boxes-stacked text-xl"></i>
                    </div>
                </div>
                <hr class="border-gray-100 dark:border-slate-800 my-4">
                @if($stokMenipis > 0)
                    <p class="text-sm text-red-500 dark:text-red-400 font-medium"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Segera lakukan restock</p>
                @else
                    <p class="text-sm text-green-500 dark:text-green-400 font-medium"><i class="fa-solid fa-check mr-1"></i> Stok aman terkendali</p>
                @endif
            </div>

        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center transition-colors">
                <div>
                    <h6 class="font-bold text-gray-800 dark:text-white text-base">Transaksi Terbaru</h6>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><i class="fa-solid fa-check text-green-500 mr-1"></i> {{ $totalTransaksiHariIni }} Transaksi berhasil hari ini</p>
                </div>
                <a href="{{ route('admin.laporan.index') }}" class="text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">Lihat Laporan Lengkap</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold">ID Transaksi</th>
                            <th class="px-6 py-4 font-semibold">Kasir (Operator)</th>
                            <th class="px-6 py-4 font-semibold">Total Bayar</th>
                            <th class="px-6 py-4 font-semibold">Waktu</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-slate-800/80">
                        
                        @forelse($transaksiTerbaru as $trx)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">{{ $trx->id }}</td>
                                <td class="px-6 py-4">{{ $trx->kasir ? $trx->kasir->nama_lengkap : 'Sistem' }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y - H:i WIB') }}</td>
                                <td class="px-6 py-4">
                                    @if($trx->status === 'Berhasil')
                                        <span class="bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors">Berhasil</span>
                                    @else
                                        <span class="bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors">{{ $trx->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-receipt text-3xl mb-3 text-gray-300 dark:text-slate-600"></i>
                                        <p>Belum ada data transaksi yang tercatat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>