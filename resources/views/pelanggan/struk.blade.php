<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pesanan {{ $transaksi->kode_transaksi }} - Susi Stationary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background-color: white !important;
            }

            .print-border {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-slate-900 text-gray-800 dark:text-gray-200 font-sans antialiased p-4 sm:p-8 transition-colors">

    <div class="max-w-3xl mx-auto mb-6 flex justify-between items-center no-print">
        <button onclick="window.close()" class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white font-medium transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Tutup Tab
        </button>
        <button onclick="window.print()" class="bg-blue-600 dark:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-blue-700 dark:hover:bg-blue-600 shadow-md dark:shadow-none transition-colors flex items-center">
            <i class="fa-solid fa-print mr-2"></i> Cetak / Simpan PDF
        </button>
    </div>

    <div class="max-w-3xl mx-auto bg-white dark:bg-slate-800 p-8 sm:p-12 rounded-2xl shadow-lg border border-gray-200 dark:border-slate-700 print-border relative overflow-hidden transition-colors">

        @if(in_array($transaksi->status, ['Selesai', 'Berhasil']))
        <div class="absolute top-20 right-10 border-4 border-green-500 text-green-500 font-bold text-4xl uppercase tracking-widest px-4 py-2 rounded-lg opacity-20 transform rotate-12 pointer-events-none select-none">
            SELESAI
        </div>
        @else
        <div class="absolute top-20 right-10 border-4 border-yellow-500 text-yellow-500 font-bold text-4xl uppercase tracking-widest px-4 py-2 rounded-lg opacity-20 transform rotate-12 pointer-events-none select-none">
            {{ $transaksi->status }}
        </div>
        @endif

        @php $toko = \App\Models\Pengaturan::first(); @endphp

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b-2 border-gray-100 dark:border-slate-700 mb-8 gap-4 transition-colors">
            <div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white tracking-wider uppercase flex items-center transition-colors">
                    <i class="fa-solid fa-store text-blue-600 dark:text-blue-500 mr-3"></i> {{ $toko->nama_toko ?? 'SUSI STATIONARY' }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors">{{ $toko->alamat ?? 'Jl. Pramuka, Bengkalis' }} | Telp: {{ $toko->no_telp ?? '0812-3456-7890' }}</p>
            </div>
            <div class="text-left sm:text-right">
                <h2 class="text-xl font-bold text-gray-300 dark:text-gray-600 uppercase tracking-widest transition-colors">INVOICE</h2>
                <p class="text-sm font-bold text-gray-800 dark:text-gray-200 mt-1 transition-colors">{{ $transaksi->kode_transaksi }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 transition-colors">Tgl: {{ \Carbon\Carbon::parse($transaksi->created_at)->locale('id')->translatedFormat('d F Y') }}</p>
            </div>
        </div>
        <div class="mb-8">
            <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2 transition-colors">Ditagihkan Kepada:</h3>
            <p class="text-base font-bold text-gray-800 dark:text-white transition-colors">{{ $transaksi->pelanggan->nama_lengkap ?? $transaksi->nama_pelanggan }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 transition-colors">Pelanggan Online</p>
        </div>

        <table class="w-full text-left border-collapse mb-8 transition-colors">
            <thead>
                <tr class="bg-gray-50 dark:bg-slate-700/50 transition-colors">
                    <th class="py-3 px-4 font-bold text-gray-700 dark:text-gray-300 text-sm border-y border-gray-200 dark:border-slate-600 transition-colors">Deskripsi Layanan</th>
                    <th class="py-3 px-4 font-bold text-gray-700 dark:text-gray-300 text-sm border-y border-gray-200 dark:border-slate-600 text-center transition-colors">Qty</th>
                    <th class="py-3 px-4 font-bold text-gray-700 dark:text-gray-300 text-sm border-y border-gray-200 dark:border-slate-600 text-right transition-colors">Harga/Satuan</th>
                    <th class="py-3 px-4 font-bold text-gray-700 dark:text-gray-300 text-sm border-y border-gray-200 dark:border-slate-600 text-right transition-colors">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->detail as $detail)
                <tr>
                    <td class="py-4 px-4 border-b border-gray-100 dark:border-slate-700 transition-colors">
                        <p class="font-bold text-gray-800 dark:text-white transition-colors">{{ $detail->nama_item ?? 'Layanan Custom' }}</p>
                        @if($detail->opsi && $detail->opsi->count() > 0)
                            <div class="mt-1 text-[10px] text-gray-500 dark:text-gray-400">
                                @foreach($detail->opsi as $opsi)
                                    <p>- {{ $opsi->kategori }}: {{ $opsi->nama_opsi }}</p>
                                @endforeach
                            </div>
                        @endif
                        @if($detail->catatan)
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 italic transition-colors">Catatan: "{{ $detail->catatan }}"</p>
                        @endif
                    </td>
                    <td class="py-4 px-4 border-b border-gray-100 dark:border-slate-700 text-center text-gray-800 dark:text-white transition-colors">
                        {{ $detail->qty ?? 1 }}
                    </td>
                    <td class="py-4 px-4 border-b border-gray-100 dark:border-slate-700 text-right text-gray-800 dark:text-white transition-colors">
                        Rp {{ number_format($detail->harga_satuan ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-4 border-b border-gray-100 dark:border-slate-700 text-right font-bold text-gray-800 dark:text-white transition-colors">
                        Rp {{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Summary --}}
        <div class="border-t-2 border-gray-200 dark:border-slate-600 pt-4 space-y-2 mt-4">
            <div class="flex justify-between text-lg font-bold pt-2 border-t border-dashed border-gray-300 dark:border-slate-600">
                <span class="text-gray-900 dark:text-white">TOTAL KESELURUHAN</span>
                <span class="text-blue-600 dark:text-blue-400">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="text-center pt-8 border-t border-gray-100 dark:border-slate-700 text-sm text-gray-500 dark:text-gray-400 transition-colors mt-8">
            <p class="font-bold text-gray-800 dark:text-white mb-1 transition-colors">Terima kasih atas pesanan Anda!</p>
            <p>{{ $toko->pesan_penutup ?? 'Silakan tunjukkan struk ini (digital/cetak) kepada kasir saat mengambil pesanan Anda.' }}</p>
        </div>

    </div>

</body>

</html>