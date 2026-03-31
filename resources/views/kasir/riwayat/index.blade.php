<x-app-layout>
    <x-slot name="header">Riwayat Transaksi Kasir</x-slot>

    <div class="w-full" x-data="{ 
        showReceiptModal: false, 
        receiptData: { id: '', tanggal: '', total: 0, bayar: 0, kembalian: 0, kasir: '' },
        
        openReceipt(item, namaKasir) {
            this.receiptData = {
                id: item.id,
                tanggal: item.created_at,
                total: item.total_harga,
                bayar: item.uang_bayar,
                kembalian: item.kembalian,
                kasir: namaKasir
            };
            this.showReceiptModal = true;
        },

        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        },
        
        formatTanggal(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID') + ' ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }
    }">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Riwayat Penjualan Anda</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar seluruh transaksi yang telah Anda proses.</p>
            </div>

            <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3">
                <form action="{{ route('kasir.riwayat') }}" method="GET" class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari ID Transaksi..."
                        class="bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 text-sm rounded-xl focus:ring-gray-900 dark:focus:ring-white focus:border-gray-900 dark:focus:border-white block w-full pl-10 p-2.5 transition-colors">

                    @if($search)
                    <a href="{{ route('kasir.riwayat') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-red-500 hover:text-red-700 dark:hover:text-red-400">
                        <i class="fa-solid fa-times"></i>
                    </a>
                    @endif
                </form>

                <a href="{{ route('kasir.pos.index') }}" class="bg-blue-600 dark:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 dark:hover:bg-blue-600 shadow-md shadow-blue-200 dark:shadow-none transition-all flex items-center justify-center whitespace-nowrap">
                    <i class="fa-solid fa-cash-register mr-2"></i> Kembali ke POS
                </a>
            </div>
        </div>

        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-green-500 dark:bg-green-600 border border-green-600 dark:border-green-500 text-white px-5 py-4 rounded-xl shadow-md flex items-center justify-between transition-all">
            <div class="flex items-center space-x-3">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-white hover:text-green-200">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        @endif

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden mb-6 transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold">ID Transaksi</th>
                            <th class="px-6 py-4 font-semibold">Tanggal & Waktu</th>
                            <th class="px-6 py-4 font-semibold text-right">Total Tagihan</th>
                            <th class="px-6 py-4 font-semibold text-right">Uang Diterima</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse($riwayat as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">#{{ $item->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d M Y') }}</span> <br>
                                <span class="text-xs text-gray-400 dark:text-gray-500"><i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800 dark:text-white">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-medium text-gray-500 dark:text-gray-400">Rp {{ number_format($item->uang_bayar, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider transition-colors">{{ $item->status }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button @click="openReceipt({{ json_encode($item) }}, '{{ Auth::user()->nama_lengkap }}')" class="bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-900 dark:hover:bg-gray-200 hover:text-white dark:hover:text-slate-900 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center border border-transparent dark:border-slate-700">
                                    <i class="fa-solid fa-receipt mr-1.5"></i> Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-receipt text-4xl mb-3 text-gray-300 dark:text-slate-600"></i>
                                    @if($search)
                                    <p class="font-medium text-gray-600 dark:text-gray-400">Pencarian ID "{{ $search }}" tidak ditemukan.</p>
                                    @else
                                    <p class="font-medium text-gray-600 dark:text-gray-400">Belum ada riwayat transaksi.</p>
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
            {{ $riwayat->links() }}
        </div>

        <div x-show="showReceiptModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">

                <div x-show="showReceiptModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-60 dark:bg-opacity-80 transition-opacity" @click="showReceiptModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showReceiptModal"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full border border-gray-200 dark:border-slate-800 relative">

                    @php $toko = \App\Models\Pengaturan::first(); @endphp

                    <div id="print-area" class="p-6 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-200 font-mono text-sm transition-colors">
                        
                        <div class="text-center mb-4 border-b-2 border-dashed border-gray-300 dark:border-slate-700 pb-4 transition-colors">
                            <h2 class="text-xl font-bold tracking-widest uppercase text-gray-900 dark:text-white">{{ $toko->nama_toko ?? 'SUSI STATIONARY' }}</h2>
                            <p class="text-xs mt-1 text-gray-600 dark:text-gray-400">{{ $toko->alamat ?? 'Jl. Pramuka, Bengkalis' }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Telp: {{ $toko->no_telp ?? '0812-3456-7890' }}</p>
                        </div>

                        <div class="mb-4 text-xs text-gray-700 dark:text-gray-300">
                            <div class="flex justify-between mb-1">
                                <span>No. TRX:</span>
                                <span class="font-bold text-gray-900 dark:text-white">#<span x-text="receiptData.id"></span></span>
                            </div>
                            <div class="flex justify-between mb-1">
                                <span>Tanggal:</span>
                                <span x-text="formatTanggal(receiptData.tanggal)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Kasir:</span>
                                <span class="uppercase font-medium text-gray-900 dark:text-white" x-text="receiptData.kasir"></span>
                            </div>
                        </div>

                        <div class="border-t-2 border-dashed border-gray-300 dark:border-slate-700 pt-3 pb-3 mb-3 transition-colors">
                            <p class="text-center text-xs italic text-gray-500 dark:text-gray-500 mb-2">(Detail item pesanan dicatat di sistem)</p>
                        </div>

                        <div class="text-xs text-gray-700 dark:text-gray-300">
                            <div class="flex justify-between mb-1">
                                <span>TOTAL TAGIHAN:</span>
                                <span class="font-bold text-base text-gray-900 dark:text-white" x-text="formatRupiah(receiptData.total)"></span>
                            </div>
                            <div class="flex justify-between mb-1 text-gray-600 dark:text-gray-400">
                                <span>TUNAI / BAYAR:</span>
                                <span x-text="formatRupiah(receiptData.bayar)"></span>
                            </div>
                            <div class="border-t border-dashed border-gray-300 dark:border-slate-700 my-1 pt-1 flex justify-between transition-colors">
                                <span>KEMBALIAN:</span>
                                <span class="font-bold text-gray-900 dark:text-white" x-text="formatRupiah(receiptData.kembalian)"></span>
                            </div>
                        </div>

                        <div class="text-center mt-6 pt-4 border-t-2 border-dashed border-gray-300 dark:border-slate-700 transition-colors">
                            <p class="text-xs font-bold text-gray-900 dark:text-white">TERIMA KASIH</p>
                            <p class="text-[10px] mt-1 text-gray-500 dark:text-gray-500">{{ $toko->pesan_penutup ?? 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.' }}</p>
                        </div>

                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/80 flex justify-end space-x-3 rounded-b-xl border-t border-gray-100 dark:border-slate-800 no-print transition-colors">
                        <button type="button" @click="showReceiptModal = false" class="px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-lg font-bold hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors text-sm">Tutup</button>

                        <button type="button" onclick="printReceipt()" class="px-4 py-2 bg-gray-900 dark:bg-white text-white dark:text-slate-900 rounded-lg font-bold hover:bg-gray-800 dark:hover:bg-gray-200 shadow-md transition-colors text-sm flex items-center">
                            <i class="fa-solid fa-print mr-2"></i> Cetak Struk
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
                color: #000 !important;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>

    <script>
        function printReceipt() {
            window.print();
        }
    </script>
</x-app-layout>