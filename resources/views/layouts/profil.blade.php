{{-- ========================================== --}}
{{-- MODAL UPDATE PROFIL (CENTERED / NOT FULL) --}}
{{-- ========================================== --}}
<div 
    @open-profile-modal.window="showProfileModal = true"
    x-show="showProfileModal"
    style="display: none;"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"

    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
>

    <div class="w-full max-w-3xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">

        {{-- HEADER --}}
        <div class="px-6 sm:px-8 py-5 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center transition-colors">
            <h3 class="text-xl font-black text-gray-800 dark:text-white tracking-wide transition-colors" id="modal-title">
                <i class="fa-solid fa-user-pen mr-3 text-blue-600 dark:text-blue-500 transition-colors"></i>
                PENGATURAN AKUN
            </h3>

            <button 
                @click="showProfileModal = false"
                class="text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 bg-gray-50 dark:bg-slate-800 hover:bg-red-50 dark:hover:bg-red-900/20 w-10 h-10 rounded-full flex items-center justify-center transition-colors"
            >
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        {{-- BODY --}}
        <div class="p-6 sm:p-10 max-h-[80vh] overflow-y-auto">

            <div class="mb-8">
                <h4 class="text-lg font-bold text-gray-900 dark:text-white transition-colors">
                    Informasi Pribadi & Kredensial
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors">
                    Perbarui detail identitas dan akses masuk Anda di sini.
                </p>
            </div>

            <form action="{{ route('profile.update.global') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- NAMA & USERNAME --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500 transition-colors">
                                <i class="fa-solid fa-id-card"></i>
                            </div>
                            <input 
                                type="text" 
                                name="nama_lengkap" 
                                value="{{ Auth::user()->nama_lengkap }}" 
                                required
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:bg-white dark:focus:bg-slate-900 text-sm transition-all"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500 transition-colors">
                                <i class="fa-solid fa-at"></i>
                            </div>
                            <input 
                                type="text" 
                                name="username" 
                                value="{{ Auth::user()->username }}" 
                                required
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:bg-white dark:focus:bg-slate-900 text-sm transition-all"
                            >
                        </div>
                    </div>
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500 transition-colors">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ Auth::user()->email }}" 
                            required
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:bg-white dark:focus:bg-slate-900 text-sm transition-all"
                        >
                    </div>
                </div>

                <hr class="border-gray-100 dark:border-slate-800 border-dashed my-8 transition-colors">

                {{-- KEAMANAN --}}
                <div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white transition-colors">
                        Keamanan Sandi
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors">
                        Biarkan kosong jika tidak ingin mengubah password.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 transition-colors">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors">
                            Password Baru
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500 transition-colors">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                placeholder="Minimal 8 karakter"
                                class="w-full pl-11 pr-4 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 text-sm transition-all"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 transition-colors">
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 dark:text-gray-500 transition-colors">
                                <i class="fa-solid fa-shield-check"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                placeholder="Ulangi sandi baru Anda"
                                class="w-full pl-11 pr-4 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 text-sm transition-all"
                            >
                        </div>
                    </div>
                </div>

                {{-- BUTTON --}}
                <div class="pt-8 flex justify-end space-x-4">
                    <button 
                        type="button"
                        @click="showProfileModal = false"
                        class="px-8 py-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl font-bold hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors text-sm"
                    >
                        Batalkan
                    </button>

                    <button 
                        type="submit"
                        class="px-8 py-3 bg-blue-600 dark:bg-blue-700 text-white rounded-xl font-bold hover:bg-blue-700 dark:hover:bg-blue-600 shadow-lg shadow-blue-200 dark:shadow-none transition-all text-sm flex items-center"
                    >
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Simpan Semua Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>