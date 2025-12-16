<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Keuangan & Operasional</title>
    <style>
        /* BASE STYLES */
        body {
            font-family: sans-serif;
            color: #333;
            font-size: 10pt; /* Ukuran font standar diperbesar sedikit */
            line-height: 1.4;
        }

        /* UTILITIES */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .font-mono { font-family: 'Courier New', monospace; letter-spacing: -0.5px; }
        .text-xs { font-size: 8pt; }
        .text-sm { font-size: 9pt; }
        .text-lg { font-size: 14pt; }

        /* COLORS */
        .text-emerald { color: #047857; }
        .text-rose { color: #be123c; }
        .bg-gray-100 { background-color: #f3f4f6; }
        .text-gray { color: #9ca3af; }

        /* LAYOUT HELPERS */
        .w-full { width: 100%; }
        .align-top { vertical-align: top; }
        .mt-4 { margin-top: 20px; }
        .mb-2 { margin-bottom: 10px; }

        /* HEADER */
        .header {
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 { margin: 0; font-size: 18pt; color: #111; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 4px 0 0; color: #555; font-size: 10pt; }
        .meta-table { width: 100%; margin-top: 10px; font-size: 9pt; color: #444; }
        .meta-table td { padding: 0; }

        /* SUMMARY CARDS (4 Columns) */
        .summary-table { width: 100%; border-collapse: separate; border-spacing: 10px 0; margin-bottom: 25px; margin-left: -10px; margin-right: -10px; width: calc(100% + 20px); }
        .card {
            padding: 12px 10px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            height: 70px; /* Tinggi tetap agar rapi */
        }
        .card-label { font-size: 8pt; text-transform: uppercase; color: #666; margin-bottom: 5px; display: block; letter-spacing: 0.5px; }
        .card-value { font-size: 13pt; font-weight: bold; display: block; margin-bottom: 4px; }
        .card-sub { font-size: 7pt; color: #666; line-height: 1.2; }

        /* CONTENT BOXES (2 Column Layout) */
        .box-container {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 12px;
            background-color: #fff;
        }
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #eee;
            padding-bottom: 8px;
            margin-bottom: 10px;
            color: #333;
        }

        /* MINI TABLES (Inside Boxes) */
        table.mini-table { width: 100%; font-size: 9pt; border-collapse: collapse; }
        table.mini-table th { text-align: left; color: #888; border-bottom: 1px solid #eee; padding: 4px 0; font-size: 8pt; text-transform: uppercase; }
        table.mini-table td { border-bottom: 1px solid #f9fafb; padding: 6px 0; }
        table.mini-table tr:last-child td { border-bottom: none; }

        /* MAIN TABLE (Ledger) */
        table.main { width: 100%; border-collapse: collapse; font-size: 9pt; margin-top: 5px; }
        table.main th {
            background-color: #e5e5e5;
            padding: 10px 8px;
            text-transform: uppercase;
            font-size: 8pt;
            font-weight: bold;
            border-bottom: 2px solid #999;
            text-align: left;
            color: #333;
        }
        table.main td { padding: 8px; border-bottom: 1px solid #ddd; vertical-align: top; }
        table.main tr:nth-child(even) { background-color: #f9f9f9; }

        /* BADGES */
        .badge { display: inline-block; padding: 2px 5px; border-radius: 3px; font-size: 7pt; font-weight: bold; text-transform: uppercase; border: 1px solid #ddd; background: #fff; color: #555; margin-top: 3px; }
        .badge-payment { background: #eff6ff; border-color: #bfdbfe; color: #1e40af; }

        /* FOOTER */
        .footer { position: fixed; bottom: 0; left: 0; right: 0; font-size: 8pt; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>Laporan Keuangan</h1>
        <p>Teh Solo De Jumbo Fibonacci - Sistem Manajemen Outlet</p>

        <table class="meta-table">
            <tr>
                <td align="left">Periode: <strong>{{ $selectedBulan ? \Carbon\Carbon::create()->month((int)$selectedBulan)->translatedFormat('F') : 'Semua Bulan' }} {{ $selectedTahun }}</strong></td>
                <td align="right">Dicetak: {{ date('d/m/Y H:i') }} | Oleh: {{ Auth::user()->name }}</td>
            </tr>
        </table>
    </div>

    {{-- 1. RINGKASAN KEUANGAN (4 BOXES) --}}
    <table class="summary-table">
        <tr>
            <td width="25%">
                <div class="card" style="background-color: #ecfdf5; border-color: #6ee7b7;">
                    <span class="card-label">Pemasukan</span>
                    <span class="card-value text-emerald">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span>
                    <div class="card-sub">
                        Tunai: {{ number_format($totalMasukCash, 0, ',', '.') }}<br>
                        QRIS/TF: {{ number_format($totalMasukNonCash, 0, ',', '.') }}
                    </div>
                </div>
            </td>
            <td width="25%">
                <div class="card" style="background-color: #fff1f2; border-color: #fda4af;">
                    <span class="card-label">Pengeluaran</span>
                    <span class="card-value text-rose">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span>
                    <div class="card-sub" style="color: #9f1239;">Belanja Bahan & Ops</div>
                </div>
            </td>
            <td width="25%">
                <div class="card" style="background-color: #f8fafc; border-color: #cbd5e1;">
                    <span class="card-label">Saldo Buku</span>
                    <span class="card-value" style="color: #334155;">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</span>
                    <div class="card-sub">Netto (Semua Akun)</div>
                </div>
            </td>
            <td width="25%">
                <div class="card" style="background-color: #171717; color: #fff; border-color: #000;">
                    <span class="card-label" style="color: #a3a3a3;">Sisa Kas Laci</span>
                    <span class="card-value">Rp {{ number_format($sisaUangFisik, 0, ',', '.') }}</span>
                    <div class="card-sub" style="color: #737373;">Uang Fisik Tersedia</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- 2. ANALISA PRODUK & GUDANG --}}
    <table class="w-full mb-2">
        <tr>
            {{-- KOLOM KIRI (Gudang) --}}
            <td width="48%" class="align-top">
                <div class="box-container">
                    <div class="section-title">Stok Gudang</div>
                    <table class="mini-table">
                        <tr>
                            <td>Varian Produk (SKU)</td>
                            <td class="text-right font-bold">{{ $productStats['inventory']['total_sku'] }}</td>
                        </tr>
                        <tr>
                            <td>Total Unit Fisik</td>
                            <td class="text-right font-bold">{{ $productStats['inventory']['total_stok'] }}</td>
                        </tr>
                        <tr>
                            <td>Nilai Aset</td>
                            <td class="text-right font-bold">Rp {{ number_format($productStats['inventory']['asset_value'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-top: 8px;">
                                @if($productStats['inventory']['low_stock'] > 0)
                                    <div style="background: #fee2e2; color: #991b1b; padding: 6px; border-radius: 4px; font-size: 8pt; text-align: center; border: 1px solid #fecaca;">
                                        <strong>PERHATIAN:</strong> {{ $productStats['inventory']['low_stock'] }} Barang stok menipis
                                    </div>
                                @else
                                    <div style="background: #d1fae5; color: #065f46; padding: 6px; border-radius: 4px; font-size: 8pt; text-align: center; border: 1px solid #a7f3d0;">
                                        Stok Aman Terkendali
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </td>

            {{-- SPACER (Pemisah Kolom) --}}
            <td width="4%"></td>

            {{-- KOLOM KANAN (Top Sales) --}}
            <td width="48%" class="align-top">
                <div class="box-container">
                    <div class="section-title">Top 5 Menu Terlaris</div>
                    <table class="mini-table">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productStats['top_products'] as $name => $stat)
                                <tr>
                                    <td>{{ $name }}</td>
                                    <td class="text-right font-bold">{{ $stat['qty'] }}</td>
                                    <td class="text-right text-xs">Rp {{ number_format($stat['total'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-xs text-gray">Belum ada penjualan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- 3. ADMIN SECTION (Hanya jika data ada) --}}
    @if(!empty($outletStats) || !empty($userStats))
    <table class="w-full mt-4 mb-2">
        <tr>
            {{-- KIRI: Outlet --}}
            @if(!empty($outletStats))
            <td width="48%" class="align-top">
                <div class="box-container">
                    <div class="section-title">Performa Cabang</div>
                    <table class="mini-table">
                        <thead>
                            <tr>
                                <th>Cabang</th>
                                <th class="text-right">Trx</th>
                                <th class="text-right">Omzet</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($outletStats as $outlet)
                                <tr>
                                    <td>{{ $outlet['name'] }}</td>
                                    <td class="text-right">{{ $outlet['trx_count'] }}</td>
                                    <td class="text-right font-bold text-emerald">Rp {{ number_format($outlet['omzet'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </td>

            {{-- SPACER --}}
            <td width="4%"></td>
            @else
            <td width="52%"></td>
            @endif

            {{-- KANAN: Staff --}}
            @if(!empty($userStats))
            <td width="48%" class="align-top">
                <div class="box-container">
                    <div class="section-title">Kinerja Pegawai</div>
                    <table class="mini-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Outlet</th>
                                <th class="text-right">Omzet</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userStats as $u)
                                <tr>
                                    <td>{{ $u['name'] }}</td>
                                    <td style="color: #666; font-size: 8pt;">{{ $u['outlet'] }}</td>
                                    <td class="text-right font-bold">Rp {{ number_format($u['omzet'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </td>
            @endif
        </tr>
    </table>
    @endif

    {{-- 4. TABEL MUTASI (PAGE BREAK JIKA PERLU) --}}
    <div class="mt-4">
        <div style="font-size: 12pt; font-weight: bold; margin-bottom: 5px; text-transform: uppercase;">Rincian Transaksi (Buku Kas)</div>
        <table class="main">
            <thead>
                <tr>
                    <th width="12%">Waktu</th>
                    <th width="10%">Kode</th>
                    <th width="33%">Keterangan</th>
                    <th width="15%" class="text-right">Masuk</th>
                    <th width="15%" class="text-right">Keluar</th>
                    <th width="15%" class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan as $item)
                <tr>
                    <td>
                        {{ $item['tanggal']->format('d/m/y') }}<br>
                        <span style="color: #888; font-size: 8pt;">{{ $item['tanggal']->format('H:i') }}</span>
                    </td>
                    <td class="font-mono text-xs">{{ $item['kode'] }}</td>
                    <td>
                        <span style="font-weight: bold; color: #222;">{{ $item['keterangan'] }}</span>
                        <br>
                        <span class="badge">{{ $item['kategori'] }}</span>
                        @if(strtolower($item['payment']) != 'tunai' && strtolower($item['payment']) != 'cash')
                            <span class="badge badge-payment">{{ $item['payment'] }}</span>
                        @endif
                    </td>
                    <td class="text-right font-mono {{ $item['masuk'] > 0 ? 'text-emerald font-bold' : 'text-gray' }}">
                        {{ $item['masuk'] > 0 ? number_format($item['masuk'], 0, ',', '.') : '-' }}
                    </td>
                    <td class="text-right font-mono {{ $item['keluar'] > 0 ? 'text-rose font-bold' : 'text-gray' }}">
                        {{ $item['keluar'] > 0 ? number_format($item['keluar'], 0, ',', '.') : '-' }}
                    </td>
                    <td class="text-right font-mono font-bold">
                        {{ number_format($item['saldo'], 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 30px; color: #888;">Tidak ada data transaksi pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Generated by System &bull; Halaman <script type="text/php">if (isset($pdf)) { echo $pdf->get_page_number(); }</script>
    </div>

</body>
</html>
