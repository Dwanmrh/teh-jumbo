<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Keuangan - Teh Solo Jumbo</title>
    <style>
        /* Reset & Base Fonts */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #44403c; /* Stone-700 */
            font-size: 10pt;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Helpers */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .font-mono { font-family: 'Courier New', Courier, monospace; letter-spacing: -0.5px; }

        /* Colors matching Index Blade */
        .text-emerald { color: #059669; }
        .bg-emerald-light { background-color: #ecfdf5; }
        .text-rose { color: #e11d48; }
        .bg-rose-light { background-color: #fff1f2; }
        .text-stone { color: #78716c; }
        .bg-stone { background-color: #f5f5f4; }

        /* Header */
        .header {
            border-bottom: 2px solid #44403c;
            padding-bottom: 20px;
            margin-bottom: 30px;
            position: relative;
        }
        .header h1 {
            font-size: 20pt;
            margin: 0;
            color: #1c1917; /* Stone-900 */
            letter-spacing: -0.5px;
        }
        .header .subtitle {
            font-size: 10pt;
            color: #78716c;
            margin-top: 5px;
        }
        .meta-data {
            position: absolute;
            top: 0;
            right: 0;
            text-align: right;
            font-size: 9pt;
            color: #78716c;
        }

        /* Summary Cards (Simulated with Table) */
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin-bottom: 30px;
        }
        .summary-card {
            background-color: #f5f5f4;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e7e5e4;
        }
        .summary-label {
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #78716c;
            margin-bottom: 5px;
            display: block;
        }
        .summary-value {
            font-size: 14pt;
            font-weight: bold;
            display: block;
        }

        /* Main Table */
        .table-container {
            width: 100%;
        }
        table.main {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }
        table.main th {
            background-color: #fafaf9;
            color: #57534e;
            padding: 10px 8px;
            text-transform: uppercase;
            font-size: 7pt;
            letter-spacing: 0.5px;
            border-top: 1px solid #e7e5e4;
            border-bottom: 1px solid #e7e5e4;
        }
        table.main td {
            padding: 10px 8px;
            border-bottom: 1px solid #f5f5f4;
            vertical-align: top;
        }
        table.main tr:last-child td {
            border-bottom: 2px solid #e7e5e4;
        }

        /* Badges */
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px 0;
            border-top: 1px solid #e7e5e4;
            font-size: 8pt;
            color: #a8a29e;
            text-align: center;
        }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Keuangan</h1>
        <div class="subtitle">Teh Solo de Jumbo Fibonacci</div>

        <div class="meta-data">
            <div>
                <strong>Periode:</strong>
                @if($selectedBulan) {{ \Carbon\Carbon::create()->month((int)$selectedBulan)->translatedFormat('F') }} @endif
                {{ $selectedTahun ?: 'Semua Tahun' }}
            </div>
            <div style="margin-top: 4px;">Generated: {{ date('d M Y, H:i') }}</div>
        </div>
    </div>

    <table class="summary-table">
        <tr>
            <td width="33%">
                <div class="summary-card" style="background-color: #ecfdf5; border-color: #a7f3d0;">
                    <span class="summary-label text-emerald">Total Pemasukan</span>
                    <span class="summary-value text-emerald">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span>
                </div>
            </td>
            <td width="33%">
                <div class="summary-card" style="background-color: #fff1f2; border-color: #fecdd3;">
                    <span class="summary-label text-rose">Total Pengeluaran</span>
                    <span class="summary-value text-rose">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span>
                </div>
            </td>
            <td width="33%">
                <div class="summary-card" style="background-color: #1c1917; border-color: #000;">
                    <span class="summary-label" style="color: #a8a29e;">Saldo Akhir</span>
                    <span class="summary-value" style="color: #fff;">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="table-container">
        <h3 style="margin-bottom: 10px; font-size: 11pt; color: #1c1917;">Mutasi Rekening</h3>
        <table class="main">
            <thead>
                <tr>
                    <th width="12%" class="text-left">Tanggal</th>
                    <th width="10%" class="text-left">Kode</th>
                    <th width="28%" class="text-left">Keterangan</th>
                    <th width="12%" class="text-center">Kategori</th>
                    <th width="13%" class="text-right">Masuk</th>
                    <th width="13%" class="text-right">Keluar</th>
                    <th width="12%" class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @if($saldoAwal != 0)
                <tr class="bg-stone">
                    <td colspan="3" class="text-stone" style="padding-left: 8px;">
                        <em>Bawaan Periode Lalu</em>
                    </td>
                    <td class="text-center">
                        <span class="badge" style="background-color: #e7e5e4; color: #57534e;">AWAL</span>
                    </td>
                    <td class="text-right text-stone">-</td>
                    <td class="text-right text-stone">-</td>
                    <td class="text-right font-bold font-mono">
                        Rp {{ number_format($saldoAwal, 0, ',', '.') }}
                    </td>
                </tr>
                @endif

                @forelse($laporan as $item)
                <tr>
                    <td>
                        <div class="font-bold">{{ $item['tanggal']->format('d/m/Y') }}</div>
                    </td>
                    <td>
                        <span style="background-color: #f5f5f4; padding: 2px 4px; border-radius: 3px; font-size: 8pt; color: #78716c;">
                            {{ $item['kode'] }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: bold; color: #44403c;">{{ $item['keterangan'] }}</div>
                        @if($item['penerima'] != '-')
                            <div style="font-size: 8pt; color: #a8a29e; margin-top: 2px;">
                                <span style="font-family: sans-serif;">&rarr;</span> {{ $item['penerima'] }}
                            </div>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $item['type'] == 'masuk' ? 'bg-emerald-light text-emerald' : 'bg-rose-light text-rose' }}">
                            {{ $item['kategori'] }}
                        </span>
                    </td>
                    <td class="text-right font-mono {{ $item['masuk'] > 0 ? 'text-emerald font-bold' : 'text-stone' }}">
                        {{ $item['masuk'] > 0 ? number_format($item['masuk'], 0, ',', '.') : '-' }}
                    </td>
                    <td class="text-right font-mono {{ $item['keluar'] > 0 ? 'text-rose font-bold' : 'text-stone' }}">
                        {{ $item['keluar'] > 0 ? number_format($item['keluar'], 0, ',', '.') : '-' }}
                    </td>
                    <td class="text-right font-bold font-mono text-stone" style="color: #1c1917;">
                        {{ number_format($item['saldo'], 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 30px; color: #a8a29e;">
                        Tidak ada data transaksi untuk periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dicetak oleh Sistem Administrasi Teh Solo de Jumbo &bull; Halaman <span class="page-number"></span>
    </div>

</body>
</html>
