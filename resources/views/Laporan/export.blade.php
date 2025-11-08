<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #2F362C; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: center; }
        th { background-color: #f5c04c; font-weight: bold; }
        h2 { text-align: center; margin-bottom: 5px; }
        .summary { margin-top: 20px; }
        .summary td { padding: 6px 10px; }
    </style>
</head>
<body>
    <h2>Laporan Keuangan</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Kategori</th>
                <th>Metode</th>
                <th>Kas Masuk</th>
                <th>Kas Keluar</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}</td>
                    <td style="text-align:left;">{{ $item['keterangan'] }}</td>
                    <td>{{ $item['kategori'] }}</td>
                    <td>{{ $item['metode'] }}</td>
                    <td>{{ $item['kas_masuk'] > 0 ? 'Rp '.number_format($item['kas_masuk'],0,',','.') : '-' }}</td>
                    <td>{{ $item['kas_keluar'] > 0 ? 'Rp '.number_format($item['kas_keluar'],0,',','.') : '-' }}</td>
                    <td>Rp {{ number_format($item['saldo'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td><strong>Total Kas Masuk:</strong></td>
            <td>Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Kas Keluar:</strong></td>
            <td>Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Selisih / Neraca Kas:</strong></td>
            <td>Rp {{ number_format($selisihKas, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Saldo Akhir:</strong></td>
            <td>Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
        </tr>
    </table>
</body>
</html>
