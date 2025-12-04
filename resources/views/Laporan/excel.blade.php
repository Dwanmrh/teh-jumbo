<table>
    {{-- HEADER LAPORAN --}}
    <tr>
        <td colspan="8" style="font-size: 16px; font-weight: bold; text-align: center; height: 30px; vertical-align: middle;">
            LAPORAN KEUANGAN TEH SOLO JUMBO
        </td>
    </tr>
    <tr>
        <td colspan="8" style="text-align: center; color: #666666;">
            Periode: {{ $selectedBulan ? \Carbon\Carbon::create()->month((int)$selectedBulan)->translatedFormat('F') : 'Semua Bulan' }} {{ $selectedTahun ?: 'Semua Tahun' }}
        </td>
    </tr>
    <tr></tr> {{-- Empty Row --}}

    {{-- SUMMARY SECTION --}}
    <tr>
        <td colspan="2" style="font-weight: bold; border: 1px solid #000000; background-color: #f3f4f6;">RINGKASAN</td>
        <td colspan="6"></td>
    </tr>
    <tr>
        <td style="border: 1px solid #e5e7eb;">Saldo Awal</td>
        <td style="border: 1px solid #e5e7eb; font-weight: bold; text-align: right;">{{ $saldoAwal }}</td>
    </tr>
    <tr>
        <td style="border: 1px solid #e5e7eb;">Total Pemasukan</td>
        <td style="border: 1px solid #e5e7eb; font-weight: bold; color: #059669; text-align: right;">{{ $totalMasuk }}</td>
    </tr>
    <tr>
        <td style="border: 1px solid #e5e7eb;">Total Pengeluaran</td>
        <td style="border: 1px solid #e5e7eb; font-weight: bold; color: #e11d48; text-align: right;">{{ $totalKeluar }}</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000000; background-color: #1c1917; color: #ffffff;">Saldo Akhir</td>
        <td style="border: 1px solid #000000; background-color: #1c1917; color: #ffffff; font-weight: bold; text-align: right;">{{ $saldoAkhir }}</td>
    </tr>
    <tr></tr> {{-- Empty Row --}}

    {{-- TABLE HEADER --}}
    <thead>
    <tr>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 15px;">TANGGAL</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 12px;">KODE</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 35px;">KETERANGAN</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 20px;">PENERIMA/DARI</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 15px;">KATEGORI</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 18px; color: #065f46;">MASUK</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 18px; color: #9f1239;">KELUAR</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 20px;">SALDO</th>
    </tr>
    </thead>

    {{-- TABLE BODY --}}
    <tbody>
        {{-- Row Saldo Awal --}}
        @if($saldoAwal != 0)
        <tr>
            <td style="border: 1px solid #d1d5db; text-align: center; color: #9ca3af;">-</td>
            <td style="border: 1px solid #d1d5db; text-align: center; color: #9ca3af;">AWAL</td>
            <td style="border: 1px solid #d1d5db; font-style: italic; color: #4b5563;">Saldo Awal Periode</td>
            <td style="border: 1px solid #d1d5db;">-</td>
            <td style="border: 1px solid #d1d5db;">-</td>
            <td style="border: 1px solid #d1d5db; text-align: right;">0</td>
            <td style="border: 1px solid #d1d5db; text-align: right;">0</td>
            <td style="border: 1px solid #d1d5db; text-align: right; font-weight: bold;">{{ $saldoAwal }}</td>
        </tr>
        @endif

        @foreach($laporan as $item)
        <tr>
            <td style="border: 1px solid #d1d5db; text-align: center;">{{ $item['tanggal']->format('d/m/Y') }}</td>
            <td style="border: 1px solid #d1d5db; text-align: center;">{{ $item['kode'] }}</td>
            <td style="border: 1px solid #d1d5db;">{{ $item['keterangan'] }}</td>
            <td style="border: 1px solid #d1d5db;">{{ $item['penerima'] !== '-' ? $item['penerima'] : '' }}</td>
            <td style="border: 1px solid #d1d5db; text-align: center;">{{ $item['kategori'] }}</td>

            {{-- Masuk Column --}}
            <td style="border: 1px solid #d1d5db; text-align: right; {{ $item['masuk'] > 0 ? 'color: #059669; font-weight:bold;' : 'color: #d1d5db;' }}">
                {{ $item['masuk'] }}
            </td>

            {{-- Keluar Column --}}
            <td style="border: 1px solid #d1d5db; text-align: right; {{ $item['keluar'] > 0 ? 'color: #e11d48; font-weight:bold;' : 'color: #d1d5db;' }}">
                {{ $item['keluar'] }}
            </td>

            {{-- Saldo Column --}}
            <td style="border: 1px solid #d1d5db; text-align: right; font-weight: bold;">{{ $item['saldo'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
