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
                            <span class="absolute -top-6 text-[10px] font-bold hidden group-hover:block bg-gray-800 dark:bg-slate-700 text-white px-1.5 py-0.5 rounded shadow z-10 w-max">
                                {{ $grafik['jumlah'] }} Trx
                            </span>
                            
                            <div class="w-full bg-blue-500 rounded-t-sm transition-all duration-300 hover:bg-blue-600 dark:hover:bg-blue-400" 
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
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Omzet kotor 7 hari terakhir</p>
                </div>

                <div class="w-full h-32 bg-gray-50 dark:bg-slate-800/50 rounded-lg flex items-end justify-between px-4 pb-2 pt-6 gap-3 border-b border-gray-200 dark:border-slate-700/50 transition-colors">
                    @foreach($revenueMingguan as $grafik)
                        <div class="w-full relative group flex flex-col justify-end items-center h-full">
                            <span class="absolute -top-6 text-[10px] font-bold hidden group-hover:block bg-gray-800 dark:bg-slate-700 text-white px-1.5 py-0.5 rounded shadow z-10 w-max">
                                Rp {{ number_format($grafik['jumlah'], 0, ',', '.') }}
                            </span>
                            
                            <div class="w-full bg-emerald-500 rounded-t-sm transition-all duration-300 hover:bg-emerald-600 dark:hover:bg-emerald-400" 
                                 style="height: {{ ($grafik['jumlah'] / $maxRevenue) * 100 }}%; min-height: 4px;">
                            </div>
                            
                            <span class="text-[9px] text-gray-400 dark:text-gray-500 mt-1 uppercase">{{ $grafik['hari'] }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-auto">
                    <hr class="my-3 border-gray-100 dark:border-slate-800">
                    <p class="text-xs text-gray-400 dark:text-gray-500"><i class="fa-solid fa-chart-line text-emerald-500 mr-1"></i> Omzet Harian</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm p-4 border border-gray-100 dark:border-slate-800 flex flex-col justify-between transition-colors">
                <div>
                    <h6 class="font-bold text-gray-800 dark:text-white text-base">Status Pesanan Cetak</h6>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Total: {{ $totalPesanan }} pesanan online</p>
                </div>

                <div class="w-full h-32 bg-gray-50 dark:bg-slate-800/50 rounded-lg flex items-end justify-between px-4 pb-2 pt-6 gap-2 border-b border-gray-200 dark:border-slate-700/50 transition-colors">
                     @php
                         $colors = [
                             'Menunggu' => 'bg-yellow-400',
                             'Diproses' => 'bg-blue-400',
                             'Siap Diambil' => 'bg-purple-400',
                             'Selesai' => 'bg-emerald-400',
                             'Dibatalkan' => 'bg-red-400'
                         ];
                         $total = $totalPesanan > 0 ? $totalPesanan : 1;
                     @endphp
                     @foreach($pesananPerStatus as $status => $jumlah)
                        <div class="w-full relative group flex flex-col justify-end items-center h-full">
                            <span class="absolute -top-6 text-[10px] font-bold hidden group-hover:block bg-gray-800 dark:bg-slate-700 text-white px-1.5 py-0.5 rounded shadow z-10 w-max">
                                {{ $jumlah }} {{ $status }}
                            </span>
                            
                            <div class="w-full {{ $colors[$status] ?? 'bg-gray-400' }} rounded-t-sm transition-all duration-300 hover:opacity-80" 
                                 style="height: {{ ($jumlah / $total) * 100 }}%; min-height: 4px;">
                            </div>
                            
                            <span class="text-[9px] text-gray-400 dark:text-gray-500 mt-1 truncate w-full text-center" title="{{ $status }}">{{ substr($status, 0, 4) }}.</span>
                        </div>
                     @endforeach
                </div>

                <div class="mt-auto">
                    <hr class="my-3 border-gray-100 dark:border-slate-800">
                    <p class="text-xs text-gray-400 dark:text-gray-500"><i class="fa-solid fa-print text-gray-500 mr-1"></i> Distribusi pesanan</p>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
            
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-800 transition-colors flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow border border-green-400/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-wallet text-sm"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mb-1">Pemasukan</p>
                        <h5 class="font-bold text-lg text-gray-800 dark:text-white tracking-tight">{{ number_format($pemasukanHariIni / 1000, 0, ',', '.') }}K</h5>
                    </div>
                </div>
                <div class="mt-auto pt-2 border-t border-gray-100 dark:border-slate-800">
                    <p class="text-[10px] text-gray-500 dark:text-gray-400"><i class="fa-solid fa-arrow-trend-up text-green-500 mr-1"></i> Hari ini</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-800 transition-colors flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl shadow border border-red-400/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-file-invoice-dollar text-sm"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mb-1">Pengeluaran</p>
                        <h5 class="font-bold text-lg text-gray-800 dark:text-white tracking-tight">{{ number_format($pengeluaranHariIni / 1000, 0, ',', '.') }}K</h5>
                    </div>
                </div>
                <div class="mt-auto pt-2 border-t border-gray-100 dark:border-slate-800">
                    <p class="text-[10px] text-gray-500 dark:text-gray-400"><i class="fa-solid fa-calculator text-red-500 mr-1"></i> Hari ini</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-800 transition-colors flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-fuchsia-600 rounded-xl shadow border border-purple-400/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-users text-sm"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mb-1">Pelanggan</p>
                        <h5 class="font-bold text-lg text-gray-800 dark:text-white tracking-tight">{{ number_format($totalPelanggan, 0, ',', '.') }}</h5>
                    </div>
                </div>
                <div class="mt-auto pt-2 border-t border-gray-100 dark:border-slate-800">
                    <p class="text-[10px] text-gray-500 dark:text-gray-400"><i class="fa-solid fa-user-plus text-purple-500 mr-1"></i> Terdaftar</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-800 transition-colors flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl shadow border border-amber-300/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-id-card-clip text-sm"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mb-1">Member</p>
                        <h5 class="font-bold text-lg text-gray-800 dark:text-white tracking-tight">{{ number_format($totalMemberAktif, 0, ',', '.') }}</h5>
                    </div>
                </div>
                <div class="mt-auto pt-2 border-t border-gray-100 dark:border-slate-800">
                    <p class="text-[10px] text-gray-500 dark:text-gray-400"><i class="fa-solid fa-check-circle text-amber-500 mr-1"></i> Member Aktif</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-slate-800 transition-colors flex flex-col justify-between">
                <div class="flex justify-between items-start mb-2">
                    <div class="w-10 h-10 {{ $stokMenipis > 0 ? 'bg-gradient-to-br from-red-500 to-red-600' : 'bg-gradient-to-br from-gray-700 to-slate-800' }} rounded-xl shadow flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-boxes-stacked text-sm"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider mb-1">Stok Tipis</p>
                        <h5 class="font-bold text-lg text-gray-800 dark:text-white tracking-tight">{{ $stokMenipis }}</h5>
                    </div>
                </div>
                <div class="mt-auto pt-2 border-t border-gray-100 dark:border-slate-800">
                    @if($stokMenipis > 0)
                        <p class="text-[10px] text-red-500 dark:text-red-400 font-medium"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Perlu restock</p>
                    @else
                        <p class="text-[10px] text-gray-400 dark:text-gray-500"><i class="fa-solid fa-check mr-1"></i> Stok aman</p>
                    @endif
                </div>
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