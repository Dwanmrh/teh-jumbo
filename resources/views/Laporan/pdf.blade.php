<style>
    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 12px;
        color: #2f2f2f;
        padding: 28px;
        background: #ffffff;
    }

    /* ===================== HEADER ===================== */

    .header-table {
        width: 100%;
        margin-bottom: 15px;
    }

    .header-title {
        font-size: 24px;
        font-weight: 800;
        color: #2f3e33;
        text-align: left;
    }

    .header-logo {
        text-align: right;
        white-space: nowrap;
    }

    .header-logo img {
        width: 55px;
        vertical-align: middle;
    }

    .header-logo span {
        font-size: 15px;
        font-weight: 700;
        color: #2f3e33;
        margin-left: 5px;
        vertical-align: middle;
    }

    hr.line {
        border: 0;
        border-top: 1.6px solid #bfbfbf;
        margin: 5px 0 25px 0;
    }

    /* ===================== SECTION TITLES ===================== */
    .section-title {
        font-size: 17px;
        font-weight: 700;
        color: #2f3e33;
        margin-bottom: 12px;
    }

    /* ===================== SUMMARY ===================== */
    .summary-box {
        width: 100%;
        border-radius: 8px;
        padding: 15px 18px;
        background: #f8fdf7;
        border: 1px solid #d6e8d6;
        margin-bottom: 25px;
    }

    .summary-item {
        margin-bottom: 6px;
        font-size: 13px;
    }

    .summary-item span {
        font-weight: bold;
    }

    /* ===================== TABLES ===================== */
    .table-container {
        background: #ffffff;
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 25px;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    th, td {
        padding: 8px 10px;
        font-size: 12px;
    }

    th {
        font-weight: bold;
        border-bottom: 1px solid #d0d0d0;
    }

    td {
        border-bottom: 1px solid #efefef;
    }

    tr:nth-child(even) td {
        background: #fafafa;
    }

    tr:last-child td {
        border-bottom: none;
    }

    /* Kas Masuk (Green) */
    table.kas-masuk th {
        background: #d4f5d4;
        color: #264326;
    }

    /* Kas Keluar (Red) */
    table.kas-keluar th {
        background: #ffcccc;
        color: #7a1a1a;
    }

    /* TOTAL ROW COLORS */
    .total-row {
        font-weight: bold;
        background: #e6ffe6 !important;
        border-top: 2px solid #b4e6b4;
    }

    .total-row-red {
        font-weight: bold;
        background: #ffd6d6 !important;
        border-top: 2px solid #ffb5b5;
    }

    /* PRINT INFO */
    .print-info {
        text-align: left;
        font-size: 11px;
        color: #666;
        margin-bottom: 10px;
        margin-top: -2px;
    }

    /* ===================== FOOTER ===================== */

    .footer {
        margin-top: 40px;
        text-align: center;
        font-size: 11px;
        color: #777;
        border-top: 1px solid #ccc;
        padding-top: 8px;
        position: fixed;
        bottom: 10px;
        left: 0;
        right: 0;
    }

</style>



<!-- =================== HEADER =================== -->
<table class="header-table">
    <tr>
        <td class="header-title">Laporan Keuangan</td>

        <td class="header-logo">
            <img src="{{ public_path('assets/images/logo_teh.png') }}">
            <span>Teh Solo Jumbo</span>
        </td>
    </tr>
</table>

<hr class="line">



<!-- =================== RINGKASAN =================== -->
<div class="section-title">Ringkasan Keuangan</div>

<div class="summary-box">
    <div class="summary-item">
        Total Masuk : <span>Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span>
    </div>
    <div class="summary-item">
        Total Keluar : <span>Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span>
    </div>
    <div class="summary-item">
        Selisih Kas : <span>Rp {{ number_format($selisihKas, 0, ',', '.') }}</span>
    </div>
</div>

<hr class="line">



<!-- =================== KAS MASUK =================== -->
<div class="section-title">Laporan Kas Masuk</div>

<div class="print-info">
    Dicetak pada: {{ now()->format('d M Y H:i') }}
</div>

<div class="table-container">
    <table class="kas-masuk">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th>Metode</th>
                <th>Masuk</th>
            </tr>
        </thead>
        <tbody>
            @php $totalMasukTabel = 0; @endphp

            @foreach ($laporan as $row)
                @if ($row['kas_masuk'] > 0)
                @php $totalMasukTabel += $row['kas_masuk']; @endphp
                <tr>
                    <td>{{ $row['tanggal'] }}</td>
                    <td>{{ $row['kategori'] }}</td>
                    <td>{{ $row['keterangan'] }}</td>
                    <td>{{ $row['metode_pembayaran'] }}</td>
                    <td>{{ number_format($row['kas_masuk']) }}</td>
                </tr>
                @endif
            @endforeach

            <tr class="total-row">
                <td colspan="4">TOTAL MASUK</td>
                <td>Rp {{ number_format($totalMasukTabel, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>



<!-- =================== KAS KELUAR =================== -->
<div class="section-title">Laporan Kas Keluar</div>

<div class="table-container">
    <table class="kas-keluar">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th>Metode</th>
                <th>Penerima</th>
                <th>Keluar</th>
            </tr>
        </thead>

        <tbody>
            @php $totalKeluarTabel = 0; @endphp

            @foreach ($laporan as $row)
                @if ($row['kas_keluar'] > 0)
                @php $totalKeluarTabel += $row['kas_keluar']; @endphp
                <tr>
                    <td>{{ $row['tanggal'] }}</td>
                    <td>{{ $row['kategori'] }}</td>
                    <td>{{ $row['deskripsi'] }}</td>
                    <td>{{ $row['metode_pembayaran'] }}</td>
                    <td>{{ $row['penerima'] }}</td>
                    <td>{{ number_format($row['kas_keluar']) }}</td>
                </tr>
                @endif
            @endforeach

            <tr class="total-row-red">
                <td colspan="5">TOTAL KELUAR</td>
                <td>Rp {{ number_format($totalKeluarTabel, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>


<!-- =================== FOOTER =================== -->
<div class="footer">
    © {{ date('Y') }} Teh Solo Jumbo — Laporan keuangan dicetak otomatis oleh sistem.
</div>
