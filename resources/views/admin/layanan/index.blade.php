<x-app-layout>
    <x-slot name="header">Kelola Harga Layanan</x-slot>

    <div class="w-full" x-data="{ 
        showCreateModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }}, 
        showEditModal: {{ $errors->any() && old('_method') == 'PUT' ? 'true' : 'false' }}, 
        showDeleteModal: false,
        editItem: { id: '', nama_layanan: '', satuan: '', harga_satuan: 0 },
        deleteItem: { name: '', url: '' },
        
        // Fungsi membuka modal edit dan mengisi datanya
        openEditModal(item) {
            this.editItem = { ...item };
            this.showEditModal = true;
        },
        
        // Fungsi membuka modal konfirmasi hapus
        openDeleteModal(name, url) {
            this.deleteItem = { name: name, url: url };
            this.showDeleteModal = true;
        }
    }">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Daftar Harga Jasa</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Atur tarif untuk layanan fotocopy, print, penjilidan, dll.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3">
                <form action="{{ route('admin.layanan.index') }}" method="GET" class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama layanan..." 
                           class="bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-gray-900 dark:focus:ring-white focus:border-gray-900 dark:focus:border-white block w-full pl-10 p-2.5 transition-colors">
                    
                    @if($search)
                        <a href="{{ route('admin.layanan.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-red-500 hover:text-red-700 dark:hover:text-red-400">
                            <i class="fa-solid fa-times"></i>
                        </a>
                    @endif
                </form>

                <button @click="showCreateModal = true" class="bg-gray-900 dark:bg-white text-white dark:text-slate-900 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-800 dark:hover:bg-gray-200 shadow-md transition-all flex items-center justify-center whitespace-nowrap">
                    <i class="fa-solid fa-plus mr-2"></i> Tambah Layanan
                </button>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 bg-green-500 text-white px-5 py-4 rounded-xl shadow-md flex items-center justify-between transition-all">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <p class="font-bold text-sm">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-white hover:text-green-200">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-100 text-red-600 px-5 py-4 rounded-xl shadow-sm border border-red-200 flex items-start space-x-3">
                <i class="fa-solid fa-circle-exclamation text-lg mt-0.5"></i>
                <div>
                    <p class="font-bold text-sm mb-1">Terjadi Kesalahan Validasi!</p>
                    <ul class="text-xs list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden mb-6 transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold">No</th>
                            <th class="px-6 py-4 font-semibold">Nama Layanan</th>
                            <th class="px-6 py-4 font-semibold">Satuan Hitungan</th>
                            <th class="px-6 py-4 font-semibold text-right">Tarif / Harga</th>
                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse($layanan as $index => $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4">{{ $layanan->firstItem() + $index }}</td>
                                <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">{{ $item->nama_layanan }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider transition-colors">Per {{ $item->satuan }}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-green-600 dark:text-green-400">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right flex justify-end space-x-2">
                                    <button @click="openEditModal({{ json_encode($item) }})" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 flex items-center justify-center hover:bg-blue-500 hover:text-white dark:hover:bg-blue-500 dark:hover:text-white transition-colors" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    
                                    <button @click="openDeleteModal('{{ addslashes($item->nama_layanan) }}', '{{ route('admin.layanan.destroy', $item->id) }}')" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 flex items-center justify-center hover:bg-red-500 hover:text-white dark:hover:bg-red-500 dark:hover:text-white transition-colors" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-tags text-4xl mb-3 text-gray-300 dark:text-slate-600"></i>
                                        @if($search)
                                            <p class="font-medium text-gray-600 dark:text-gray-400">Layanan "{{ $search }}" tidak ditemukan.</p>
                                        @else
                                            <p class="font-medium text-gray-600 dark:text-gray-400">Belum ada data layanan.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mb-8">
            {{ $layanan->links() }}
        </div>

        <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-60 dark:bg-opacity-80 transition-opacity" @click="showCreateModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showCreateModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-gray-100 dark:border-slate-800">
                    
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center bg-gray-50/50 dark:bg-slate-800/50 transition-colors">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">Tambah Layanan Baru</h3>
                        <button @click="showCreateModal = false" class="text-gray-400 dark:text-gray-500 hover:text-gray-800 dark:hover:text-gray-300 transition-colors">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.layanan.store') }}" method="POST">
                        @csrf
                        <div class="px-6 py-6 space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Layanan / Jasa <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_layanan" value="{{ old('nama_layanan') }}" required placeholder="Contoh: Print Warna A4" class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-white text-sm">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Satuan Hitungan <span class="text-red-500">*</span></label>
                                    <select name="satuan" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-white text-sm">
                                        <option value="" disabled selected>Pilih Satuan...</option>
                                        <option value="Lembar" {{ old('satuan') == 'Lembar' ? 'selected' : '' }}>Lembar (lbr)</option>
                                        <option value="Buku" {{ old('satuan') == 'Buku' ? 'selected' : '' }}>Buku</option>
                                        <option value="Halaman" {{ old('satuan') == 'Halaman' ? 'selected' : '' }}>Halaman (hal)</option>
                                        <option value="Meter" {{ old('satuan') == 'Meter' ? 'selected' : '' }}>Meter (m)</option>
                                        <option value="Paket" {{ old('satuan') == 'Paket' ? 'selected' : '' }}>Paket</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Tarif / Harga (Rp) <span class="text-red-500">*</span></label>
                                    <input type="number" name="harga_satuan" value="{{ old('harga_satuan') }}" required min="0" placeholder="1500" class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-white text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/80 flex justify-end space-x-3 rounded-b-2xl border-t border-gray-100 dark:border-slate-800 transition-colors">
                            <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-sm">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-bold hover:bg-gray-800 dark:hover:bg-gray-200 shadow-md transition-colors text-sm">Simpan Layanan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" @click="showEditModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showEditModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-gray-100 dark:border-slate-700">
                    
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50/50 dark:bg-slate-800/50 transition-colors">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">Edit Harga Layanan</h3>
                        <button @click="showEditModal = false" class="text-gray-400 dark:text-slate-500 hover:text-gray-800 dark:hover:text-slate-300 transition-colors">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>

                    <form x-bind:action="`{{ url('admin/layanan') }}/${editItem.id}`" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="px-6 py-6 space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1 transition-colors">Nama Layanan / Jasa <span class="text-red-500 dark:text-red-400">*</span></label>
                                <input type="text" name="nama_layanan" x-model="editItem.nama_layanan" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-slate-500 text-sm text-slate-800 dark:text-slate-100 transition-colors">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1 transition-colors">Satuan Hitungan <span class="text-red-500 dark:text-red-400">*</span></label>
                                    <select name="satuan" x-model="editItem.satuan" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-slate-500 text-sm text-slate-800 dark:text-slate-100 transition-colors">
                                        <option value="Lembar">Lembar (lbr)</option>
                                        <option value="Buku">Buku</option>
                                        <option value="Halaman">Halaman (hal)</option>
                                        <option value="Meter">Meter (m)</option>
                                        <option value="Paket">Paket</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1 transition-colors">Tarif / Harga (Rp) <span class="text-red-500 dark:text-red-400">*</span></label>
                                    <input type="number" name="harga_satuan" x-model="editItem.harga_satuan" required min="0" class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-slate-500 text-sm text-slate-800 dark:text-slate-100 transition-colors">
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/80 flex justify-end space-x-3 rounded-b-2xl border-t border-gray-100 dark:border-slate-700 transition-colors">
                            <button type="button" @click="showEditModal = false" class="px-5 py-2.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors text-sm">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 dark:bg-blue-700 text-white rounded-xl font-bold hover:bg-blue-700 dark:hover:bg-blue-600 shadow-md shadow-blue-200 dark:shadow-none transition-colors text-sm">Update Layanan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" @click="showDeleteModal = false" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showDeleteModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full border border-gray-100 dark:border-slate-700">
                    
                    <div class="px-6 py-6 text-center transition-colors">
                        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white dark:border-slate-800 shadow-sm transition-colors">
                            <i class="fa-solid fa-trash-can text-2xl text-red-500 dark:text-red-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 transition-colors">Konfirmasi Hapus</h3>
                        <p class="text-sm text-gray-500 dark:text-slate-400 transition-colors">Apakah Anda yakin ingin menghapus layanan <br><span class="font-bold text-gray-800 dark:text-slate-200" x-text="deleteItem.name"></span>?<br> Data yang dihapus tidak dapat dikembalikan.</p>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/80 flex justify-center space-x-3 rounded-b-2xl border-t border-gray-100 dark:border-slate-700 transition-colors">
                        <button type="button" @click="showDeleteModal = false" class="w-1/2 px-5 py-2.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors text-sm">Batal</button>
                        
                        <form x-bind:action="deleteItem.url" method="POST" class="w-1/2 m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-5 py-2.5 bg-red-600 dark:bg-red-700 text-white rounded-xl font-bold hover:bg-red-700 dark:hover:bg-red-600 shadow-md transition-colors text-sm">Ya, Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</x-app-layout>