@php
    // helper lokal (closure) — tidak mendefinisikan fungsi global
    $get = function($row, $keys, $default = '-') {
        foreach ((array) $keys as $k) {
            $v = data_get($row, $k);
            if (!is_null($v) && $v !== '') return $v;
        }
        return $default;
    };

    $formatRp = function($val) {
        if ($val === null || $val === '') return '-';
        if (!is_numeric($val)) {
            // jika sudah string berformat, coba bersihkan lalu parse
            $n = preg_replace('/[^\d\-.,]/', '', $val);
            $n = str_replace(',', '.', $n);
            return is_numeric($n) ? 'Rp ' . number_format((float)$n, 0, ',', '.') : $val;
        }
        return 'Rp ' . number_format($val, 0, ',', '.');
    };
@endphp

<h3 style="font-weight:bold">RINGKASAN KEUANGAN</h3>
<table>
    <tr><td>Total Masuk</td><td>{{ $formatRp($totalMasuk ?? 0) }}</td></tr>
    <tr><td>Total Keluar</td><td>{{ $formatRp($totalKeluar ?? 0) }}</td></tr>
    <tr><td>Selisih Kas</td><td>{{ $formatRp($selisihKas ?? 0) }}</td></tr>
</table>

<br><br>

<h3 style="font-weight:bold">LAPORAN KAS MASUK</h3>
<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Kode Kas</th>
            <th>Keterangan</th>
            <th>Kategori</th>
            <th>Metode</th>
            <th>Masuk</th>
        </tr>
    </thead>
    <tbody>
        @php $sumMasuk = 0; @endphp
        @foreach($kasMasuk as $row)
            @php
                $tanggal = $get($row, ['tanggal_transaksi','tanggal']);
                $kode = $get($row, ['kode_kas']);
                $ket = $get($row, ['keterangan','deskripsi']);
                $kategori = $get($row, ['kategori']);
                $metode = $get($row, ['metode_pembayaran','metode']);
                $masukRaw = data_get($row, 'total') ?? data_get($row, 'jumlah') ?? 0;
                $masukVal = is_numeric($masukRaw) ? (float)$masukRaw : floatval(preg_replace('/[^\d\-\.]/','',$masukRaw));
                $sumMasuk += $masukVal;
            @endphp
            <tr>
                <td>{{ $tanggal }}</td>
                <td>{{ $kode }}</td>
                <td>{{ $ket }}</td>
                <td>{{ $kategori }}</td>
                <td>{{ $metode }}</td>
                <td>{{ $formatRp($masukVal) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="5" style="font-weight:bold">TOTAL MASUK</td>
            <td style="font-weight:bold;color:green">{{ $formatRp($sumMasuk) }}</td>
        </tr>
    </tbody>
</table>

<br><br>

<h3 style="font-weight:bold">LAPORAN KAS KELUAR</h3>
<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Kode Kas</th>
            <th>Kategori</th>
            <th>Deskripsi</th>
            <th>Metode</th>
            <th>Penerima</th>
            <th>Keluar</th>
        </tr>
    </thead>
    <tbody>
        @php $sumKeluar = 0; @endphp
        @foreach($kasKeluar as $row)
            @php
                $tanggal = $get($row, ['tanggal','tanggal_transaksi']);
                $kode = $get($row, ['kode_kas']);
                $kategori = $get($row, ['kategori']);
                $desc = $get($row, ['deskripsi','keterangan']);
                $metode = $get($row, ['metode_pembayaran','metode']);
                $penerima = $get($row, ['penerima']);
                $keluarRaw = data_get($row, 'nominal') ?? data_get($row, 'jumlah') ?? 0;
                $keluarVal = is_numeric($keluarRaw) ? (float)$keluarRaw : floatval(preg_replace('/[^\d\-\.]/','',$keluarRaw));
                $sumKeluar += $keluarVal;
            @endphp
            <tr>
                <td>{{ $tanggal }}</td>
                <td>{{ $kode }}</td>
                <td>{{ $kategori }}</td>
                <td>{{ $desc }}</td>
                <td>{{ $metode }}</td>
                <td>{{ $penerima }}</td>
                <td>{{ $formatRp($keluarVal) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6" style="font-weight:bold">TOTAL KELUAR</td>
            <td style="font-weight:bold;color:red">{{ $formatRp($sumKeluar) }}</td>
        </tr>
    </tbody>
</table>

<br><br>

<h3 style="font-weight:bold">SEMUA TRANSAKSI</h3>
<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Kategori</th>
            <th>Metode</th>
            <th>Masuk</th>
            <th>Keluar</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        @php $running = 0; @endphp
        @foreach($laporan as $row)
            @php
                $tanggal = $get($row, ['tanggal','tanggal_transaksi']);
                $ket = $get($row, ['keterangan','deskripsi']);
                $kategori = $get($row, ['kategori']);
                $metode = $get($row, ['metode','metode_pembayaran']);
                $masukRaw = data_get($row,'kas_masuk') ?? data_get($row,'total') ?? 0;
                $keluarRaw = data_get($row,'kas_keluar') ?? data_get($row,'nominal') ?? 0;
                $masuk = is_numeric($masukRaw) ? (float)$masukRaw : floatval(preg_replace('/[^\d\-\.]/','',$masukRaw));
                $keluar = is_numeric($keluarRaw) ? (float)$keluarRaw : floatval(preg_replace('/[^\d\-\.]/','',$keluarRaw));
                $running += ($masuk - $keluar);
            @endphp
            <tr>
                <td>{{ $tanggal }}</td>
                <td>{{ $ket }}</td>
                <td>{{ $kategori }}</td>
                <td>{{ $metode }}</td>
                <td>{{ $masuk>0 ? $formatRp($masuk) : '-' }}</td>
                <td>{{ $keluar>0 ? $formatRp($keluar) : '-' }}</td>
                <td>{{ $formatRp($running) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
