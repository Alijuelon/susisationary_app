<x-guest-layout>
    <x-slot name="title">Daftar - Susi Stationary</x-slot>

    <div x-data="{ 
        step: {{ ($errors->has('email') || $errors->has('password') || $errors->has('password_confirmation')) ? 2 : 1 }}, 
        showP1: false, 
        showP2: false,
        
        // State untuk Pop-up Alert Custom
        showAlert: false,
        alertTitle: 'Perhatian!',
        alertMessage: '',
        
        nextStep() {
            const nama = document.getElementById('nama_lengkap').value;
            const username = document.getElementById('username').value;
            
            if(!nama || !username) {
                // Tampilkan Pop-up SweetAlert Tailwind
                this.alertMessage = 'Mohon isi Nama Lengkap dan Username terlebih dahulu sebelum melanjutkan ke langkah berikutnya.';
                this.showAlert = true;
                return;
            }
            this.step = 2;
        }
    }" class="relative w-full">

        <div class="mb-10 lg:mb-12">
            <h2 class="text-3xl lg:text-4xl font-black text-slate-900 dark:text-white tracking-tight leading-tight transition-colors">Buat Akun <br/><span class="text-blue-600 dark:text-blue-500">Baru</span></h2>
            <p class="text-sm md:text-base text-slate-500 dark:text-slate-400 mt-2 font-medium transition-colors">Bergabung untuk kemudahan mencetak dokumen.</p>
        </div>

        <div class="mb-8 relative px-4">
            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full z-0 transition-colors"></div>
            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1.5 bg-blue-600 dark:bg-blue-500 rounded-full z-0 transition-all duration-500" :class="step === 1 ? 'w-1/2' : 'w-full'"></div>
            
            <div class="relative z-10 flex justify-between">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold shadow-md transition-colors duration-300 text-sm"
                         :class="step === 1 || step === 2 ? 'bg-blue-600 dark:bg-blue-600 text-white ring-4 ring-blue-50 dark:ring-blue-900/30' : 'bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400'">1</div>
                    <span class="text-[10px] font-bold uppercase tracking-wider mt-2 transition-colors duration-300" :class="step === 1 || step === 2 ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500'">Profil</span>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold shadow-md transition-colors duration-300 text-sm"
                         :class="step === 2 ? 'bg-blue-600 dark:bg-blue-600 text-white ring-4 ring-blue-50 dark:ring-blue-900/30' : 'bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500'">2</div>
                    <span class="text-[10px] font-bold uppercase tracking-wider mt-2 transition-colors duration-300" :class="step === 2 ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500'">Akun & Sandi</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <div x-show="step === 1" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 -translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform absolute top-0 w-full"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-8"
                 class="space-y-5">
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 transition-colors">Nama Lengkap</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors">
                            <i class="fa-solid fa-id-card text-sm"></i>
                        </div>
                        <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" autofocus
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:bg-white dark:focus:bg-slate-900 focus:ring-[3px] focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all text-sm font-semibold input-transition text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500"
                            placeholder="Masukkan nama lengkap">
                    </div>
                    @error('nama_lengkap') <p class="text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 mt-4 transition-colors">Username</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors">
                            <i class="fa-solid fa-at text-sm"></i>
                        </div>
                        <input id="username" type="text" name="username" value="{{ old('username') }}"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:bg-white dark:focus:bg-slate-900 focus:ring-[3px] focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all text-sm font-semibold input-transition text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500"
                            placeholder="Pilih username">
                    </div>
                    @error('username') <p class="text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">{{ $message }}</p> @enderror
                </div>

                <button type="button" @click="nextStep()"
                    class="w-full mt-6 bg-blue-600 dark:bg-blue-700 text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 dark:hover:bg-blue-600 transform transition hover:-translate-y-0.5 shadow-lg shadow-blue-200 dark:shadow-none flex items-center justify-center gap-2 text-sm">
                    Selanjutnya <i class="fa-solid fa-arrow-right"></i>
                </button>

                <p class="text-center text-sm text-slate-500 dark:text-slate-400 mt-6 transition-colors">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline transition-colors">Login disini</a>
                </p>
            </div>


            <div x-show="step === 2" style="display: none;"
                 x-transition:enter="transition ease-out duration-300 transform delay-100"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform absolute top-0 w-full"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-8"
                 class="space-y-5 w-full">
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 transition-colors">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:bg-white dark:focus:bg-slate-900 focus:ring-[3px] focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all text-sm font-semibold input-transition text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500"
                            placeholder="email@contoh.com">
                    </div>
                    @error('email') <p class="text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 mt-4 transition-colors">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <input id="password" :type="showP1 ? 'text' : 'password'" name="password" required
                            class="w-full pl-11 pr-12 py-3.5 bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:bg-white dark:focus:bg-slate-900 focus:ring-[3px] focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all text-sm font-semibold input-transition text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500"
                            placeholder="••••••••">
                        <button type="button" @click="showP1 = !showP1" 
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            <i class="fa-solid" :class="showP1 ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 mt-4 transition-colors">Konfirmasi Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors">
                            <i class="fa-solid fa-shield-check text-sm"></i>
                        </div>
                        <input id="password_confirmation" :type="showP2 ? 'text' : 'password'" name="password_confirmation" required
                            class="w-full pl-11 pr-12 py-3.5 bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:bg-white dark:focus:bg-slate-900 focus:ring-[3px] focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all text-sm font-semibold input-transition text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500"
                            placeholder="••••••••">
                        <button type="button" @click="showP2 = !showP2" 
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            <i class="fa-solid" :class="showP2 ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex gap-4 mt-8 pt-2">
                    <button type="button" @click="step = 1"
                        class="w-1/3 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-2 border-slate-200 dark:border-slate-700 font-bold py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 transition duration-300 flex items-center justify-center gap-2 text-sm">
                        <i class="fa-solid fa-arrow-left"></i> Kembali
                    </button>
                    <button type="submit"
                        class="w-2/3 bg-blue-600 dark:bg-blue-700 text-white font-bold py-3 rounded-xl hover:bg-blue-700 dark:hover:bg-blue-600 transform transition hover:-translate-y-0.5 shadow-lg shadow-blue-200 dark:shadow-none text-sm">
                        DAFTAR SEKARANG
                    </button>
                </div>
            </div>
        </form>

        {{-- ========================================== --}}
        {{-- SWEET ALERT CUSTOM TAILWIND (ALPINE.JS)    --}}
        {{-- ========================================== --}}
        <div x-show="showAlert" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            
            <div x-show="showAlert" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/40 dark:bg-slate-900/80 backdrop-blur-sm transition-opacity" 
                 @click="showAlert = false"></div>

            <div x-show="showAlert" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-4" 
                 class="relative bg-white dark:bg-slate-800 rounded-3xl p-8 max-w-sm w-full shadow-2xl text-center transform transition-all border border-slate-100 dark:border-slate-700">
                
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-orange-50 dark:bg-orange-900/30 mb-6 border-4 border-white dark:border-slate-800 shadow-inner relative transition-colors">
                    <div class="absolute inset-0 rounded-full bg-orange-100 dark:bg-orange-500/20 animate-ping opacity-20"></div>
                    <i class="fa-solid fa-exclamation text-4xl text-orange-500 dark:text-orange-400 relative z-10 animate-bounce mt-1 transition-colors"></i>
                </div>
                
                <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors" x-text="alertTitle"></h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed font-medium transition-colors" x-text="alertMessage"></p>
                
                <button type="button" @click="showAlert = false" 
                    class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-blue-600 dark:bg-blue-700 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200 dark:shadow-none hover:bg-blue-700 dark:hover:bg-blue-600 hover:-translate-y-0.5 transition-all">
                    Mengerti <i class="fa-solid fa-check"></i>
                </button>
            </div>
        </div>
        {{-- AKHIR SWEET ALERT CUSTOM --}}

    </div>
</x-guest-layout>