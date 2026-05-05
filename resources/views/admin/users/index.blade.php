<x-app-layout>
    <x-slot name="header">Kelola Pengguna</x-slot>

    <div class="w-full" x-data="{ 
        showCreateModal: false, 
        showEditModal: false, 
        showDeleteModal: false,
        editUser: { id: '', nama_lengkap: '', email: '', role: '', password: '' },
        deleteItem: { name: '', url: '' },
        selectedIds: [],
        openEdit(user) {
            this.editUser = { id: user.id, nama_lengkap: user.nama_lengkap, email: user.email, role: user.role, password: '' };
            this.showEditModal = true;
        },
        openDeleteModal(name, url) {
            this.deleteItem = { name: name, url: url };
            this.showDeleteModal = true;
        }
    }">
    }">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Daftar Pengguna Sistem</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola akses admin, kasir, dan pelanggan.</p>
            </div>
            
            <div>
                <button @click="showCreateModal = true" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-blue-700 transition-colors text-sm shadow-sm w-full md:w-auto flex items-center justify-center whitespace-nowrap">
                    <i class="fa-solid fa-user-plus mr-2"></i> Tambah User Baru
                </button>
            </div>
        </div>

        {{-- Search & Filter --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-800 p-4 mb-6 transition-colors">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-3">
                <div class="relative w-full md:flex-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama, username, email..."
                        class="bg-gray-50 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block w-full pl-10 p-2.5 transition-colors">
                </div>
                <select name="role" class="bg-gray-50 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 p-2.5 w-full sm:w-auto transition-colors">
                    <option value="semua" {{ ($filterRole ?? '') === 'semua' ? 'selected' : '' }}>Semua Role</option>
                    <option value="admin" {{ ($filterRole ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kasir" {{ ($filterRole ?? '') === 'kasir' ? 'selected' : '' }}>Kasir</option>
                    <option value="pelanggan" {{ ($filterRole ?? '') === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                </select>
                <select name="status" class="bg-gray-50 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 p-2.5 w-full sm:w-auto transition-colors">
                    <option value="semua" {{ ($filterStatus ?? '') === 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="aktif" {{ ($filterStatus ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ ($filterStatus ?? '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="w-full sm:w-auto bg-gray-900 dark:bg-slate-700 text-white dark:text-white px-5 py-2.5 rounded-xl font-bold hover:bg-gray-800 dark:hover:bg-slate-600 transition-colors text-sm shadow-sm">
                    Terapkan
                </button>
                @if(request('search') || request('role') || request('status'))
                    <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto text-center px-4 py-2.5 text-sm font-bold text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/20 rounded-xl transition-colors">Reset</a>
                @endif
            </form>
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
                <i class="fa-solid fa-check-double mr-2"></i> <span x-text="selectedIds.length"></span> User Terpilih
            </div>
            <button type="button" @click.prevent="if(confirm('Apakah Anda yakin ingin menghapus ' + selectedIds.length + ' data user sekaligus?')) document.getElementById('bulkDeleteForm').submit();" class="bg-red-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md hover:bg-red-700 transition-colors w-full sm:w-auto">
                <i class="fa-solid fa-trash mr-2"></i> Hapus Sekaligus
            </button>
        </div>

        <form action="{{ route('admin.users.destroyBulk') }}" method="POST" id="bulkDeleteForm">
            @csrf
            @method('DELETE')

        {{-- Tabel User --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold text-center w-12">
                                <input type="checkbox" @change="selectedIds = $event.target.checked ? [{{ $users->pluck('id')->join(',') }}] : []" :checked="selectedIds.length === {{ $users->count() }} && {{ $users->count() }} > 0" class="rounded border-gray-300 text-red-600 focus:ring-red-500 bg-white dark:bg-slate-900">
                            </th>
                            <th class="px-6 py-4 font-semibold">Nama Lengkap</th>
                            <th class="px-6 py-4 font-semibold">Username</th>
                            <th class="px-6 py-4 font-semibold">Email</th>
                            <th class="px-6 py-4 font-semibold">Role</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-slate-800/80">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors {{ $user->id === auth()->id() ? 'bg-blue-50/20 dark:bg-blue-900/10' : '' }}" :class="selectedIds.includes({{ $user->id }}) ? 'bg-red-50/30 dark:bg-red-900/10' : ''">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $user->id }}" x-model="selectedIds" class="rounded border-gray-300 text-red-600 focus:ring-red-500 bg-white dark:bg-slate-900" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">
                                    {{ $user->nama_lengkap }}
                                    @if($user->id === auth()->id())
                                        <span class="inline-block ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-[10px] rounded-full">Anda</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $user->username }}</td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    @if($user->role === 'admin')
                                        <span class="bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase">Admin</span>
                                    @elseif($user->role === 'kasir')
                                        <span class="bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase">Kasir</span>
                                    @else
                                        <span class="bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-md text-[10px] font-bold uppercase">Pelanggan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->is_active)
                                        <span class="bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase">Aktif</span>
                                    @else
                                        <span class="bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST"
                                                onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} user {{ $user->nama_lengkap }}?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg {{ $user->is_active ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50' : 'bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/50' }} transition-colors"
                                                    title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                                    <i class="fa-solid {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }} text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <button @click="openEdit({{ $user->toJson() }})"
                                            class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 flex items-center justify-center hover:bg-blue-500 hover:text-white dark:hover:bg-blue-500 dark:hover:text-white transition-colors border border-transparent dark:border-slate-700" title="Edit User">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        @if($user->id !== auth()->id())
                                            <button type="button" @click="openDeleteModal('{{ addslashes($user->nama_lengkap) }}', '{{ route('admin.users.destroy', $user->id) }}')" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 flex items-center justify-center hover:bg-red-500 hover:text-white dark:hover:bg-red-500 dark:hover:text-white transition-colors border border-transparent" title="Hapus User">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-users text-4xl mb-3 text-gray-300 dark:text-slate-600"></i>
                                        @if(request('search') || request('role') || request('status'))
                                            <p class="font-medium text-gray-600 dark:text-gray-400">Pencarian untuk filter ini tidak ditemukan.</p>
                                        @else
                                            <p class="font-medium text-gray-600 dark:text-gray-400">Belum ada data pengguna.</p>
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

        @if($users->hasPages())
            <div class="mb-8 mt-6">
                {{ $users->links() }}
            </div>
        @endif

        {{-- MODAL TAMBAH USER --}}
        <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showCreateModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="showCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-8"
                    class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-slate-100 dark:border-slate-800">
                    
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-user-plus mr-2 text-blue-500"></i>Tambah User Baru</h3>
                    </div>

                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        <div class="px-6 py-5 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" required class="w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Username</label>
                                <input type="text" name="username" required class="w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Email</label>
                                <input type="email" name="email" required class="w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Password</label>
                                <input type="password" name="password" required minlength="6" class="w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Role</label>
                                <select name="role" required class="w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="pelanggan">Pelanggan</option>
                                    <option value="kasir">Kasir</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/50 flex justify-end gap-3 border-t border-gray-100 dark:border-slate-800">
                            <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-700 dark:text-gray-200 rounded-xl font-bold hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors text-sm">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-md transition-all text-sm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT USER --}}
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-8"
                    class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-slate-100 dark:border-slate-800">
                    
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-pen-to-square mr-2 text-blue-500"></i>Edit User</h3>
                    </div>

                    <form method="POST" :action="'{{ url('admin/users') }}/' + editUser.id">
                        @csrf
                        @method('PUT')
                        <div class="px-6 py-5 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" x-model="editUser.nama_lengkap" required class="w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Email</label>
                                <input type="email" name="email" x-model="editUser.email" required class="w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Password Baru <span class="text-gray-400 normal-case font-normal">(kosongkan jika tidak ingin ubah)</span></label>
                                <input type="password" name="password" x-model="editUser.password" minlength="6" class="w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Role</label>
                                <select name="role" x-model="editUser.role" required class="w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="pelanggan">Pelanggan</option>
                                    <option value="kasir">Kasir</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/50 flex justify-end gap-3 border-t border-gray-100 dark:border-slate-800">
                            <button type="button" @click="showEditModal = false" class="px-5 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-700 dark:text-gray-200 rounded-xl font-bold hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors text-sm">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-md transition-all text-sm">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS USER --}}
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
                        <p class="text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus user <br><span class="font-bold text-gray-800 dark:text-gray-200" x-text="deleteItem.name"></span>?<br> Data yang dihapus tidak dapat dikembalikan.</p>
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

    </div>
</x-app-layout>
