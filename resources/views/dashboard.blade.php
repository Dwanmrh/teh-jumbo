<x-app-layout>
    {{-- FONT & CHART.JS / External Resources --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <div class="p-4 sm:p-6 bg-[#F7F7F7] min-h-screen font-[Outfit]">

        {{-- HEADER & FILTER SECTION --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">

            <h2 class="text-2xl font-bold text-[#2F362C]">Dashboard</h2>

            {{-- FILTER DI SEBELAH KANAN (BULAN & TAHUN) --}}
            {{-- Perubahan: Menggunakan flex-wrap dan justify-end agar filter rapi di mobile --}}
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap justify-end items-center gap-3 w-full sm:w-auto">

                {{-- FILTER BULAN --}}
                <div class="relative w-full sm:w-auto">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-base">
                        calendar_today
                    </span>

                    <select name="bulan"
                            onchange="this.form.submit()"
                            class="appearance-none pl-10 pr-8 py-2 rounded-xl bg-gray-100 border border-gray-200 text-sm shadow-sm w-full">
                        <option value="">Semua Bulan</option>
                        @foreach(range(1, 12) as $bln)
                            @php
                                $val = now()->year .'-'. str_pad($bln, 2, '0', STR_PAD_LEFT);
                            @endphp
                            <option value="{{ $val }}" {{ request('bulan') == $val ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $bln)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">▾</span>
                </div>

                {{-- FILTER TAHUN --}}
                <div class="relative w-full sm:w-auto">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-base">
                        calendar_today
                    </span>

                    <select name="tahun"
                        onchange="this.form.submit()"
                        class="appearance-none pl-10 pr-8 py-2 rounded-xl bg-gray-100 border border-gray-200 text-sm shadow-sm w-full">

                        <option value="" {{ request('tahun') == '' ? 'selected' : '' }}>Semua Tahun</option>

                        @foreach($tahunList as $t)
                            <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>

                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">▾</span>
                </div>
            </form>
        </div>

        

        {{-- SUMMARY CARDS --}}
        {{-- Perubahan: Sudah menggunakan grid-cols-1 di mobile (default) dan sm:grid-cols-3 --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

            <div class="p-5 bg-white rounded-xl shadow border-l-4 border-emerald-500 min-h-[150px] w-full">
                <div class="flex justify-between items-start">
                    <p class="text-sm text-emerald-700">Total Kas Masuk</p>
                    <div class="p-1 border border-emerald-300/40 bg-emerald-100 rounded-xl flex items-center justify-center -mt-2">
                        <span class="material-symbols-outlined text-green-600 text-xs"> trending_up </span>
                    </div>
                </div>

                <h3 class="text-xl mt-8 text-emerald-600">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h3>
                <div class="flex justify-between items-center mt-2">
                    <p class="text-xs text-gray-400">
                        {{ $countMasuk }} transaksi
                    </p>
                    <p class="text-xs mt-1 text-emerald-600 flex items-center gap-1">
                        <span class="material-symbols-outlined !text-[14px] leading-none">
                            arrow_outward
                        </span>
                        Rata-rata: Rp {{ $countMasuk > 0 ? number_format($totalMasuk / $countMasuk, 0, ',', '.') : 0 }}
                    </p>
                </div>
            </div>

            <div class="p-5 bg-white/80 rounded-xl shadow border-l-4 border-rose-500 min-h-[150px] w-full">
                <div class="flex justify-between items-start">
                    <p class="text-sm text-rose-700">Total Kas Keluar</p>
                    <div class="p-1 border border-rose-300/40 bg-rose-100 rounded-xl flex items-center justify-center -mt-2">
                        <span class="material-symbols-outlined text-rose-600 text-xs"> trending_down </span>
                    </div>
                </div>

                <h3 class="text-xl mt-8 text-rose-600">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h3>
                <div class="flex justify-between items-center mt-2">
                    <p class="text-xs text-gray-400 mt-2">{{ $countKeluar }} transaksi</p>

                    <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined !text-[14px] leading-none">
                            south_east
                        </span>
                        Rata-rata: Rp {{ $countKeluar > 0 ? number_format($totalKeluar / $countKeluar, 0, ',', '.') : 0 }}
                    </p>
                </div>
            </div>

            @php
                $isSurplus = $saldoAkhir >= 0;
            @endphp
            <div class="p-5 bg-white/80 rounded-xl shadow border-l-4 {{ $isSurplus ? 'border-blue-600' : 'border-yellow-400' }} min-h-[150px] w-full">
                <div class="flex justify-between items-start">
                    <p class="text-sm font-medium {{ $isSurplus ? 'text-blue-700' : 'text-yellow-500' }}">Saldo Saat Ini</p>

                    {{-- Icon wallet --}}
                    <div class="p-1 border {{ $isSurplus ? 'bg-blue-300/40 border-blue-300/40' : 'bg-yellow-300/40 border-yellow-300/40' }} rounded-xl flex items-center justify-center -mt-2 ">
                        <span class="material-symbols-outlined text-xs {{ $isSurplus ? 'text-blue-700' : 'text-yellow-500' }}">wallet</span>
                    </div>
                </div>

                {{-- Nominal --}}
                <h3 class="text-xl mt-7 {{ $isSurplus ? 'text-blue-600' : 'text-yellow-400' }}">
                    Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                </h3>

                {{-- Status --}}
                <p class="text-sm mt-3 font-medium inline-flex items-center gap-1 {{ $isSurplus ? 'text-blue-600' : 'text-yellow-400' }}">
                    <span class="material-symbols-outlined text-sm">
                        {{ $isSurplus ? 'check_circle' : 'error' }}
                    </span>
                    {{ $isSurplus ? 'Surplus' : 'Defisit' }}
                </p>
            </div>
        </div>

        

        {{-- GRAFIK TREN KAS (Bar Chart) --}}
        <div class="bg-white p-6 rounded-xl shadow mb-8">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-2 sm:mb-0">Tren Kas Masuk & Keluar</h3>

                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full inline-block bg-[#08d073cc]"></span>
                        <span class="text-gray-700">Kas Masuk</span>
                    </div>

                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full inline-block bg-[#E74C3Ccc]"></span>
                        <span class="text-gray-700">Kas Keluar</span>
                    </div>
                </div>
            </div>

            {{-- Perubahan: Menggunakan tinggi yang lebih fleksibel di mobile --}}
            <div class="w-full h-64 md:h-80"> 
                <canvas id="trenKas" class="w-full h-full"></canvas>
            </div>

        </div>

        

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8"> {{-- Diubah ke md:grid-cols-2 --}}

            {{-- Pie Chart Masuk --}}
            <div class="bg-white p-6 rounded-xl shadow-md flex flex-col">
                <h3 class="text-lg font-semibold text-gray-700">Persentase Kategori Kas Masuk</h3>
                <p class="text-sm text-gray-500 mb-4 -mt-1">Distribusi pemasukan berdasarkan kategori</p>

                <div class="flex justify-center items-center w-full h-64">
                    <canvas id="pieMasuk" class="w-full h-full"></canvas>
                </div>

                <div id="legendMasuk" class="mt-4 text-sm"></div>
            </div>

            {{-- Pie Chart Keluar --}}
            <div class="bg-white p-6 rounded-xl shadow-md flex flex-col">
                <h3 class="text-lg font-semibold text-gray-700">Persentase Kategori Kas Keluar</h3>
                <p class="text-sm text-gray-500 mb-4 -mt-1">Distribusi pengeluaran berdasarkan kategori</p>

                <div class="flex justify-center items-center w-full h-64">
                    <canvas id="pieKeluar" class="w-full h-full"></canvas>
                </div>

                <div id="legendKeluar" class="mt-4 text-sm"></div>
            </div>

        </div>
        
        

        {{-- SALDO KUMULATIF (Line Chart) --}}
        <div class="bg-white p-5 rounded-xl shadow">
            <h3 class="text-lg font-semibold mb-3">Tren Saldo Kumulatif</h3>
            {{-- Perubahan: Menggunakan tinggi yang lebih fleksibel di mobile --}}
            <canvas id="saldoChart" class="max-h-64 h-64"></canvas> 
        </div>

    </div>

    {{-- SCRIPTS CHART.JS --}}
    <script>
    
        // WARNA UNTUK PIE CHART DIDEFINISIKAN DI AWAL
        const colorSetMasuk = ['#16A34A', '#2563EB', '#F97316', '#EF4444', '#FACC15', '#6B7280'];
        const colorSetKeluar = ['#F97316', '#EF4444', '#FACC15', '#3B82F6', '#16A34A', '#6B7280'];

        /**
         * Fungsi untuk membuat legend kustom di luar canvas chart.
         */
        function generateLegend(labels, data, colors, elementId) {
            let total = data.reduce((a, b) => a + b, 0);
            let html = "";

            labels.forEach((label, i) => {
                let percent = total > 0 ? ((data[i] / total) * 100).toFixed(0) : 0;

                html += `
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-3 h-3 rounded-sm" style="background:${colors[i]}"></div>
                        <span class="text-gray-700 font-medium">${label}</span>
                        <span class="text-gray-500 ml-1">${percent}%</span>
                    </div>
                `;
            });

            document.getElementById(elementId).innerHTML = html;
        }

        /**
         * Fungsi untuk membuat Pie Chart dan Legendnya.
         */
        function createPieChart(canvasId, labels, values, legendId, colors) {

            const numericValues = values.map(v => Number(v));
            const total = numericValues.reduce((a, b) => a + b, 0);

            new Chart(document.getElementById(canvasId), {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: numericValues,
                        backgroundColor: colors,
                        borderWidth: 1,
                        borderColor: "#fff",
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        datalabels: {
                            color: "#000",
                            font: { size: 14, weight: "600" },
                            formatter: (value) => {
                                if (total === 0) return "";
                                // Hanya tampilkan label untuk slice yang cukup besar (>= 5%)
                                if (((value / total) * 100) < 5) return ""; 
                                return ((value / total) * 100).toFixed(0) + "%";
                            },
                            anchor: "center",
                            align: "center"
                        }
                    }
                }
            });

            // Panggil fungsi untuk membuat legend
            generateLegend(labels, numericValues, colors, legendId);
        }
        
        // Mulai eksekusi DOM Content Loaded
        document.addEventListener('DOMContentLoaded', function () {

            // --- LANGKAH 1: REGISTRASI PLUGIN SECARA GLOBAL ---
            Chart.register(ChartDataLabels); 
            // --------------------------------------------------

            // --- LANGKAH 2: DEKLARASI DATA DARI PHP KE JS ---
            // Deklarasi data untuk Pie Chart
            const masukLabel = {!! json_encode($kategoriMasukLabel) !!};
            const masukNominal = {!! json_encode($kategoriMasukNominal) !!};
            const keluarLabel = {!! json_encode($kategoriKeluarLabel) !!};
            const keluarNominal = {!! json_encode($kategoriKeluarNominal) !!};

            // Deklarasi data untuk Bar & Line Chart
            const labelList = {!! json_encode($labelList) !!};
            const dataMasuk = {!! json_encode($dataMasuk) !!};
            const dataKeluar = {!! json_encode($dataKeluar) !!};
            const saldoKumulatif = {!! json_encode($saldoKumulatif) !!};

            // --- LANGKAH 3: INISIALISASI BAR CHART (Tren Kas Masuk & Keluar) ---
            new Chart(document.getElementById('trenKas'), {
                type: 'bar', 
                data: {
                    labels: labelList,
                    datasets: [
                        {
                            label: 'Kas Masuk',
                            data: dataMasuk,
                            backgroundColor: '#08d073cc',
                            borderRadius: 6
                        },
                        {
                            label: 'Kas Keluar',
                            data: dataKeluar,
                            backgroundColor: '#E74C3Ccc',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        },
                        datalabels: { display: false }
                    },
                    scales: { 
                        x: { 
                            grid: { display: false },
                            ticks: { maxRotation: 0, minRotation: 0, autoSkip: true, maxTicksLimit: 6 }
                        }, 
                        y: { 
                            grid: { color: "#E5E7EB60" },
                            ticks: { 
                                maxTicksLimit: 5,
                                callback: function(value) {
                                    if (value >= 1000000 || value <= -1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                                    } else if (value >= 1000 || value <= -1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + ' K';
                                    }
                                    return 'Rp ' + value;
                                }
                            }
                        } 
                    }
                }
            });

            // --- LANGKAH 4: INISIALISASI LINE CHART (Saldo Kumulatif) ---
            new Chart(document.getElementById('saldoChart'), {
                type: 'line',
                data: {
                    labels: labelList,
                    datasets: [{
                        label: 'Saldo',
                        data: saldoKumulatif,
                        borderColor: '#5D9CEC',
                        backgroundColor: 'rgba(93,156,236,0.25)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        },
                        datalabels: {
                            align: 'end',
                            anchor: 'end',
                            formatter: (value, context) => {
                                // Hanya tampilkan label pada titik data terakhir
                                if (value == context.dataset.data[context.dataset.data.length - 1]) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                                return null;
                            },
                            color: (context) => {
                                return context.dataset.data[context.dataIndex] >= 0 ? '#5D9CEC' : '#EF4444';
                            },
                            font: { size: 10 }
                        }
                    },
                    scales: { 
                        x: { 
                            grid: { display: false },
                            ticks: { maxRotation: 0, minRotation: 0, autoSkip: true, maxTicksLimit: 6 }
                        }, 
                        y: { 
                            grid: { color: "#E5E7EB60" },
                            ticks: { 
                                maxTicksLimit: 5,
                                callback: function(value) {
                                    if (value >= 1000000 || value <= -1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                                    } else if (value >= 1000 || value <= -1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + ' K';
                                    }
                                    return 'Rp ' + value;
                                }
                            }
                        } 
                    }
                }
            });


            // --- LANGKAH 5: INISIALISASI PIE CHARTS ---
            
            // Panggil fungsi untuk Pie Chart Kas Masuk
            createPieChart(
                "pieMasuk",
                masukLabel,
                masukNominal,
                "legendMasuk",
                colorSetMasuk
            );

            // Panggil fungsi untuk Pie Chart Kas Keluar
            createPieChart(
                "pieKeluar",
                keluarLabel,
                keluarNominal,
                "legendKeluar",
                colorSetKeluar
            );

        });

    </script>
</x-app-layout>