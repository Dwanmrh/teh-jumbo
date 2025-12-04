<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 12px; color: #1f2937; padding: 20px; }
        .header { margin-bottom: 20px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #111827; font-size: 24px; }
        .header p { margin: 5px 0 0; color: #6b7280; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); }
        th, td { border: 1px solid #e5e7eb; padding: 10px 12px; text-align: left; }
        th { background-color: #f9fafb; font-weight: 600; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; color: #374151; }
        tr:nth-child(even) { background-color: #f9fafb; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: Consolas, "Liberation Mono", Menlo, Courier, monospace; }
        .font-bold { font-weight: bold; }

        .text-emerald { color: #059669; }
        .text-rose { color: #e11d48; }
        .bg-gray { background-color: #f3f4f6; }

        .summary-box { display: flex; gap: 20px; margin-bottom: 30px; }
        .card { flex: 1; padding: 15px; border: 1px solid #e5e7eb; border-radius: 8px; background: white; }
        .card-label { font-size: 11px; text-transform: uppercase; color: #6b7280; display: block; margin-bottom: 5px; }
        .card-value { font-size: 18px; font-weight: bold; color: #111827; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Keuangan Teh Solo Jumbo</h2>
        <p>Periode: {{ $selectedBulan ? \Carbon\Carbon::create()->month((int)$selectedBulan)->translatedFormat('F') : 'Semua Bulan' }} {{ $selectedTahun ?: 'Semua Tahun' }}</p>
    </div>

    <div class="summary-box">
        <div class="card">
            <span class="card-label">Total Masuk</span>
            <span class="card-value text-emerald">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span>
        </div>
        <div class="card">
            <span class="card-label">Total Keluar</span>
            <span class="card-value text-rose">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span>
        </div>
        <div class="card" style="background-color: #1f2937;">
            <span class="card-label" style="color: #9ca3af;">Saldo Akhir</span>
            <span class="card-value" style="color: white;">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th>Keterangan</th>
                <th width="15%" class="text-center">Kategori</th>
                <th width="15%" class="text-right">Masuk</th>
                <th width="15%" class="text-right">Keluar</th>
                <th width="15%" class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @if($saldoAwal != 0)
            <tr class="bg-gray">
                <td class="text-center">-</td>
                <td colspan="2" class="font-bold" style="color: #4b5563;">Saldo Awal Periode</td>
                <td class="text-center">-</td>
                <td class="text-right">-</td>
                <td class="text-right">-</td>
                <td class="text-right font-mono font-bold">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</td>
            </tr>
            @endif

            @foreach ($laporan as $index => $item)
                <tr>
                    <td class="text-center" style="color: #9ca3af;">{{ $index + 1 }}</td>
                    <td>
                        <div class="font-bold">{{ $item['tanggal']->format('d M Y') }}</div>
                        <div style="font-size: 10px; color: #6b7280;">{{ $item['kode'] }}</div>
                    </td>
                    <td>
                        {{ $item['keterangan'] }}
                        @if($item['penerima'] != '-') <br><small style="color: #6b7280;">({{ $item['penerima'] }})</small> @endif
                    </td>
                    <td class="text-center">
                        <span style="background: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase;">{{ $item['kategori'] }}</span>
                    </td>
                    <td class="text-right font-mono {{ $item['masuk'] > 0 ? 'text-emerald font-bold' : 'text-gray' }}">
                        {{ $item['masuk'] > 0 ? 'Rp '.number_format($item['masuk'],0,',','.') : '-' }}
                    </td>
                    <td class="text-right font-mono {{ $item['keluar'] > 0 ? 'text-rose font-bold' : 'text-gray' }}">
                        {{ $item['keluar'] > 0 ? 'Rp '.number_format($item['keluar'],0,',','.') : '-' }}
                    </td>
                    <td class="text-right font-mono font-bold">Rp {{ number_format($item['saldo'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
