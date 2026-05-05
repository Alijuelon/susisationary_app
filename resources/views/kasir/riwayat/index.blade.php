<x-app-layout>
    <x-slot name="header">Riwayat Transaksi Kasir</x-slot>

    <div class="w-full" x-data="{ 
        showReceiptModal: false, 
        receiptData: { id: '', tanggal: '', total: 0, bayar: 0, kembalian: 0, kasir: '' },
        selectedIds: [],
        
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
                <a href="{{ route('kasir.pos.index') }}" class="bg-blue-600 dark:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 dark:hover:bg-blue-600 shadow-md shadow-blue-200 dark:shadow-none transition-all flex items-center justify-center whitespace-nowrap">
                    <i class="fa-solid fa-cash-register mr-2"></i> POS
                </a>
            </div>
        </div>

        <!-- Filter Area -->
        <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-4 mb-6 shadow-sm">
            <form action="{{ route('kasir.riwayat') }}" method="GET" class="flex flex-wrap md:flex-nowrap gap-3 items-center w-full">
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
                        <option value="Berhasil" {{ request('status') == 'Berhasil' ? 'selected' : '' }}>Berhasil</option>
                        <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="relative w-full md:flex-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID/Pelanggan..." class="bg-gray-50 dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block w-full pl-10 p-2.5 dark:text-white">
                </div>
                <button type="submit" class="w-full sm:w-auto bg-gray-900 dark:bg-slate-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-800 dark:hover:bg-slate-600 transition-colors shadow-sm">
                    Filter
                </button>
                @if(request('search') || request('status') || request('tgl_mulai') || request('tgl_akhir'))
                    <a href="{{ route('kasir.riwayat') }}" class="w-full sm:w-auto text-center px-4 py-2.5 text-sm font-bold text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/20 rounded-xl transition-colors">Reset</a>
                @endif
            </form>
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
        
        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-red-500 dark:bg-red-600 text-white px-5 py-4 rounded-xl shadow-md flex items-center justify-between transition-all">
            <div class="flex items-center space-x-3">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-white hover:text-red-200">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        @endif

        <!-- Bulk Delete Action Bar -->
        <div x-show="selectedIds.length > 0" style="display: none;" x-transition class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-2xl p-4 mb-4 flex flex-col sm:flex-row justify-between items-center shadow-sm">
            <div class="text-red-800 dark:text-red-400 font-bold mb-3 sm:mb-0">
                <i class="fa-solid fa-check-double mr-2"></i> <span x-text="selectedIds.length"></span> Transaksi Terpilih
            </div>
            <button type="button" @click.prevent="if(confirm('Apakah Anda yakin ingin menghapus ' + selectedIds.length + ' transaksi sekaligus? Data tidak bisa dipulihkan.')) document.getElementById('bulkDeleteForm').submit();" class="bg-red-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md hover:bg-red-700 transition-colors w-full sm:w-auto">
                <i class="fa-solid fa-trash mr-2"></i> Hapus Sekaligus
            </button>
        </div>

        <form action="{{ route('kasir.riwayat.destroyBulk') }}" method="POST" id="bulkDeleteForm">
            @csrf
            @method('DELETE')

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden mb-6 transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider transition-colors">
                            <th class="px-6 py-4 font-semibold text-center w-12">
                                <input type="checkbox" @change="selectedIds = $event.target.checked ? [{{ $riwayat->pluck('id')->join(',') }}] : []" :checked="selectedIds.length === {{ $riwayat->count() }} && {{ $riwayat->count() }} > 0" class="rounded border-gray-300 text-red-600 focus:ring-red-500 bg-white dark:bg-slate-900">
                            </th>
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
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors" :class="selectedIds.includes({{ $item->id }}) ? 'bg-red-50/30 dark:bg-red-900/10' : ''">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" name="selected_ids[]" value="{{ $item->id }}" x-model="selectedIds" class="rounded border-gray-300 text-red-600 focus:ring-red-500 bg-white dark:bg-slate-900">
                            </td>
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
                                <div class="flex justify-center space-x-2">
                                    <button @click="openReceipt({{ json_encode($item) }}, '{{ Auth::user()->nama_lengkap }}')" class="bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-900 dark:hover:bg-gray-200 hover:text-white dark:hover:text-slate-900 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center border border-transparent dark:border-slate-700">
                                        <i class="fa-solid fa-receipt mr-1.5"></i> Detail
                                    </button>
                                    <form action="{{ route('kasir.riwayat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi penjualan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors inline-flex items-center border border-transparent" title="Hapus Riwayat">
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
                                    <i class="fa-solid fa-receipt text-4xl mb-3 text-gray-300 dark:text-slate-600"></i>
                                    @if(request('search') || request('status') || request('tgl_mulai'))
                                    <p class="font-medium text-gray-600 dark:text-gray-400">Pencarian untuk filter ini tidak ditemukan.</p>
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
        </form>

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