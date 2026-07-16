<x-app-layout>
    <x-slot name="header">Kelola Keanggotaan</x-slot>

    <div class="w-full" x-data="{ 
        showDeleteModal: false,
        deleteItem: { name: '', url: '' },
        selectedIds: [],
        openDeleteModal(name, url) {
            this.deleteItem = { name: name, url: url };
            this.showDeleteModal = true;
        },
        showConfirmModal: false,
        confirmData: { message: '', url: '', method: '', btnClass: '', iconClass: '', title: '' },
        openConfirmModal(title, message, url, method, btnClass, iconClass) {
            this.confirmData = { title, message, url, method, btnClass, iconClass };
            this.showConfirmModal = true;
        }
    }">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 transition-colors">Kelola permohonan dan status keanggotaan pelanggan.
            <a href="{{ route('admin.membership.settings') }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline ml-2"><i class="fa-solid fa-gear mr-1"></i>Pengaturan Membership</a>
        </p>

        {{-- Search & Filter --}}
        {{-- Search & Filter --}}
        <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-4 mb-6 shadow-sm">
            <form method="GET" action="{{ route('admin.membership.index') }}" class="flex flex-col md:flex-row gap-3 items-center w-full">
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
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama pelanggan atau nomor kartu..."
                        class="bg-gray-50 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-300 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block w-full pl-10 p-2.5 transition-colors">
                </div>

                <select name="status" class="bg-gray-50 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-300 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 p-2.5 w-full sm:w-auto transition-colors">
                    <option value="semua" {{ (request('status') ?? '') === 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="menunggu" {{ (request('status') ?? '') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="aktif" {{ (request('status') ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ (request('status') ?? '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif/Ditolak</option>
                </select>

                <button type="submit" class="w-full sm:w-auto bg-gray-900 dark:bg-slate-700 text-white dark:text-white px-5 py-2.5 rounded-xl font-bold hover:bg-gray-800 dark:hover:bg-slate-600 transition-colors text-sm shadow-sm">
                    Terapkan
                </button>
                @if(request('search') || request('status') || request('tgl_mulai') || request('tgl_akhir'))
                    <a href="{{ route('admin.membership.index') }}" class="w-full sm:w-auto text-center px-4 py-2.5 text-sm font-bold text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/20 rounded-xl transition-colors">Reset</a>
                @endif
            </form>
        </div>

        {{-- Info Membership Aktif --}}
        @if($pengaturan && $pengaturan->membership_aktif)
            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 px-5 py-3 rounded-xl mb-6 flex items-center text-sm transition-colors">
                <i class="fa-solid fa-users mr-3 text-lg"></i>
                <span>Program membership <strong>AKTIF</strong>.</span>
            </div>
        @else
            <div class="bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-gray-400 px-5 py-3 rounded-xl mb-6 flex items-center text-sm transition-colors">
                <i class="fa-solid fa-circle-pause mr-3 text-lg"></i>
                <span>Program membership sedang <strong>NONAKTIF</strong>. Aktifkan di <a href="{{ route('admin.membership.settings') }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline">Pengaturan</a>.</span>
            </div>
        @endif



        <!-- Bulk Delete Action Bar -->
        <div x-show="selectedIds.length > 0" style="display: none;" x-transition class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-2xl p-4 mb-4 flex flex-col sm:flex-row justify-between items-center shadow-sm">
            <div class="text-red-800 dark:text-red-400 font-bold mb-3 sm:mb-0">
                <i class="fa-solid fa-check-double mr-2"></i> <span x-text="selectedIds.length"></span> Data Terpilih
            </div>
            <button type="button" @click.prevent="if(confirm('Apakah Anda yakin ingin menghapus ' + selectedIds.length + ' data sekaligus?')) document.getElementById('bulkDeleteForm').submit();" class="bg-red-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md hover:bg-red-700 transition-colors w-full sm:w-auto">
                <i class="fa-solid fa-trash mr-2"></i> Hapus Sekaligus
            </button>
        </div>

        <form action="{{ route('admin.membership.destroyBulk') }}" method="POST" id="bulkDeleteForm" class="hidden">
            @csrf
            @method('DELETE')
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" name="selected_ids[]" :value="id">
            </template>
        </form>

        {{-- Tabel Membership --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold text-center w-12">
                                <input type="checkbox" @change="selectedIds = $event.target.checked ? [{{ $memberships->pluck('id')->join(',') }}] : []" :checked="selectedIds.length === {{ $memberships->count() }} && {{ $memberships->count() }} > 0" class="rounded border-gray-300 text-red-600 focus:ring-red-500 bg-white dark:bg-slate-900">
                            </th>
                            <th class="px-6 py-4 font-semibold">Nama Pelanggan</th>
                            <th class="px-6 py-4 font-semibold">No. Kartu</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Diproses Oleh</th>
                            <th class="px-6 py-4 font-semibold">Tgl Proses</th>
                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-slate-800/80">
                        @forelse($memberships as $index => $m)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors" :class="selectedIds.includes({{ $m->id }}) ? 'bg-red-50/30 dark:bg-red-900/10' : ''">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $m->id }}" x-model="selectedIds" class="rounded border-gray-300 text-red-600 focus:ring-red-500 bg-white dark:bg-slate-900">
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">{{ $m->pelanggan->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4 font-mono text-xs text-gray-600 dark:text-gray-400">{{ $m->no_kartu }}</td>
                                <td class="px-6 py-4">
                                    @if($m->status === 'menunggu')
                                        <span class="bg-yellow-100 dark:bg-yellow-900/40 text-yellow-600 dark:text-yellow-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase">Menunggu</span>
                                    @elseif($m->status === 'aktif')
                                        <span class="bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase">Aktif</span>
                                    @else
                                        <span class="bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-xs">{{ $m->processor->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-xs">{{ $m->processed_at ? $m->processed_at->format('d M Y H:i') : '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($m->status === 'menunggu')
                                            <button type="button" @click="openConfirmModal('Setujui Membership', 'Apakah Anda yakin ingin menyetujui membership atas nama {{ addslashes($m->pelanggan->nama_lengkap ?? 'Unknown') }}?', '{{ route('admin.membership.approve', $m->id) }}', 'PATCH', 'bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white', 'fa-check text-green-500 dark:text-green-400')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors" title="Setujui">
                                                <i class="fa-solid fa-check text-xs"></i>
                                            </button>
                                            <button type="button" @click="openConfirmModal('Tolak Membership', 'Apakah Anda yakin ingin menolak permohonan membership atas nama {{ addslashes($m->pelanggan->nama_lengkap ?? 'Unknown') }}?', '{{ route('admin.membership.reject', $m->id) }}', 'PATCH', 'bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 text-white', 'fa-times text-red-500 dark:text-red-400')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors" title="Tolak">
                                                <i class="fa-solid fa-times text-xs"></i>
                                            </button>
                                        @elseif($m->status === 'aktif')
                                            <button type="button" @click="openConfirmModal('Nonaktifkan Membership', 'Apakah Anda yakin ingin menonaktifkan membership atas nama {{ addslashes($m->pelanggan->nama_lengkap ?? 'Unknown') }}?', '{{ route('admin.membership.reject', $m->id) }}', 'PATCH', 'bg-amber-600 hover:bg-amber-700 dark:bg-amber-700 dark:hover:bg-amber-600 text-white', 'fa-ban text-amber-500 dark:text-amber-400')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50 transition-colors" title="Nonaktifkan">
                                                <i class="fa-solid fa-ban text-xs"></i>
                                            </button>
                                        @else
                                            <button type="button" @click="openConfirmModal('Aktifkan Kembali', 'Apakah Anda yakin ingin mengaktifkan kembali membership atas nama {{ addslashes($m->pelanggan->nama_lengkap ?? 'Unknown') }}?', '{{ route('admin.membership.approve', $m->id) }}', 'PATCH', 'bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white', 'fa-rotate-left text-green-500 dark:text-green-400')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors" title="Aktifkan Kembali">
                                                <i class="fa-solid fa-rotate-left text-xs"></i>
                                            </button>
                                        @endif
                                        <button type="button" @click="openDeleteModal('{{ addslashes($m->pelanggan->nama_lengkap ?? 'Unknown') }}', '{{ route('admin.membership.destroy', $m->id) }}')" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 flex items-center justify-center hover:bg-red-500 hover:text-white dark:hover:bg-red-500 dark:hover:text-white transition-colors border border-transparent" title="Hapus Membership">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-id-card text-4xl mb-3 text-gray-300 dark:text-slate-600"></i>
                                        @if(request('search') || request('status') || request('tgl_mulai') || request('tgl_akhir'))
                                            <p class="font-medium text-gray-600 dark:text-gray-400">Pencarian untuk filter ini tidak ditemukan.</p>
                                        @else
                                            <p class="font-medium text-gray-600 dark:text-gray-400">Belum ada data keanggotaan.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>

        @if($memberships->hasPages())
            <div class="mb-8 mt-6">
                {{ $memberships->links() }}
            </div>
        @endif

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
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Konfirmasi Hapus</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus membership <br><span class="font-bold text-gray-800 dark:text-gray-200" x-text="deleteItem.name"></span>?<br> Data yang dihapus tidak dapat dikembalikan.</p>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/80 flex justify-center space-x-3 rounded-b-2xl border-t border-gray-100 dark:border-slate-800 transition-colors">
                        <button type="button" @click="showDeleteModal = false" class="w-1/2 px-5 py-2.5 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-sm">Batal</button>
                        
                        <form x-bind:action="deleteItem.url" method="POST" class="w-1/2 m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-5 py-2.5 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 shadow-md transition-colors text-sm">Ya, Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL KONFIRMASI (SETUJUI DLL) --}}
        <div x-show="showConfirmModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showConfirmModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-60 dark:bg-opacity-80 transition-opacity" @click="showConfirmModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showConfirmModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full border border-gray-100 dark:border-slate-800">
                    
                    <div class="px-6 py-6 text-center">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white dark:border-slate-800 shadow-sm transition-colors">
                            <i class="fa-solid text-2xl" :class="confirmData.iconClass"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2" x-text="confirmData.title">Konfirmasi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="confirmData.message"></p>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/80 flex justify-center space-x-3 rounded-b-2xl border-t border-gray-100 dark:border-slate-800 transition-colors">
                        <button type="button" @click="showConfirmModal = false" class="w-1/2 px-5 py-2.5 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-sm">Batal</button>
                        
                        <form x-bind:action="confirmData.url" method="POST" class="w-1/2 m-0">
                            @csrf
                            <input type="hidden" name="_method" x-bind:value="confirmData.method">
                            <button type="submit" class="w-full px-5 py-2.5 rounded-xl font-bold shadow-md transition-colors text-sm" :class="confirmData.btnClass">Ya, Lanjutkan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
