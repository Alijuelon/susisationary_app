<x-app-layout>
    <x-slot name="header">Antrean Pesanan Online</x-slot>

    <div class="w-full" x-data="{ 
        showStatusModal: false, 
        orderItem: { id: '', status: '', nama_pelanggan: '' },
        
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
            
            <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3">
                
                <form action="{{ route('kasir.pesanan.masuk') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full" id="filterForm">
                    <select name="status" onchange="document.getElementById('filterForm').submit()" class="bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-gray-900 dark:focus:ring-white focus:border-gray-900 dark:focus:border-white p-2.5 transition-colors">
                        <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Siap Diambil" {{ request('status') == 'Siap Diambil' ? 'selected' : '' }}>Siap Diambil</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>

                    <div class="relative w-full sm:w-56">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fa-solid fa-search text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau ID..." 
                               class="bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-gray-900 dark:focus:ring-white focus:border-gray-900 dark:focus:border-white block w-full pl-10 p-2.5 transition-colors">
                        @if($search)
                            <a href="{{ route('kasir.pesanan.masuk') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-red-500 hover:text-red-700 dark:hover:text-red-400">
                                <i class="fa-solid fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>

            </div>
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

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden mb-6 transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold">ID / Waktu Masuk</th>
                            <th class="px-6 py-4 font-semibold">Pelanggan</th>
                            <th class="px-6 py-4 font-semibold">Layanan & Catatan</th>
                            <th class="px-6 py-4 font-semibold text-center">File Dokumen</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse($pesanan as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors {{ $item->status === 'Menunggu' ? 'bg-yellow-50/30 dark:bg-yellow-900/10' : '' }}">
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
                                    <a href="{{ asset('storage/' . $item->file_dokumen) }}" target="_blank" download class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-slate-800 hover:bg-gray-900 dark:hover:bg-gray-200 hover:text-white dark:hover:text-slate-900 text-gray-600 dark:text-gray-400 rounded-lg transition-colors" title="Download File">
                                        <i class="fa-solid fa-cloud-arrow-down"></i>
                                    </a>
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
                                <td class="px-6 py-4 text-right">
                                    <button @click="openStatusModal({{ json_encode($item) }})" class="bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                        Ubah Status
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-inbox text-4xl mb-3 text-gray-300 dark:text-slate-600"></i>
                                        <p class="font-medium text-gray-600 dark:text-gray-400">Tidak ada antrean pesanan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

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