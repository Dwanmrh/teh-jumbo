<table>
    {{-- JUDUL LAPORAN --}}
    <tr>
        <td colspan="7" style="font-size: 14px; font-weight: bold; text-align: center; height: 30px; vertical-align: middle;">
            LAPORAN KEUANGAN TEH SOLO DE JUMBO
        </td>
    </tr>
    <tr>
        <td colspan="7" style="text-align: center; color: #555555; font-style: italic;">
            Periode: {{ $selectedBulan ? \Carbon\Carbon::create()->month((int)$selectedBulan)->translatedFormat('F') : 'Semua Bulan' }} {{ $selectedTahun }}
        </td>
    </tr>
    <tr></tr>

    {{-- RINGKASAN DATA (Proporsional) --}}
    <tr>
        <td colspan="2" style="font-weight: bold; background-color: #eeeeee; border: 1px solid #000000;">RINGKASAN PERIODE</td>
        <td colspan="5"></td>
    </tr>
    <tr>
        <td style="border: 1px solid #cccccc; width: 25px;">Total Pemasukan (Omzet)</td>
        <td style="border: 1px solid #cccccc; font-weight: bold; color: #059669; text-align: right; width: 20px;">{{ $totalMasuk }}</td>
        <td colspan="5"></td>
    </tr>
    <tr>
        <td style="border: 1px solid #cccccc;">Total Pengeluaran</td>
        <td style="border: 1px solid #cccccc; font-weight: bold; color: #e11d48; text-align: right;">{{ $totalKeluar }}</td>
        <td colspan="5"></td>
    </tr>
    <tr>
        <td style="border: 1px solid #000000; background-color: #333333; color: #ffffff; font-weight: bold;">SALDO PEMBUKUAN (AKHIR)</td>
        <td style="border: 1px solid #000000; background-color: #333333; color: #ffffff; font-weight: bold; text-align: right;">{{ $saldoAkhir }}</td>
        <td colspan="5"></td>
    </tr>
    <tr>
        <td style="border: 1px solid #000000; background-color: #fffbeb; color: #92400e; font-weight: bold;">ESTIMASI SISA KAS (LACI)</td>
        <td style="border: 1px solid #000000; background-color: #fffbeb; color: #92400e; font-weight: bold; text-align: right;">{{ $sisaUangFisik }}</td>
        <td colspan="5" style="font-style: italic; color: #666666; font-size: 10px; vertical-align: middle;">*Hanya Uang Tunai (Cash Masuk - Keluar)</td>
    </tr>
    <tr></tr>

    {{-- HEADER TABEL TRANSAKSI --}}
    <thead>
    <tr>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 18px; vertical-align: middle;">TANGGAL</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 15px; vertical-align: middle;">KODE</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 40px; vertical-align: middle;">KETERANGAN</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 25px; vertical-align: middle;">KATEGORI / METODE</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 18px; color: #065f46; vertical-align: middle;">MASUK</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 18px; color: #9f1239; vertical-align: middle;">KELUAR</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #d1d5db; width: 20px; vertical-align: middle;">SALDO</th>
    </tr>
    </thead>

    {{-- BODY TABEL --}}
    <tbody>
        @foreach($laporan as $item)
        <tr>
            <td style="border: 1px solid #cccccc; text-align: center; vertical-align: top;">
                {{ $item['tanggal']->format('d/m/Y H:i') }}
            </td>
            <td style="border: 1px solid #cccccc; text-align: center; vertical-align: top;">
                {{ $item['kode'] }}
            </td>
            <td style="border: 1px solid #cccccc; vertical-align: top;">
                {{ $item['keterangan'] }}
            </td>
            <td style="border: 1px solid #cccccc; text-align: center; vertical-align: top;">
                {{ $item['kategori'] }}
                @if(strtolower($item['payment']) != 'tunai' && strtolower($item['payment']) != 'cash')
                 ({{ strtoupper($item['payment']) }})
                @endif
            </td>
            <td style="border: 1px solid #cccccc; text-align: right; vertical-align: top; {{ $item['masuk'] > 0 ? 'color: #065f46; font-weight:bold;' : 'color: #dddddd;' }}">
                {{ $item['masuk'] > 0 ? $item['masuk'] : '0' }}
            </td>
            <td style="border: 1px solid #cccccc; text-align: right; vertical-align: top; {{ $item['keluar'] > 0 ? 'color: #9f1239; font-weight:bold;' : 'color: #dddddd;' }}">
                {{ $item['keluar'] > 0 ? $item['keluar'] : '0' }}
            </td>
            <td style="border: 1px solid #cccccc; text-align: right; font-weight: bold; vertical-align: top;">
                {{ $item['saldo'] }}
            </td>
        </tr>
        @endforeach

        {{-- FOOTER TABEL (Opsional, untuk batas bawah) --}}
        <tr>
            <td colspan="7" style="border-top: 2px solid #000000;"></td>
        </tr>
    </tbody>
</table>
