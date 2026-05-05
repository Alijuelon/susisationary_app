<x-app-layout>
    <x-slot name="header">Antrean Pesanan Online</x-slot>

    <div class="w-full" x-data="{ 
        showStatusModal: false, 
        orderItem: { id: '', status: '', nama_pelanggan: '' },
        selectedIds: [],
        
        openStatusModal(item) {
            this.orderItem = { 
                id: item.id, 
                status: item.status, 
                nama_pelanggan: item.pelanggan.nama_lengkap 
            };
            this.showStatusModal = true;
        }
    }">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Daftar Antrean Cetak</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Unduh file pelanggan dan perbarui status pengerjaan.</p>
            </div>
        </div>

        <!-- Filter Area -->
        <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-4 mb-6 shadow-sm">
            <form action="{{ route('kasir.pesanan.masuk') }}" method="GET" class="flex flex-wrap md:flex-nowrap gap-3 items-center w-full">
                <div class="w-full sm:w-auto">
                    <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="bg-gray-50 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block w-full p-2.5 dark:text-white" title="Dari Tanggal">
                </div>
                <div class="hidden sm:block text-gray-400">-</div>
                <div class="w-full sm:w-auto">
                    <input type="date" name="tgl_akhir" value="{{ request('tgl_akhir') }}" class="bg-gray-50 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block w-full p-2.5 dark:text-white" title="Sampai Tanggal">
                </div>
                <div class="w-full sm:w-auto">
                    <select name="status" class="bg-gray-50 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block w-full p-2.5 dark:text-white relative z-50 appearance-auto">
                        <option value="Semua">Semua Status</option>
                        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Siap Diambil" {{ request('status') == 'Siap Diambil' ? 'selected' : '' }}>Siap Diambil</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="relative w-full md:flex-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau ID..." class="bg-gray-50 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block w-full pl-10 p-2.5 dark:text-white">
                </div>
                <button type="submit" class="w-full sm:w-auto bg-gray-900 dark:bg-slate-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-800 dark:hover:bg-slate-600 transition-colors shadow-sm">
                    Filter
                </button>
                @if(request('search') || request('status') || request('tgl_mulai') || request('tgl_akhir'))
                    <a href="{{ route('kasir.pesanan.masuk') }}" class="w-full sm:w-auto text-center px-4 py-2.5 text-sm font-bold text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/20 rounded-xl transition-colors">Reset</a>
                @endif
            </form>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-green-500 dark:bg-green-600 border border-green-600 dark:border-green-500 text-white px-5 py-4 rounded-xl shadow-md flex items-center justify-between transition-all">
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

        <!-- Bulk Delete Action Bar -->
        <div x-show="selectedIds.length > 0" style="display: none;" x-transition class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-2xl p-4 mb-4 flex flex-col sm:flex-row justify-between items-center shadow-sm">
            <div class="text-red-800 dark:text-red-400 font-bold mb-3 sm:mb-0">
                <i class="fa-solid fa-check-double mr-2"></i> <span x-text="selectedIds.length"></span> Pesanan Terpilih
            </div>
            <button type="button" @click.prevent="if(confirm('Apakah Anda yakin ingin menghapus ' + selectedIds.length + ' pesanan sekaligus? Data tidak bisa dipulihkan.')) document.getElementById('bulkDeleteForm').submit();" class="bg-red-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md hover:bg-red-700 transition-colors w-full sm:w-auto">
                <i class="fa-solid fa-trash mr-2"></i> Hapus Sekaligus
            </button>
        </div>

        <form action="{{ route('kasir.pesanan.destroyBulk') }}" method="POST" id="bulkDeleteForm">
            @csrf
            @method('DELETE')

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden mb-6 transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold text-center w-12">
                                <input type="checkbox" @change="selectedIds = $event.target.checked ? [{{ $pesanan->pluck('id')->join(',') }}] : []" :checked="selectedIds.length === {{ $pesanan->count() }} && {{ $pesanan->count() }} > 0" class="rounded border-gray-300 text-red-600 focus:ring-red-500 bg-white dark:bg-slate-900">
                            </th>
                            <th class="px-6 py-4 font-semibold">ID / Waktu Masuk</th>
                            <th class="px-6 py-4 font-semibold">Pelanggan</th>
                            <th class="px-6 py-4 font-semibold">Layanan & Catatan</th>
                            <th class="px-6 py-4 font-semibold text-center">File Dokumen</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse($pesanan as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors {{ $item->status === 'Menunggu' ? 'bg-yellow-50/30 dark:bg-yellow-900/10' : '' }}" :class="selectedIds.includes({{ $item->id }}) ? 'bg-red-50/30 dark:bg-red-900/10' : ''">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $item->id }}" x-model="selectedIds" class="rounded border-gray-300 text-red-600 focus:ring-red-500 bg-white dark:bg-slate-900">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold text-gray-800 dark:text-white">#{{ $item->id }}</span> <br>
                                    <span class="text-[11px] text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">{{ $item->pelanggan->nama_lengkap ?? 'Anonim' }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-2 py-0.5 rounded text-xs font-bold transition-colors">{{ $item->layanan->nama_layanan ?? 'Custom' }}</span>
                                    @if($item->catatan)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">"{{ Str::limit($item->catatan, 30) }}"</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ asset('storage/' . $item->file_dokumen) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white text-blue-600 dark:text-blue-400 rounded-lg transition-colors" title="Lihat Dokumen">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ asset('storage/' . $item->file_dokumen) }}" download class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-slate-800 hover:bg-gray-900 dark:hover:bg-gray-200 hover:text-white dark:hover:text-slate-900 text-gray-600 dark:text-gray-400 rounded-lg transition-colors" title="Download File">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->status === 'Menunggu')
                                        <span class="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors"><i class="fa-regular fa-clock mr-1"></i> Menunggu</span>
                                    @elseif($item->status === 'Diproses')
                                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors"><i class="fa-solid fa-spinner mr-1"></i> Diproses</span>
                                    @elseif($item->status === 'Siap Diambil')
                                        <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors"><i class="fa-solid fa-box mr-1"></i> Siap Diambil</span>
                                    @elseif($item->status === 'Selesai')
                                        <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors"><i class="fa-solid fa-check-double mr-1"></i> Selesai</span>
                                    @else
                                        <span class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button type="button" @click="openStatusModal({{ json_encode($item) }})" class="bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors shadow-sm inline-flex items-center">
                                            Status
                                        </button>
                                        <form action="{{ route('kasir.pesanan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan online ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors shadow-sm inline-flex items-center border border-transparent" title="Hapus Pesanan">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-inbox text-4xl mb-3 text-gray-300 dark:text-slate-600"></i>
                                        @if(request('search') || request('status') || request('tgl_mulai'))
                                        <p class="font-medium text-gray-600 dark:text-gray-400">Pencarian untuk filter ini tidak ditemukan.</p>
                                        @else
                                        <p class="font-medium text-gray-600 dark:text-gray-400">Tidak ada antrean pesanan online.</p>
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

        <div class="mb-8">
            {{ $pesanan->links() }}
        </div>

        <div x-show="showStatusModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="showStatusModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-60 dark:bg-opacity-80 transition-opacity" @click="showStatusModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                     <div x-show="showStatusModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full border border-gray-100 dark:border-slate-800">
                    
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center bg-gray-50/50 dark:bg-slate-800/50 transition-colors">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">Perbarui Status</h3>
                        <button @click="showStatusModal = false" class="text-gray-400 dark:text-gray-500 hover:text-gray-800 dark:hover:text-gray-300 transition-colors">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>

                    <form x-bind:action="`{{ url('kasir/pesanan-masuk') }}/${orderItem.id}/status`" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="px-6 py-6">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Ubah status pengerjaan untuk pesanan <span class="font-bold text-gray-900 dark:text-white" x-text="orderItem.nama_pelanggan"></span>.</p>
                            
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status Saat Ini:</label>
                            
                            <div class="space-y-2">
                                <label class="flex items-center p-3 border border-gray-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors" :class="orderItem.status == 'Menunggu' ? 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/10 dark:border-yellow-500' : ''">
                                    <input type="radio" name="status" value="Menunggu" x-model="orderItem.status" class="text-yellow-600 focus:ring-yellow-500 bg-white dark:bg-slate-900 dark:border-slate-600 mr-3">
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Menunggu</span>
                                </label>
                                
                                <label class="flex items-center p-3 border border-gray-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors" :class="orderItem.status == 'Diproses' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/10 dark:border-blue-500' : ''">
                                    <input type="radio" name="status" value="Diproses" x-model="orderItem.status" class="text-blue-600 focus:ring-blue-500 bg-white dark:bg-slate-900 dark:border-slate-600 mr-3">
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Diproses (Sedang dicetak/dijilid)</span>                                                                                                                                                                  
                                </label>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        

                                <label class="flex items-center p-3 border border-gray-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors" :class="orderItem.status == 'Siap Diambil' ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/10 dark:border-purple-500' : ''">
                                    <input type="radio" name="status" value="Siap Diambil" x-model="orderItem.status" class="text-purple-600 focus:ring-purple-500 bg-white dark:bg-slate-900 dark:border-slate-600 mr-3">
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Siap Diambil / Selesai Dicetak</span>
                                </label>

                                <label class="flex items-center p-3 border border-gray-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors" :class="orderItem.status == 'Selesai' ? 'border-green-500 bg-green-50 dark:bg-green-900/10 dark:border-green-500' :                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     ''">
                                    <input type="radio" name="status" value="Selesai" x-model="orderItem.status" class="text-green-600 focus:ring-green-500 bg-white dark:bg-slate-900 dark:border-slate-600 mr-3">
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Selesai (Sudah diambil & dibayar)</span>
                                </label>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/80 flex justify-end space-x-3 rounded-b-2xl border-t border-gray-100 dark:border-slate-800 transition-colors">
                            <button type="button" @click="showStatusModal = false" class="px-5 py-2.5 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-sm">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-bold hover:bg-gray-800 dark:hover:bg-gray-200 shadow-md transition-colors text-sm">Simpan Status</button>
                        </div>
                    </form>                                                                                                                                                                                                                                                                                                                                                                                     
                </div>                                                                                                                                                                                                                                                                                                                                                              
            </div>
        </div>

    </div>
</x-app-layout>                                                                                                                                                                                                                                                                                                                                                                             