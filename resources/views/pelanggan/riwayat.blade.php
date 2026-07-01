<x-app-layout>
    <x-slot name="header">Riwayat Pesanan</x-slot>

    <div class="w-full" x-data="{ 
        showDeleteModal: false,
        deleteItem: { name: '', url: '' },
        selectedIds: [],
        openDeleteModal(name, url) {
            this.deleteItem = { name: name, url: url };
            this.showDeleteModal = true;
        }
    }">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Riwayat Pesanan Saya</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau status pesanan dan lihat riwayat yang sudah selesai.</p>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 bg-green-500 text-white px-5 py-4 rounded-xl shadow-md flex items-center justify-between transition-all">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <p class="font-bold text-sm">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-white hover:text-green-200"><i class="fa-solid fa-times"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-red-500 dark:bg-red-600 text-white px-5 py-4 rounded-xl shadow-md flex items-center justify-between transition-all">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    <p class="font-bold text-sm">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-white hover:text-red-200"><i class="fa-solid fa-times"></i></button>
            </div>
        @endif

        {{-- Search & Filter --}}
        <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-4 mb-6 shadow-sm">
            <form method="GET" action="{{ route('pelanggan.riwayat') }}" class="flex flex-col md:flex-row gap-3 items-center w-full">
                <!-- Filter Tanggal -->
                <div class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
                    <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="bg-gray-50 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block w-full p-2.5 text-gray-900 dark:text-white">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">s/d</span>
                    <input type="date" name="tgl_akhir" value="{{ request('tgl_akhir') }}" class="bg-gray-50 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block w-full p-2.5 text-gray-900 dark:text-white">
                </div>

                <div class="relative w-full md:flex-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari layanan atau catatan..."
                        class="bg-gray-50 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block w-full pl-10 p-2.5 transition-colors">
                </div>

                <select name="status" class="bg-gray-50 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 p-2.5 w-full sm:w-auto transition-colors">
                    <option value="semua" {{ (request('status') ?? '') === 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="Menunggu" {{ (request('status') ?? '') === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Diproses" {{ (request('status') ?? '') === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Siap Diambil" {{ (request('status') ?? '') === 'Siap Diambil' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="Selesai" {{ (request('status') ?? '') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>

                <button type="submit" class="w-full sm:w-auto bg-gray-900 dark:bg-slate-700 text-white dark:text-white px-5 py-2.5 rounded-xl font-bold hover:bg-gray-800 dark:hover:bg-slate-600 transition-colors text-sm shadow-sm">
                    Terapkan
                </button>
                @if(request('search') || request('status') || request('tgl_mulai') || request('tgl_akhir'))
                    <a href="{{ route('pelanggan.riwayat') }}" class="w-full sm:w-auto text-center px-4 py-2.5 text-sm font-bold text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/20 rounded-xl transition-colors">Reset</a>
                @endif
            </form>
        </div>

        <!-- Bulk Delete Action Bar -->
        <div x-show="selectedIds.length > 0" style="display: none;" x-transition class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-2xl p-4 mb-4 flex flex-col sm:flex-row justify-between items-center shadow-sm">
            <div class="text-red-800 dark:text-red-400 font-bold mb-3 sm:mb-0">
                <i class="fa-solid fa-check-double mr-2"></i> <span x-text="selectedIds.length"></span> Pesanan Menunggu Terpilih
            </div>
            <button type="button" @click.prevent="if(confirm('Apakah Anda yakin ingin membatalkan ' + selectedIds.length + ' pesanan ini?')) document.getElementById('bulkDeleteForm').submit();" class="bg-red-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md hover:bg-red-700 transition-colors w-full sm:w-auto">
                <i class="fa-solid fa-trash-can mr-2"></i> Batalkan Sekaligus
            </button>
        </div>

        <form action="{{ route('pelanggan.pesanan.destroyBulk') }}" method="POST" id="bulkDeleteForm">
            @csrf
            @method('DELETE')

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden mb-6 transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold text-center w-12">
                                <input type="checkbox" @change="selectedIds = $event.target.checked ? [{{ $riwayat->where('status', 'Menunggu')->pluck('id')->join(',') }}] : []" :checked="selectedIds.length === {{ $riwayat->where('status', 'Menunggu')->count() }} && {{ $riwayat->where('status', 'Menunggu')->count() }} > 0" class="rounded border-gray-300 text-red-600 focus:ring-red-500 bg-white dark:bg-slate-900 {{ $riwayat->where('status', 'Menunggu')->count() === 0 ? 'opacity-50 cursor-not-allowed hidden' : '' }}">
                            </th>
                            <th class="px-6 py-4 font-semibold">ID Pesanan</th>
                            <th class="px-6 py-4 font-semibold">Layanan</th>
                            <th class="px-6 py-4 font-semibold">Catatan</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse($riwayat as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors" :class="selectedIds.includes({{ $item->id }}) ? 'bg-red-50/30 dark:bg-red-900/10' : ''">
                                <td class="px-6 py-4 text-center">
                                    @if($item->status === 'Menunggu')
                                        <input type="checkbox" name="selected_ids[]" value="{{ $item->id }}" x-model="selectedIds" class="rounded border-gray-300 text-red-600 focus:ring-red-500 bg-white dark:bg-slate-900">
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-800 dark:text-white">#{{ $item->id }}</span><br>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">{{ $item->layanan->nama_item ?? 'Custom' }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($item->catatan, 40) ?: '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->status === 'Menunggu') <span class="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors">Menunggu</span>
                                    @elseif($item->status === 'Diproses') <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors">Diproses</span>
                                    @elseif($item->status === 'Siap Diambil') <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors">Siap Diambil</span>
                                    @elseif($item->status === 'Selesai') <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors">Selesai</span>
                                    @else <span class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors">{{ $item->status }}</span>
                                    @endif

                                    @if($item->status === 'Menunggu' || $item->status === 'Diproses')
                                        @php
                                            $antreanDepan = \App\Models\Pesanan::whereIn('status', ['Menunggu', 'Diproses'])
                                                ->where('created_at', '<', $item->created_at)
                                                ->count();
                                        @endphp
                                        <div class="mt-2 flex justify-center">
                                            <span class="inline-flex items-center px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 text-indigo-700 dark:text-indigo-400 rounded text-[9px] font-bold shadow-sm transition-colors whitespace-nowrap">
                                                <i class="fa-solid fa-people-arrows mr-1"></i> {{ $antreanDepan == 0 ? 'Giliran Anda Berikutnya' : $antreanDepan . ' Antrean Di Depan' }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('pelanggan.riwayat.download', $item->id) }}" target="_blank" class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600 dark:hover:bg-blue-600 hover:text-white dark:hover:text-white w-8 h-8 rounded-lg flex items-center justify-center transition-colors border border-blue-200 dark:border-blue-800 hover:border-blue-600 dark:hover:border-blue-600" title="Unduh Struk">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </a>
                                        @if($item->status === 'Menunggu')
                                            <button type="button" @click="openDeleteModal('{{ addslashes($item->layanan->nama_item ?? 'Custom') }}', '{{ route('pelanggan.pesanan.destroy', $item->id) }}')" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 flex items-center justify-center hover:bg-red-500 hover:text-white dark:hover:bg-red-500 dark:hover:text-white transition-colors border border-transparent" title="Batalkan Pesanan">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-box-open text-4xl mb-3 text-gray-300 dark:text-slate-600"></i>
                                        @if(request('search') || request('status') || request('tgl_mulai') || request('tgl_akhir'))
                                            <p class="font-medium text-gray-600 dark:text-gray-400">Pencarian untuk filter ini tidak ditemukan.</p>
                                        @else
                                            <p class="font-medium text-gray-600 dark:text-gray-400">Anda belum pernah membuat pesanan.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        </form>

        <div class="mb-8 mt-6">
            {{ $riwayat->links() }}
        </div>

        {{-- MODAL HAPUS --}}
        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-60 dark:bg-opacity-80 transition-opacity" @click="showDeleteModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showDeleteModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full border border-gray-100 dark:border-slate-800">
                    <div class="px-6 py-6 text-center">
                        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/40 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white dark:border-slate-800 shadow-sm transition-colors">
                            <i class="fa-solid fa-trash-can text-2xl text-red-500 dark:text-red-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Batalkan Pesanan</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin membatalkan pesanan <br><span class="font-bold text-gray-800 dark:text-gray-200" x-text="deleteItem.name"></span>?<br> Pesanan yang dibatalkan tidak dapat dikembalikan.</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/80 flex justify-center space-x-3 rounded-b-2xl border-t border-gray-100 dark:border-slate-800 transition-colors">
                        <button type="button" @click="showDeleteModal = false" class="w-1/2 px-5 py-2.5 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-sm">Kembali</button>
                        <form x-bind:action="deleteItem.url" method="POST" class="w-1/2 m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-5 py-2.5 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 shadow-md transition-colors text-sm">Batalkan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>