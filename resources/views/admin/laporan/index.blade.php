<x-app-layout>
    <x-slot name="header">Laporan Keuangan Susi Stationary</x-slot>

    <div class="w-full">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Rekapitulasi pemasukan transaksi dan pengeluaran operasional toko.</p>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 mb-6 transition-colors">
            <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
                
                <div class="w-full sm:w-1/3">
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-800 dark:focus:ring-white text-sm text-gray-600 dark:text-gray-300 transition-colors">
                </div>
                
                <div class="w-full sm:w-1/3">
                    <label for="end_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-800 dark:focus:ring-white text-sm text-gray-600 dark:text-gray-300 transition-colors">
                </div>
                
                <div class="w-full sm:w-auto flex space-x-2">
                    <button type="submit" class="w-full bg-gray-900 dark:bg-white text-white dark:text-slate-900 px-6 py-2 rounded-xl text-sm font-bold hover:bg-gray-800 dark:hover:bg-gray-200 transition-colors shadow-sm">
                        <i class="fa-solid fa-filter mr-2"></i> Filter Data
                    </button>
                    <a href="{{ route('admin.laporan.index') }}" class="bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors flex items-center justify-center" title="Reset Filter">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                </div>
                
                <div class="w-full sm:w-auto sm:ml-auto">
                    <a href="{{ route('admin.laporan.cetak', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="block w-full text-center bg-red-600 text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-red-700 transition-colors shadow-md shadow-red-200 dark:shadow-red-900/20">
                        <i class="fa-solid fa-file-pdf mr-2"></i> Cetak PDF
                    </a>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-800 transition-colors">
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Pemasukan</p>
                <h3 class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-800 transition-colors">
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Pengeluaran</p>
                <h3 class="text-2xl font-bold text-red-500 dark:text-red-400 mt-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">Keterangan</th>
                            <th class="px-6 py-4 font-semibold">Jenis</th>
                            <th class="px-6 py-4 font-semibold text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse($laporan as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d M Y - H:i') }}
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">{{ $item->keterangan }}</td>
                                <td class="px-6 py-4">
                                    @if($item->jenis == 'Pemasukan')
                                        <span class="bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider transition-colors"><i class="fa-solid fa-arrow-down mr-1"></i> Masuk</span>
                                    @else
                                        <span class="bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider transition-colors"><i class="fa-solid fa-arrow-up mr-1"></i> Keluar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-medium {{ $item->jenis == 'Pemasukan' ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                                    {{ $item->jenis == 'Pemasukan' ? '+' : '-' }} Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300 dark:text-slate-600"></i>
                                        <p class="font-medium text-gray-600 dark:text-gray-400">Tidak ada data di rentang tanggal ini.</p>
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