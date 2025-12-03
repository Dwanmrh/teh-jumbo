<x-app-layout>
    {{-- Container Utama --}}
    <div class="space-y-8">

        {{-- 1. HEADER & EXPORT ACTIONS --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-stone-800 tracking-tight">Laporan Keuangan</h2>
                <p class="text-stone-500 text-sm mt-1">Ringkasan arus kas dan rekapitulasi transaksi.</p>
            </div>

            <div class="flex items-center gap-3">
                {{-- PDF Export --}}
                <a href="{{ route('laporan.export.pdf', request()->all()) }}"
                   class="group flex items-center gap-2 bg-white border border-stone-200 text-stone-600 px-4 py-2.5 rounded-xl hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all shadow-soft active:scale-95">
                    <span class="material-symbols-rounded text-[20px] group-hover:scale-110 transition-transform">picture_as_pdf</span>
                    <span class="text-sm font-bold">PDF</span>
                </a>

                {{-- Excel Export --}}
                <a href="{{ route('laporan.export.excel', request()->all()) }}"
                   class="group flex items-center gap-2 bg-white border border-stone-200 text-stone-600 px-4 py-2.5 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition-all shadow-soft active:scale-95">
                    <span class="material-symbols-rounded text-[20px] group-hover:scale-110 transition-transform">table_view</span>
                    <span class="text-sm font-bold">Excel</span>
                </a>
            </div>
        </div>

        {{-- 2. FILTER SECTION (Glass Bar) --}}
        <div class="bg-white p-1.5 rounded-2xl shadow-soft border border-stone-200/60">
            <form method="GET" action="{{ route('laporan.index') }}" id="filterForm" class="flex flex-col sm:flex-row gap-2">

                {{-- Label Visual --}}
                <div class="hidden sm:flex items-center justify-center px-4 bg-stone-50 rounded-xl border border-stone-100">
                    <span class="material-symbols-rounded text-stone-400">filter_alt</span>
                </div>

                {{-- Select Bulan --}}
                <div class="relative flex-1 group">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <span class="material-symbols-rounded text-stone-400 text-[20px]">calendar_month</span>
                    </div>
                    <select name="bulan" class="auto-submit w-full pl-10 pr-4 py-3 bg-stone-50 border-0 text-stone-700 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:bg-white transition-colors cursor-pointer hover:bg-stone-100">
                        <option value="">Pilih Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Select Tahun --}}
                <div class="relative flex-1 group">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <span class="material-symbols-rounded text-stone-400 text-[20px]">schedule</span>
                    </div>
                    {{-- Bagian Select Tahun yang sudah diperbarui --}}
                    <div class="relative flex-1 group">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <span class="material-symbols-rounded text-stone-400 text-[20px]">schedule</span>
                        </div>
                        <select name="tahun" class="auto-submit w-full pl-10 pr-4 py-3 bg-stone-50 border-0 text-stone-700 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:bg-white transition-colors cursor-pointer hover:bg-stone-100">
                            <option value="">Pilih Tahun</option>
                            @php
                                $tahunSekarang = date('Y');
                            @endphp
                            {{-- Loop dari tahun sekarang mundur 5 tahun ke belakang --}}
                            @for ($th = $tahunSekarang; $th >= $tahunSekarang - 5; $th--)
                                <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>
                                    {{ $th }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </form>
        </div>

        {{-- 3. STATISTIK CARDS (Modern Grid) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Card Pemasukan --}}
            <div class="bg-white p-6 rounded-2xl shadow-soft border border-stone-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <span class="material-symbols-rounded text-6xl text-emerald-600">trending_up</span>
                </div>
                <div class="flex flex-col gap-1 relative z-10">
                    <div class="flex items-center gap-2 text-emerald-600 mb-2">
                        <span class="p-1.5 bg-emerald-100 rounded-lg material-symbols-rounded text-lg">arrow_upward</span>
                        <span class="text-xs font-bold uppercase tracking-wider">Total Masuk</span>
                    </div>
                    <span class="text-3xl font-extrabold text-stone-800 tracking-tight">
                        Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-stone-500 font-medium mt-1">
                        {{ $kasMasuk->count() }} transaksi berhasil
                    </span>
                </div>
            </div>

            {{-- Card Pengeluaran --}}
            <div class="bg-white p-6 rounded-2xl shadow-soft border border-stone-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <span class="material-symbols-rounded text-6xl text-rose-600">trending_down</span>
                </div>
                <div class="flex flex-col gap-1 relative z-10">
                    <div class="flex items-center gap-2 text-rose-600 mb-2">
                        <span class="p-1.5 bg-rose-100 rounded-lg material-symbols-rounded text-lg">arrow_downward</span>
                        <span class="text-xs font-bold uppercase tracking-wider">Total Keluar</span>
                    </div>
                    <span class="text-3xl font-extrabold text-stone-800 tracking-tight">
                        Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-stone-500 font-medium mt-1">
                        {{ $kasKeluar->count() }} transaksi tercatat
                    </span>
                </div>
            </div>

            {{-- Card Saldo --}}
            <div class="bg-gradient-to-br from-brand-500 to-brand-600 p-6 rounded-2xl shadow-glow text-white relative overflow-hidden">
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/20 rounded-full blur-2xl"></div>
                <div class="absolute top-4 right-4 bg-white/20 p-2 rounded-xl backdrop-blur-sm">
                     <span class="material-symbols-rounded text-xl">account_balance_wallet</span>
                </div>

                <div class="flex flex-col gap-1 relative z-10">
                    <span class="text-brand-100 text-xs font-bold uppercase tracking-wider mb-2">Sisa Saldo</span>
                    <span class="text-3xl font-extrabold tracking-tight">
                        Rp {{ number_format($selisihKas, 0, ',', '.') }}
                    </span>
                    <div class="flex items-center gap-1 mt-1 text-xs font-medium text-brand-50">
                        @if($selisihKas >= 0)
                            <span class="bg-white/20 px-2 py-0.5 rounded text-white">Surplus</span>
                        @else
                            <span class="bg-rose-500/50 px-2 py-0.5 rounded text-white border border-rose-400/30">Defisit</span>
                        @endif
                        <span>Periode ini</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. TABEL GABUNGAN (Saldo Running) --}}
        <div class="bg-white rounded-2xl shadow-soft border border-stone-200 overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-stone-100 flex items-center justify-between bg-stone-50/50">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-600">
                        <span class="material-symbols-rounded">receipt_long</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-stone-800">Semua Transaksi</h3>
                        <p class="text-xs text-stone-500">Mutasi rekening lengkap</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-stone-500 uppercase bg-stone-50 border-b border-stone-200 font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4 whitespace-nowrap">Tanggal</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4 text-center">Metode</th>
                            <th class="px-6 py-4 text-right">Masuk</th>
                            <th class="px-6 py-4 text-right">Keluar</th>
                            <th class="px-6 py-4 text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($laporan as $item)
                            <tr class="hover:bg-brand-50/30 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap text-stone-600 font-medium">
                                    {{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-stone-700">{{ $item['keterangan'] ?? $item['deskripsi'] ?? '-' }}</div>
                                    @if(isset($item['penerima']))
                                        <div class="text-xs text-stone-400">Penerima: {{ $item['penerima'] }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border
                                        {{ $item['kas_masuk'] > 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                                        {{ $item['kategori'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-stone-500 text-xs">
                                    {{ $item['metode_pembayaran'] }}
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap text-emerald-600 font-bold">
                                    {{ $item['kas_masuk'] > 0 ? '+ '.number_format($item['kas_masuk'],0,',','.') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap text-rose-600 font-bold">
                                    {{ $item['kas_keluar'] > 0 ? '- '.number_format($item['kas_keluar'],0,',','.') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap font-bold text-stone-800 bg-stone-50/50 group-hover:bg-brand-50/50">
                                    Rp {{ number_format($item['saldo'],0,',','.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-stone-400">
                                    <span class="material-symbols-rounded text-4xl mb-2 opacity-50">inbox</span>
                                    <p>Tidak ada data transaksi untuk periode ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 5. DETAIL KAS MASUK & KELUAR (Grid 2 Kolom) --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

            {{-- Tabel Kas Masuk --}}
            <div class="bg-white rounded-2xl shadow-soft border border-stone-200 overflow-hidden flex flex-col h-full">
                <div class="p-5 border-b border-stone-100 flex items-center gap-3 bg-emerald-50/30">
                    <span class="material-symbols-rounded text-emerald-500">trending_up</span>
                    <h3 class="font-bold text-stone-800">Rincian Pemasukan</h3>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-stone-500 uppercase bg-stone-50 font-semibold">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Sumber</th>
                                <th class="px-4 py-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse($kasMasuk->take(5) as $m)
                                <tr class="hover:bg-emerald-50/20 transition">
                                    <td class="px-4 py-3 whitespace-nowrap text-stone-600">{{ \Carbon\Carbon::parse($m->tanggal_transaksi)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-stone-700 font-medium truncate max-w-[150px]">{{ $m->keterangan ?? $m->kategori }}</td>
                                    <td class="px-4 py-3 text-right text-emerald-600 font-bold">Rp {{ number_format($m->total,0,',','.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-4 py-6 text-center text-stone-400 text-xs">Data kosong</td></tr>
                            @endforelse
                        </tbody>
                        @if($kasMasuk->count() > 5)
                            <tfoot class="bg-stone-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-center text-xs">
                                        <a href="{{ route('kas-masuk.index') }}" class="text-emerald-600 hover:text-emerald-700 font-bold hover:underline">Lihat Semua ({{ $kasMasuk->count() }})</a>
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Tabel Kas Keluar --}}
            <div class="bg-white rounded-2xl shadow-soft border border-stone-200 overflow-hidden flex flex-col h-full">
                <div class="p-5 border-b border-stone-100 flex items-center gap-3 bg-rose-50/30">
                    <span class="material-symbols-rounded text-rose-500">trending_down</span>
                    <h3 class="font-bold text-stone-800">Rincian Pengeluaran</h3>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-stone-500 uppercase bg-stone-50 font-semibold">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Keperluan</th>
                                <th class="px-4 py-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse($kasKeluar->take(5) as $k)
                                <tr class="hover:bg-rose-50/20 transition">
                                    <td class="px-4 py-3 whitespace-nowrap text-stone-600">{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-stone-700 font-medium truncate max-w-[150px]">{{ $k->deskripsi ?? $k->kategori }}</td>
                                    <td class="px-4 py-3 text-right text-rose-600 font-bold">Rp {{ number_format($k->nominal,0,',','.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-4 py-6 text-center text-stone-400 text-xs">Data kosong</td></tr>
                            @endforelse
                        </tbody>
                        @if($kasKeluar->count() > 5)
                            <tfoot class="bg-stone-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-center text-xs">
                                        <a href="{{ route('kas-keluar.index') }}" class="text-rose-600 hover:text-rose-700 font-bold hover:underline">Lihat Semua ({{ $kasKeluar->count() }})</a>
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

        </div>

    </div>

    {{-- Script Auto Submit Filter --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selects = document.querySelectorAll('.auto-submit');
            selects.forEach(select => {
                select.addEventListener('change', () => {
                    document.getElementById('filterForm').submit();
                });
            });
        });
    </script>
</x-app-layout>
