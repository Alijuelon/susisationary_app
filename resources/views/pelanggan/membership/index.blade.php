<x-app-layout>
    <x-slot name="header">Keanggotaan Saya</x-slot>

    <div class="w-full max-w-2xl" x-data="{
        showConfirmModal: false,
        openConfirmModal() {
            this.showConfirmModal = true;
        }
    }">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 transition-colors">Status keanggotaan Anda di Susi Stationary. Dapatkan diskon spesial di setiap transaksi!</p>

        @if($membership)
            {{-- Status Card --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
                <div class="p-6 sm:p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Kartu Keanggotaan</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Susi Stationary Member</p>
                        </div>
                        @if($membership->status === 'menunggu')
                            <span class="bg-yellow-100 dark:bg-yellow-900/40 text-yellow-600 dark:text-yellow-400 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wide">
                                <i class="fa-solid fa-clock mr-1"></i> Menunggu Persetujuan
                            </span>
                        @elseif($membership->status === 'aktif')
                            <span class="bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wide">
                                <i class="fa-solid fa-check-circle mr-1"></i> Aktif
                            </span>
                        @else
                            <span class="bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wide">
                                <i class="fa-solid fa-times-circle mr-1"></i> Nonaktif
                            </span>
                        @endif
                    </div>

                    {{-- Card Visual --}}
                    <div class="bg-gradient-to-br from-gray-900 to-gray-700 dark:from-slate-800 dark:to-slate-700 rounded-2xl p-6 text-white shadow-xl mb-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-10 translate-x-10"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-8 -translate-x-8"></div>
                        
                        <div class="flex justify-between items-start relative z-10 mb-8">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Member Card</p>
                                <h4 class="text-lg font-bold mt-1">SUSI STATIONARY</h4>
                            </div>
                            <i class="fa-solid fa-id-card text-3xl text-gray-400"></i>
                        </div>
                        
                        <div class="relative z-10">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Nomor Kartu</p>
                            <p class="text-xl font-bold font-mono tracking-widest">{{ $membership->no_kartu }}</p>
                        </div>
                        
                        <div class="flex justify-between items-end mt-4 relative z-10">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Nama</p>
                                <p class="text-sm font-bold">{{ Auth::user()->nama_lengkap }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Sejak</p>
                                <p class="text-sm font-bold">{{ $membership->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($membership->status === 'menunggu')
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800/50 text-yellow-700 dark:text-yellow-400 px-5 py-4 rounded-xl text-sm transition-colors">
                            <p class="font-bold mb-1"><i class="fa-solid fa-hourglass-half mr-2"></i>Menunggu Persetujuan</p>
                            <p class="text-xs">Permohonan Anda sedang diproses oleh kasir/admin. Silakan kembali lagi nanti untuk cek status.</p>
                        </div>
                    @elseif($membership->status === 'aktif' && $pengaturan && $pengaturan->membership_aktif)
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 px-5 py-4 rounded-xl text-sm transition-colors">
                            <p class="font-bold mb-1"><i class="fa-solid fa-party-horn mr-2"></i>Selamat! Anda Member Aktif</p>
                            <p class="text-xs">Anda mendapatkan diskon <strong>{{ $pengaturan->diskon_member }}%</strong> untuk setiap transaksi. Cukup sebutkan nama atau nomor kartu Anda saat bertransaksi di kasir.</p>
                        </div>
                    @elseif($membership->status === 'nonaktif')
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-400 px-5 py-4 rounded-xl text-sm transition-colors">
                            <p class="font-bold mb-1"><i class="fa-solid fa-circle-xmark mr-2"></i>Membership Nonaktif</p>
                            <p class="text-xs">Membership Anda telah dinonaktifkan. Hubungi admin/kasir untuk informasi lebih lanjut.</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            {{-- Belum Punya Membership --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors text-center p-8 sm:p-12">
                <div class="w-20 h-20 bg-gray-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-id-card text-3xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Belum Menjadi Member</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">Daftar sebagai member Susi Stationary dan dapatkan diskon spesial di setiap transaksi Anda!</p>

                @if($pengaturan && $pengaturan->membership_aktif)
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-400 px-5 py-4 rounded-xl text-sm mb-6 max-w-md mx-auto text-left transition-colors">
                        <p class="font-bold mb-1"><i class="fa-solid fa-gift mr-2"></i>Keuntungan Member:</p>
                        <p class="text-xs">Diskon <strong>{{ $pengaturan->diskon_member }}%</strong> untuk setiap transaksi di Susi Stationary.</p>
                    </div>

                    <button type="button" @click="openConfirmModal()" class="bg-blue-600 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 dark:shadow-blue-900/20 transition-all text-sm hover:-translate-y-0.5">
                        <i class="fa-solid fa-user-plus mr-2"></i> Daftar Membership Sekarang
                    </button>
                @else
                    <div class="bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-gray-400 px-5 py-4 rounded-xl text-sm max-w-md mx-auto transition-colors">
                        <p><i class="fa-solid fa-circle-pause mr-2"></i>Program membership sedang tidak aktif saat ini. Silakan kembali lagi nanti.</p>
                    </div>
                @endif
            </div>
        @endif

        {{-- MODAL KONFIRMASI DAFTAR --}}
        <div x-show="showConfirmModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showConfirmModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-60 dark:bg-opacity-80 transition-opacity" @click="showConfirmModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showConfirmModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full border border-gray-100 dark:border-slate-800">
                    
                    <div class="px-6 py-6 text-center">
                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white dark:border-slate-800 shadow-sm transition-colors">
                            <i class="fa-solid fa-user-plus text-2xl text-blue-500 dark:text-blue-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Konfirmasi Pendaftaran</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Daftarkan diri Anda sebagai member sekarang untuk mulai mendapatkan keuntungan diskon khusus?</p>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/80 flex justify-center space-x-3 rounded-b-2xl border-t border-gray-100 dark:border-slate-800 transition-colors">
                        <button type="button" @click="showConfirmModal = false" class="w-1/2 px-5 py-2.5 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors text-sm">Batal</button>
                        
                        <form action="{{ route('pelanggan.membership.store') }}" method="POST" class="w-1/2 m-0">
                            @csrf
                            <button type="submit" class="w-full px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-md transition-colors text-sm">Ya, Daftar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
