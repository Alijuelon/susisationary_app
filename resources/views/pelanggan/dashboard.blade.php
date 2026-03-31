<x-app-layout>
    <x-slot name="header">Dashboard Saya</x-slot>

    <div class="w-full">
        
        <div class="bg-gradient-to-br from-blue-600 to-blue-800 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-lg p-6 sm:p-8 mb-6 text-white flex flex-col md:flex-row items-center justify-between relative overflow-hidden transition-colors">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-10"></div>
            
            <div class="z-10 text-center md:text-left mb-6 md:mb-0">
                <h2 class="text-2xl sm:text-3xl font-bold mb-2">Halo, {{ explode(' ', Auth::user()->nama_lengkap)[0] }}! 👋</h2>
                <p class="text-blue-100 dark:text-gray-300">Cetak dokumen Anda tanpa perlu antre lama di toko.</p>
            </div>
            
            <div class="z-10 w-full md:w-auto">
                <a href="{{ route('pelanggan.pesanan.create') }}" class="block w-full text-center bg-white dark:bg-slate-700 text-blue-700 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-600 font-bold py-3 px-8 rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Pesan Cetak Baru
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-800 flex items-center transition-colors">
                <div class="w-14 h-14 bg-yellow-100 dark:bg-yellow-900/40 text-yellow-600 dark:text-yellow-400 rounded-xl flex items-center justify-center text-2xl font-bold mr-4"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <div><p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Pesanan Aktif</p><h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ collect($pesananAktif ?? [])->count() }}</h3></div>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-800 flex items-center transition-colors">
                <div class="w-14 h-14 bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 rounded-xl flex items-center justify-center text-2xl font-bold mr-4"><i class="fa-solid fa-check-double"></i></div>
                <div><p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Pesanan Selesai</p><h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $pesananSelesai ?? 0 }}</h3></div>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-800 flex items-center hidden lg:flex transition-colors">
                <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-2xl font-bold mr-4"><i class="fa-solid fa-file-invoice"></i></div>
                <div><p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Pemesanan</p><h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalPesanan ?? 0 }}</h3></div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 transition-colors"><h6 class="font-bold text-gray-800 dark:text-white text-base">Status Pesanan Berjalan</h6></div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold">ID Transaksi</th>
                            <th class="px-6 py-4 font-semibold">Layanan</th>
                            <th class="px-6 py-4 font-semibold">Waktu Pemesanan</th>
                            <th class="px-6 py-4 font-semibold text-center">Status Pengerjaan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-slate-800/80">
                        @forelse($pesananAktif ?? [] as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">#{{ $item->id }}</td>
                                <td class="px-6 py-4">{{ $item->layanan->nama_layanan ?? 'Custom' }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->status === 'Menunggu') <span class="bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors"><i class="fa-solid fa-hourglass-half mr-1"></i> Menunggu</span>
                                    @elseif($item->status === 'Diproses') <span class="bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors"><i class="fa-solid fa-spinner animate-spin mr-1"></i> Diproses</span>
                                    @elseif($item->status === 'Siap Diambil') <span class="bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors"><i class="fa-solid fa-box mr-1"></i> Siap Diambil</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400"><i class="fa-solid fa-mug-hot text-3xl mb-3 text-gray-300 dark:text-slate-600"></i><p>Tidak ada pesanan yang sedang berjalan.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>