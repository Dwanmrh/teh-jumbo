<x-app-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">

    <div class="py-8 bg-[#f7f7f7] min-h-screen font-[Outfit]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <h2 class="text-xl font-semibold text-[#2F362C]">Laporan Keuangan</h2>
            <p class="text-sm text-gray-500 mb-4">Ringkasan dan rincian transaksi</p>

            {{-- CARD FILTER + EXPORT --}}
            <div class="bg-white p-6 rounded-xl shadow-md mb-6">

                <div class="flex items-center gap-3 mb-4">
                    <span class="material-symbols-outlined text-gray-600">filter_alt</span>
                    <h3 class="text-lg font-semibold text-gray-700">Filter Laporan</h3>
                </div>

                <form method="GET" action="{{ route('laporan.index') }}" id="filterForm">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">

                        {{-- Filter BULAN + TAHUN --}}
                        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">

                            {{-- BULAN --}}
                            <div class="flex flex-col w-full sm:w-auto">
                                <label class="text-sm font-medium text-gray-700 mb-1">Bulan</label>
                                <select name="bulan" class="w-full sm:w-[350px] border-gray-100 rounded-lg p-2 auto-submit bg-gray-100">
                                    <option value="">Semua Bulan</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            {{-- TAHUN --}}
                            <div class="flex flex-col w-full sm:w-auto">
                                <label class="text-sm font-medium text-gray-700 mb-1">Tahun</label>
                                <select name="tahun" class="w-full sm:w-[350px] border-gray-100 rounded-lg p-2 auto-submit bg-gray-100">
                                    <option value="">Semua Tahun</option>
                                    @foreach ($listTahun as $th)
                                        <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>
                                            {{ $th }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        {{-- EXPORT BUTTONS --}}
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                            {{-- PDF --}}
                            <a href="{{ route('laporan.export.pdf', request()->all()) }}"
                                class="flex items-center gap-2 border border-red-400 text-red-500 px-5 py-2 rounded-lg hover:bg-red-50 transition w-full sm:w-auto justify-center">
                                <span class="material-symbols-outlined text-base">download</span>
                                Download PDF
                            </a>

                            {{-- Excel --}}
                            <a href="{{ route('laporan.export.excel', request()->all()) }}"
                                class="flex items-center gap-2 border border-green-400 text-green-600 px-5 py-2 rounded-lg hover:bg-green-50 transition w-full sm:w-auto justify-center">
                                <span class="material-symbols-outlined text-base">dataset</span>
                                Download Excel
                            </a>
                        </div>

                    </div>
                </form>

                <script>
                    document.querySelectorAll('.auto-submit').forEach(el => {
                        el.addEventListener('change', () => {
                            document.getElementById('filterForm').submit();
                        });
                    });
                </script>

            </div>

            {{-- Card Ringkasan --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white border-l-4 border-green-500 rounded-xl p-5 shadow-sm w-full">
                    <p class="text-gray-600 text-sm font-medium">Total Kas Masuk</p>
                    <p class="text-xl font-semibold text-emerald-600 mt-3">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-3">{{ $kasMasuk->count() }} transaksi</p>
                </div>

                <div class="bg-white border-l-4 border-red-500 rounded-xl p-5 shadow-sm w-full">
                    <p class="text-gray-600 text-sm">Total Kas Keluar</p>
                    <p class="text-xl font-semibold text-rose-600 mt-3">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-3">{{ $kasKeluar->count() }} transaksi</p>
                </div>

                <div class="bg-white border-l-4 border-blue-500 rounded-xl p-5 shadow-sm w-full">
                    <p class="text-gray-600 text-sm">Selisih / Saldo</p>
                    <p class="text-xl font-semibold text-blue-600 mt-3">Rp {{ number_format($selisihKas, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-3">{{ $selisihKas >= 0 ? 'Surplus' : 'Defisit' }}</p>
                </div>
            </div>

            {{-- Card Kas Masuk --}}
            <div class="bg-white border-t-4 border-green-500 p-6 rounded-xl shadow-sm mb-6 overflow-x-auto">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-green-600 text-2xl">trending_up</span>
                    <h3 class="text-lg font-semibold text-green-700">Rincian Kas Masuk</h3>
                </div>
                <p class="text-sm text-gray-500 mb-4">Daftar transaksi pemasukan</p>

                <table class="min-w-[600px] sm:min-w-full text-sm border border-gray-100 rounded-lg table-fixed">
                    <thead class="bg-gray-50 text-gray-800 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Kategori</th>
                            <th class="px-4 py-2 text-left">Keterangan</th>
                            <th class="px-4 py-2 text-left">Metode Pembayaran</th>
                            <th class="px-4 py-2 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kasMasuk as $m)
                            <tr class="border-b hover:bg-green-50 transition">
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($m->tanggal_transaksi)->format('d M Y') }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">{{ $m->kategori }}</span>
                                </td>
                                <td class="px-4 py-2">{{ $m->keterangan ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $m->metode_pembayaran }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($m->total,0,',','.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data kas masuk</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-green-50 font-medium">
                            <td colspan="4" class="px-4 py-2 text-right">Total Kas Masuk</td>
                            <td class="px-4 py-2 text-right text-green-600">Rp {{ number_format($totalMasuk,0,',','.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Card Kas Keluar --}}
            <div class="bg-white border-t-4 border-red-500 p-6 rounded-xl shadow-sm mb-6 overflow-x-auto">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-red-600 text-2xl">trending_down</span>
                    <h3 class="text-lg font-semibold text-red-700">Rincian Kas Keluar</h3>
                </div>
                <p class="text-sm text-gray-500 mb-4">Daftar transaksi pengeluaran</p>

                <table class="min-w-[700px] sm:min-w-full text-sm border border-gray-100 rounded-lg table-fixed">
                    <thead class="bg-gray-50 text-gray-800 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Kategori</th>
                            <th class="px-4 py-2 text-left">Keterangan</th>
                            <th class="px-4 py-2 text-left">Metode Pembayaran</th>
                            <th class="px-4 py-2 text-left">Penerima</th>
                            <th class="px-4 py-2 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kasKeluar as $k)
                            <tr class="border-b hover:bg-red-50 transition">
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</td>
                                <td class="px-4 py-2"><span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium">{{ $k->kategori }}</span></td>
                                <td class="px-4 py-2">{{ $k->deskripsi ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $k->metode_pembayaran }}</td>
                                <td class="px-4 py-2">{{ $k->penerima}}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($k->nominal,0,',','.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data kas keluar</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-red-50 font-medium">
                            <td colspan="5" class="px-4 py-2 text-right">Total Kas Keluar</td>
                            <td class="px-4 py-2 text-right text-red-600">Rp {{ number_format($totalKeluar,0,',','.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Tabel Gabungan --}}
            <div class="bg-white border-t-4 border-blue-500 p-6 rounded-xl shadow-md mb-6 overflow-x-auto">
                <h3 class="text-xl font-semibold text-gray-600 mb-3">Semua Transaksi</h3>
                <table class="min-w-[700px] sm:min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray text-black border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-2 text-left border-b border-gray-300">Tanggal</th>
                            <th class="px-4 py-2 text-left border-b border-gray-300">Keterangan</th>
                            <th class="px-4 py-2 text-left border-b border-gray-300">Kategori</th>
                            <th class="px-4 py-2 text-left border-b border-gray-300">Metode</th>
                            <th class="px-4 py-2 text-right border-b border-gray-300">Kas Masuk</th>
                            <th class="px-4 py-2 text-right border-b border-gray-300">Kas Keluar</th>
                            <th class="px-4 py-2 text-right border-b border-gray-300">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($laporan as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}</td>
                                <td class="px-4 py-2">{{ $item['keterangan'] ?? $item['deskripsi'] ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $item['kategori'] }}</td>
                                <td class="px-4 py-2">{{ $item['metode_pembayaran'] }}</td>
                                <td class="px-4 py-2 text-right text-green-600">
                                    {{ $item['kas_masuk'] > 0 ? 'Rp '.number_format($item['kas_masuk'],0,',','.') : '-' }}
                                </td>
                                <td class="px-4 py-2 text-right text-red-600">
                                    {{ $item['kas_keluar'] > 0 ? 'Rp '.number_format($item['kas_keluar'],0,',','.') : '-' }}
                                </td>
                                <td class="px-4 py-2 text-right font-medium">
                                    Rp {{ number_format($item['saldo'],0,',','.') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-gray-500">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Ringkasan Total --}}
            <div class="border border-blue-300 rounded-xl p-5 mt-8 bg-white shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
                    <p class="text-gray-600">Total Pemasukan</p>
                    <p class="text-green-600">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                </div>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
                    <p class="text-gray-600">Total Pengeluaran</p>
                    <p class="text-red-600">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                </div>
                <hr class="my-2 border-gray-300">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <p class="text-gray-700">Selisih</p>
                    <p class="text-blue-600">Rp {{ number_format($selisihKas, 0, ',', '.') }}</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
