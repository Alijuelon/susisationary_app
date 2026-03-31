<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - Susi Stationary</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #666; }
        .info { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .masuk { color: green; }
        .keluar { color: red; }
        .summary-box { width: 40%; float: right; border: 1px solid #000; padding: 10px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .clear { clear: both; }
    </style>
</head>
<body>

    <div class="header">
        <h2>SUSI STATIONARY</h2>
        <p>Jl. Pramuka, Bengkalis | Telp: 0812-3456-7890</p>
        <p><b>Laporan Keuangan (Pemasukan & Pengeluaran)</b></p>
    </div>

    <div class="info">
        <p><b>Periode:</b> {{ \Carbon\Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y') }}</p>
        <p><b>Tanggal Cetak:</b> {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Tanggal</th>
                <th width="40%">Keterangan</th>
                <th width="15%" class="text-center">Jenis</th>
                <th width="20%" class="text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td class="text-center">{{ $item->jenis }}</td>
                    <td class="text-right {{ $item->jenis == 'Pemasukan' ? 'masuk' : 'keluar' }}">
                        {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <table style="border: none; margin-bottom: 0;">
            <tr>
                <td style="border: none; padding: 2px;">Total Pemasukan</td>
                <td style="border: none; padding: 2px;" class="text-right">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px;">Total Pengeluaran</td>
                <td style="border: none; padding: 2px;" class="text-right">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2" style="border: none; border-top: 1px solid #000;"></td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px;"><b>Laba Bersih</b></td>
                <td style="border: none; padding: 2px;" class="text-right"><b>Rp {{ number_format($labaBersih, 0, ',', '.') }}</b></td>
            </tr>
        </table>
    </div>

    <div class="clear"></div>

</body>
</html>