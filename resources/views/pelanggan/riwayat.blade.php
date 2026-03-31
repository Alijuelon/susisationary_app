<x-app-layout>
    <x-slot name="header">Riwayat Pesanan</x-slot>

    <div class="w-full">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Riwayat Pesanan Saya</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau status pesanan dan lihat riwayat yang sudah selesai.</p>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 bg-green-500 dark:bg-green-600 border border-green-600 dark:border-green-500 text-white px-5 py-4 rounded-xl shadow-md flex items-center justify-between transition-colors">
                <div class="flex items-center space-x-3"><i class="fa-solid fa-circle-check text-xl"></i><p class="font-bold text-sm">{{ session('success') }}</p></div>
                <button @click="show = false" class="text-white hover:text-green-200 transition-colors"><i class="fa-solid fa-times"></i></button>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden mb-6 transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold">ID Pesanan</th>
                            <th class="px-6 py-4 font-semibold">Layanan</th>
                            <th class="px-6 py-4 font-semibold">Catatan</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse($riwayat as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-800 dark:text-white">#{{ $item->id }}</span><br>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">{{ $item->layanan->nama_layanan ?? 'Custom' }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($item->catatan, 40) ?: '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->status === 'Menunggu') <span class="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors">Menunggu</span>
                                    @elseif($item->status === 'Diproses') <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors">Diproses</span>
                                    @elseif($item->status === 'Siap Diambil') <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors">Siap Diambil</span>
                                    @elseif($item->status === 'Selesai') <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors">Selesai</span>
                                    @else <span class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('pelanggan.riwayat.download', $item->id) }}" target="_blank" class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600 dark:hover:bg-blue-600 hover:text-white dark:hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center border border-blue-200 dark:border-blue-800 hover:border-blue-600 dark:hover:border-blue-600">
                                        <i class="fa-solid fa-file-invoice mr-1.5"></i> Struk
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">Anda belum pernah membuat pesanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mb-8">{{ $riwayat->links() }}</div>
    </div>
</x-app-layout>