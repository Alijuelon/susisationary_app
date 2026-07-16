<x-app-layout>
    <x-slot name="header">Dashboard Kasir</x-slot>

    <div class="w-full">
        
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-lg p-6 sm:p-8 mb-6 text-white flex flex-col md:flex-row items-center justify-between relative overflow-hidden transition-colors">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-5"></div>
            <div class="absolute bottom-0 right-32 -mb-12 w-32 h-32 rounded-full bg-white opacity-5"></div>
            
            <div class="z-10 text-center md:text-left mb-6 md:mb-0">
                <p class="text-gray-300 dark:text-gray-400 font-medium mb-1">Shift Hari Ini</p>
                <h2 class="text-2xl sm:text-3xl font-bold">Selamat bekerja, {{ explode(' ', Auth::user()->nama_lengkap)[0] }}! 🚀</h2>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">Pastikan teliti dalam menginput pesanan pelanggan.</p>
            </div>
            
            <div class="z-10 w-full md:w-auto">
                <a href="{{ route('kasir.pos.index') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-blue-900/50 dark:shadow-none transition-transform transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-cash-register mr-2"></i> Buka Sistem POS
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-800 flex items-center transition-colors">
                <div class="w-14 h-14 bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 rounded-xl flex items-center justify-center text-2xl font-bold mr-4 transition-colors">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Setoran Anda Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white tracking-tight">Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-800 flex items-center transition-colors">
                <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-2xl font-bold mr-4 transition-colors">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Pelanggan Dilayani</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white tracking-tight">{{ $totalTransaksi ?? 0 }} <span class="text-base font-normal text-gray-500 dark:text-gray-400">Transaksi</span></h3>
                </div>
            </div>
        </div>



        <!-- Antrian Pesanan Online -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors mb-6">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 flex flex-col sm:flex-row justify-between sm:items-center transition-colors bg-gradient-to-r from-blue-50 to-white dark:from-slate-800 dark:to-slate-900">
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center mr-3 shadow-sm relative shrink-0">
                        <i class="fa-solid fa-cloud-arrow-down"></i>
                        @if(count($antrianPesananOnline) > 0)
                        <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white dark:border-slate-900 rounded-full animate-pulse"></span>
                        @endif
                    </div>
                    <div>
                        <h6 class="font-bold text-gray-800 dark:text-white text-base">Antrian Pesanan Online</h6>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Daftar pesanan dari pelanggan online yang masuk.</p>
                    </div>
                </div>
                
                <div class="flex space-x-3 w-full sm:w-auto">
                    <a href="{{ route('kasir.pesanan.masuk') }}" class="flex-1 sm:flex-none px-4 py-2.5 bg-blue-600 text-white hover:bg-blue-700 font-bold rounded-xl transition-colors flex items-center justify-center text-sm shadow-sm">
                        <i class="fa-solid fa-list mr-2"></i> Kelola Semua
                    </a>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold">Kode Transaksi</th>
                            <th class="px-6 py-4 font-semibold">Pelanggan</th>
                            <th class="px-6 py-4 font-semibold">Waktu Masuk</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-slate-800/80">
                        @forelse($antrianPesananOnline as $trx)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors {{ $trx->status === 'Diproses' ? 'bg-blue-50/30 dark:bg-blue-900/10' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="font-black text-sm {{ $trx->status === 'Diproses' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-800 dark:text-white' }}">
                                        {{ $trx->kode_transaksi }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">
                                    {{ $trx->pelanggan->nama_lengkap ?? $trx->nama_pelanggan }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fa-regular fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($trx->created_at)->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($trx->status === 'Menunggu')
                                        <span class="bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors inline-flex items-center">
                                            Menunggu
                                        </span>
                                    @elseif($trx->status === 'Diproses')
                                        <span class="bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors inline-flex items-center">
                                            <i class="fa-solid fa-spinner animate-spin mr-1"></i> Diproses
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-2">
                                        @if($trx->status === 'Menunggu')
                                            <form action="{{ route('kasir.pesanan.updateStatus', $trx->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Diproses">
                                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs shadow-sm transition-transform transform hover:-translate-y-0.5 flex items-center">
                                                    <i class="fa-solid fa-print mr-1.5"></i> Proses
                                                </button>
                                            </form>
                                        @elseif($trx->status === 'Diproses')
                                            <form action="{{ route('kasir.pesanan.updateStatus', $trx->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Siap Diambil">
                                                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg text-xs shadow-sm transition-transform transform hover:-translate-y-0.5 flex items-center">
                                                    <i class="fa-solid fa-check mr-1.5"></i> Selesai
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3">
                                            <i class="fa-solid fa-cloud-arrow-down text-2xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <p class="font-medium text-gray-600 dark:text-gray-300">Tidak ada pesanan online</p>
                                        <p class="text-xs mt-1">Belum ada pesanan masuk hari ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center transition-colors">
                <div>
                    <h6 class="font-bold text-gray-800 dark:text-white text-base">Aktivitas Terakhir Anda</h6>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">5 transaksi terakhir yang Anda proses.</p>
                </div>
                <a href="{{ route('kasir.riwayat') }}" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">Lihat Semua</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold">ID Transaksi</th>
                            <th class="px-6 py-4 font-semibold">Waktu</th>
                            <th class="px-6 py-4 font-semibold text-right">Total Bayar</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-slate-800/80">
                        @forelse($transaksiTerbaru ?? collect([]) as $trx)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">{{ $trx->id }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400"><i class="fa-regular fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($trx->created_at)->format('H:i WIB') }}</td>
                                <td class="px-6 py-4 font-bold text-gray-800 dark:text-white text-right">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider transition-colors">Berhasil</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-receipt text-3xl mb-3 text-gray-300 dark:text-slate-600"></i>
                                        <p>Anda belum memproses transaksi hari ini.</p>
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