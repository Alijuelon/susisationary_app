<x-app-layout>
    <x-slot name="header">Dashboard Saya</x-slot>

    <div class="w-full">
        
        <div class="bg-gradient-to-br from-blue-600 to-blue-800 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-lg p-6 sm:p-8 mb-6 text-white flex flex-col md:flex-row items-center justify-between relative overflow-hidden transition-colors">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-10"></div>
            
            <div class="z-10 text-center md:text-left mb-6 md:mb-0">
                <h2 class="text-2xl sm:text-3xl font-bold mb-2">Halo, {{ explode(' ', Auth::user()->nama_lengkap)[0] }}! 👋</h2>
                <p class="text-blue-100 dark:text-gray-300">Cetak dokumen Anda tanpa perlu antre lama di toko.</p>
            </div>
            
            <div class="z-10 w-full md:w-auto">
                <a href="{{ route('pelanggan.pesanan.create') }}" class="block w-full text-center bg-white dark:bg-slate-700 text-blue-700 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-600 font-bold py-3 px-8 rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Pesan Cetak Baru
                </a>
            </div>
        </div>

        <!-- Modul Antrian -->
        <div class="mb-6" x-data="{ showGuideModal: false }">
            @if($antrianAktif)
                <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl p-1 shadow-lg relative overflow-hidden transition-all duration-300 transform hover:scale-[1.01]">
                    <div class="bg-white dark:bg-slate-900 rounded-xl p-6 relative z-10 flex flex-col md:flex-row items-center justify-between backdrop-blur-xl bg-opacity-90 dark:bg-opacity-90">
                        <div class="flex items-center mb-4 md:mb-0">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/50 dark:to-purple-900/50 flex items-center justify-center mr-6 border-4 border-white dark:border-slate-800 shadow-inner">
                                <span class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-br from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">{{ $antrianAktif->queue_number }}</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">Status Antrian</p>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                                    {{ $antrianAktif->status->label() }}
                                    @if($antrianAktif->status === \App\Enums\QueueStatus::MENUNGGU)
                                        <span class="ml-3 flex h-3 w-3 relative">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                                        </span>
                                    @elseif($antrianAktif->status === \App\Enums\QueueStatus::DIPROSES)
                                        <span class="ml-3 flex h-3 w-3 relative">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                                        </span>
                                    @endif
                                </h3>
                                @if($antrianAktif->status === \App\Enums\QueueStatus::MENUNGGU)
                                    <p class="text-sm text-indigo-600 dark:text-indigo-400 font-medium mt-1">
                                        <i class="fa-solid fa-stopwatch mr-1"></i> Estimasi Tunggu: {{ $antrianAktif->estimated_wait_time }} Menit
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="flex space-x-3 w-full md:w-auto">
                            <button type="button" @click="showGuideModal = true" class="px-4 py-3 bg-gray-50 dark:bg-slate-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 font-bold rounded-xl transition-colors border border-gray-200 dark:border-slate-700" title="Panduan Antrian">
                                <i class="fa-solid fa-circle-question"></i>
                            </button>
                            @if($antrianAktif->status === \App\Enums\QueueStatus::MENUNGGU)
                                <form action="{{ route('pelanggan.queue.cancel', $antrianAktif->id) }}" method="POST" class="w-full md:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan antrian ini?');">
                                    @csrf
                                    <button type="submit" class="w-full md:w-auto px-6 py-3 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 font-bold rounded-xl transition-colors border border-red-200 dark:border-red-800/50 flex items-center justify-center">
                                        <i class="fa-solid fa-xmark mr-2"></i> Batalkan Antrian
                                    </button>
                                </form>
                            @else
                                <div class="px-6 py-3 bg-gray-50 dark:bg-slate-800 text-gray-500 dark:text-gray-400 font-bold rounded-xl border border-gray-200 dark:border-slate-700 flex items-center justify-center">
                                    <i class="fa-solid fa-lock mr-2"></i> Tidak Dapat Dibatalkan
                                </div>
                            @endif
                            <button onclick="window.location.reload()" class="px-4 py-3 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 font-bold rounded-xl transition-colors border border-indigo-200 dark:border-indigo-800/50">
                                <i class="fa-solid fa-rotate-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 flex flex-col md:flex-row items-center justify-between relative overflow-hidden transition-colors group hover:border-indigo-300 dark:hover:border-indigo-700">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    
                    <div class="z-10 flex items-center mb-4 md:mb-0">
                        <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center text-xl mr-4 shadow-sm">
                            <i class="fa-solid fa-ticket"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Ambil Antrian Toko</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pesan antrian secara online sebelum Anda tiba di toko.</p>
                        </div>
                    </div>
                    
                    <div class="z-10 flex space-x-3 w-full md:w-auto mt-4 md:mt-0">
                        <button type="button" @click="showGuideModal = true" class="px-4 py-3 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors border border-transparent" title="Panduan Antrian">
                            <i class="fa-solid fa-circle-question"></i>
                        </button>
                        <form action="{{ route('pelanggan.queue.take') }}" method="POST" class="w-full md:w-auto">
                            @csrf
                            <button type="submit" class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 dark:shadow-indigo-900/20 transition-all transform hover:-translate-y-0.5 flex items-center justify-center">
                                <i class="fa-solid fa-hand-pointer mr-2"></i> Ambil Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- MODAL PANDUAN PELANGGAN --}}
            <div x-show="showGuideModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showGuideModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-60 dark:bg-opacity-80 transition-opacity" @click="showGuideModal = false" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div x-show="showGuideModal" 
                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100 dark:border-slate-800">
                        
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                                <i class="fa-solid fa-circle-info text-indigo-500 mr-2"></i> Panduan Antrian Online
                            </h3>
                            <button type="button" @click="showGuideModal = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="px-6 py-6 text-sm text-gray-600 dark:text-gray-300 space-y-4">
                            <div class="flex">
                                <div class="flex-shrink-0 mt-1"><i class="fa-solid fa-1 text-indigo-500 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-900/30 w-7 h-7 rounded-full flex items-center justify-center"></i></div>
                                <div class="ml-3"><p><strong class="text-gray-800 dark:text-white">Ambil Antrian</strong><br>Klik tombol "Ambil Sekarang" untuk mendapatkan nomor antrian Anda secara online. Anda tidak perlu repot-repot datang ke toko hanya untuk mengambil nomor antrean.</p></div>
                            </div>
                            <div class="flex">
                                <div class="flex-shrink-0 mt-1"><i class="fa-solid fa-2 text-indigo-500 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-900/30 w-7 h-7 rounded-full flex items-center justify-center"></i></div>
                                <div class="ml-3"><p><strong class="text-gray-800 dark:text-white">Pantau Status</strong><br>Setelah mendaftar, Anda dapat memantau status antrian (Menunggu / Diproses) dan estimasi waktu tunggu secara langsung (Live) dari dashboard ini.</p></div>
                            </div>
                            <div class="flex">
                                <div class="flex-shrink-0 mt-1"><i class="fa-solid fa-3 text-indigo-500 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-900/30 w-7 h-7 rounded-full flex items-center justify-center"></i></div>
                                <div class="ml-3"><p><strong class="text-gray-800 dark:text-white">Datang ke Toko</strong><br>Silakan datang ke toko ketika status antrian Anda sudah dekat atau berubah menjadi "Diproses". Pastikan Anda ada di lokasi saat dipanggil oleh kasir.</p></div>
                            </div>
                            <div class="flex">
                                <div class="flex-shrink-0 mt-1"><i class="fa-solid fa-4 text-indigo-500 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-900/30 w-7 h-7 rounded-full flex items-center justify-center"></i></div>
                                <div class="ml-3"><p><strong class="text-gray-800 dark:text-white">Pembatalan</strong><br>Anda dapat membatalkan nomor antrian kapan saja, selama statusnya masih "Menunggu".</p></div>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/80 rounded-b-2xl border-t border-gray-100 dark:border-slate-800 text-center">
                            <button type="button" @click="showGuideModal = false" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-md transition-colors text-sm w-full sm:w-auto">Mengerti</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-800 flex items-center transition-colors">
                <div class="w-14 h-14 bg-yellow-100 dark:bg-yellow-900/40 text-yellow-600 dark:text-yellow-400 rounded-xl flex items-center justify-center text-2xl font-bold mr-4"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <div><p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Pesanan Aktif</p><h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ collect($pesananAktif ?? [])->count() }}</h3></div>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-800 flex items-center transition-colors">
                <div class="w-14 h-14 bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 rounded-xl flex items-center justify-center text-2xl font-bold mr-4"><i class="fa-solid fa-check-double"></i></div>
                <div><p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Pesanan Selesai</p><h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $pesananSelesai ?? 0 }}</h3></div>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-800 flex items-center hidden lg:flex transition-colors">
                <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-2xl font-bold mr-4"><i class="fa-solid fa-file-invoice"></i></div>
                <div><p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Pemesanan</p><h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalPesanan ?? 0 }}</h3></div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 transition-colors"><h6 class="font-bold text-gray-800 dark:text-white text-base">Status Pesanan Berjalan</h6></div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold">ID Transaksi</th>
                            <th class="px-6 py-4 font-semibold">Layanan</th>
                            <th class="px-6 py-4 font-semibold">Waktu Pemesanan</th>
                            <th class="px-6 py-4 font-semibold text-center">Status Pengerjaan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-slate-800/80">
                        @forelse($pesananAktif ?? [] as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">#{{ $item->id }}</td>
                                <td class="px-6 py-4">{{ $item->layanan->nama_layanan ?? 'Custom' }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->status === 'Menunggu') <span class="bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors"><i class="fa-solid fa-hourglass-half mr-1"></i> Menunggu</span>
                                    @elseif($item->status === 'Diproses') <span class="bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors"><i class="fa-solid fa-spinner animate-spin mr-1"></i> Diproses</span>
                                    @elseif($item->status === 'Siap Diambil') <span class="bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase transition-colors"><i class="fa-solid fa-box mr-1"></i> Siap Diambil</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400"><i class="fa-solid fa-mug-hot text-3xl mb-3 text-gray-300 dark:text-slate-600"></i><p>Tidak ada pesanan yang sedang berjalan.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>