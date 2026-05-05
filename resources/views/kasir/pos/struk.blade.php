<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi {{ $transaksi->kode_transaksi }} - Susi Stationary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            .print-border { border: none !important; box-shadow: none !important; padding: 0 !important; }
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-slate-900 text-gray-800 dark:text-gray-200 font-sans antialiased p-4 sm:p-8 transition-colors">

    <div class="max-w-sm mx-auto mb-6 flex justify-between items-center no-print">
        <a href="{{ route('kasir.riwayat') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white text-sm font-medium transition-colors">
            <i class="fa-solid fa-arrow-left mr-1"></i> Riwayat
        </a>
        <button onclick="window.print()" class="bg-blue-600 dark:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-blue-700 dark:hover:bg-blue-600 shadow-md dark:shadow-none transition-colors flex items-center">
            <i class="fa-solid fa-print mr-2"></i> Cetak
        </button>
    </div>

    <div class="max-w-sm mx-auto bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-slate-700 print-border relative overflow-hidden transition-colors">

        {{-- Watermark --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 border-4 border-emerald-500 text-emerald-500 font-bold text-4xl uppercase tracking-widest px-4 py-2 rounded-lg opacity-10 transform -rotate-12 pointer-events-none select-none whitespace-nowrap">
            {{ $transaksi->status }}
        </div>

        {{-- Header Toko --}}
        <div class="text-center pb-4 border-b-2 border-dashed border-gray-200 dark:border-slate-700 mb-6 transition-colors">
            <h1 class="text-xl font-black text-gray-900 dark:text-white tracking-widest uppercase flex flex-col items-center justify-center transition-colors">
                <i class="fa-solid fa-store text-blue-600 dark:text-blue-500 text-2xl mb-2"></i>
                {{ $toko->nama_toko ?? 'SUSI STATIONARY' }}
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 transition-colors leading-relaxed">
                {{ $toko->alamat ?? '-' }}<br>Telp: {{ $toko->no_telp ?? '-' }}
            </p>
        </div>

        {{-- Info Transaksi --}}
        <div class="space-y-1 mb-6 text-xs text-gray-600 dark:text-gray-400">
            <div class="flex justify-between">
                <span>Waktu</span>
                <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span>No. TRX</span>
                <span class="text-gray-800 dark:text-gray-200 font-bold">{{ $transaksi->kode_transaksi }}</span>
            </div>
            <div class="flex justify-between">
                <span>Kasir</span>
                <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $transaksi->kasir->nama_lengkap ?? 'Sistem' }}</span>
            </div>
            <div class="flex justify-between">
                <span>Customer</span>
                <span class="text-gray-800 dark:text-gray-200 font-medium whitespace-nowrap overflow-hidden text-ellipsis max-w-[150px] inline-block text-right">
                    {{ $transaksi->nama_pelanggan ?: ($transaksi->pelanggan->nama_lengkap ?? 'Umum') }}
                </span>
            </div>
            @if($transaksi->membership)
                <div class="flex justify-between mt-1">
                    <span class="text-emerald-600 dark:text-emerald-400">Member</span>
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ $transaksi->membership->no_kartu }}</span>
                </div>
            @endif
        </div>

        {{-- Tabel Item --}}
        <table class="w-full text-left border-collapse mb-6 transition-colors">
            <thead>
                <tr class="bg-gray-50 dark:bg-slate-700/50 transition-colors">
                    <th class="py-3 px-4 font-bold text-gray-700 dark:text-gray-300 text-sm border-y border-gray-200 dark:border-slate-600">Item</th>
                    <th class="py-3 px-4 font-bold text-gray-700 dark:text-gray-300 text-sm border-y border-gray-200 dark:border-slate-600 text-center">Qty</th>
                    <th class="py-3 px-4 font-bold text-gray-700 dark:text-gray-300 text-sm border-y border-gray-200 dark:border-slate-600 text-right">Harga</th>
                    <th class="py-3 px-4 font-bold text-gray-700 dark:text-gray-300 text-sm border-y border-gray-200 dark:border-slate-600 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->detail as $detail)
                <tr class="border-b border-gray-100 dark:border-slate-700">
                    <td class="py-3 px-4 text-sm">
                        <p class="font-bold text-gray-800 dark:text-white">{{ $detail->nama_item ?? ($detail->tipe_item === 'Barang' ? optional($detail->barang)->nama_barang : optional($detail->layanan)->nama_layanan) ?? 'Item' }}</p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase">{{ $detail->tipe_item }}</p>
                    </td>
                    <td class="py-3 px-4 text-sm text-center text-gray-600 dark:text-gray-400">{{ $detail->qty }}</td>
                    <td class="py-3 px-4 text-sm text-right text-gray-600 dark:text-gray-400">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 text-sm text-right font-bold text-gray-800 dark:text-white">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Summary --}}
        <div class="border-t-2 border-gray-200 dark:border-slate-600 pt-4 space-y-2">
            @if($transaksi->total_sebelum_diskon)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                    <span class="text-gray-700 dark:text-gray-300 font-medium">Rp {{ number_format($transaksi->total_sebelum_diskon, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-emerald-600 dark:text-emerald-400">Diskon Member ({{ $transaksi->diskon_persen }}%)</span>
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">- Rp {{ number_format($transaksi->total_sebelum_diskon - $transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between text-lg font-bold pt-2 border-t border-dashed border-gray-300 dark:border-slate-600">
                <span class="text-gray-900 dark:text-white">TOTAL</span>
                <span class="text-gray-900 dark:text-white">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500 dark:text-gray-400">Tunai</span>
                <span class="text-gray-700 dark:text-gray-300">Rp {{ number_format($transaksi->uang_bayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500 dark:text-gray-400">Kembalian</span>
                <span class="text-green-600 dark:text-green-400 font-bold">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
            </div>
            @if($transaksi->total_sebelum_diskon && $transaksi->diskon_persen > 0)
                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl text-xs mt-4 transition-colors">
                    <p class="font-bold"><i class="fa-solid fa-piggy-bank mr-1"></i> Anda Hemat Rp {{ number_format($transaksi->total_sebelum_diskon - $transaksi->total_harga, 0, ',', '.') }} dengan membership!</p>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="text-center pt-8 mt-6 border-t border-gray-100 dark:border-slate-700 text-sm text-gray-500 dark:text-gray-400 transition-colors">
            <p class="font-bold text-gray-800 dark:text-white mb-1 transition-colors">Terima kasih atas kunjungan Anda!</p>
            <p>{{ $toko->pesan_penutup ?? 'Barang yang sudah dibeli tidak dapat dikembalikan.' }}</p>
        </div>

    </div>

</body>

</html>
