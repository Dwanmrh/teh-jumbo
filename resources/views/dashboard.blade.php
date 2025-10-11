<x-app-layout>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f7f7f7;
        }

        h2 {
            font-weight: 600;
            color: #2F362C;
        }

        /* ===== Filter Box ===== */
        .filter-box {
            background-color: #fff;
            padding: 10px 20px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            max-width: 320px;
            margin-bottom: 25px;
        }

        .filter-box select {
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: inherit;
            background-color: #F5C04C;
            color: #222;
            font-weight: 500;
            flex: 1;
        }

        /* ===== Cards ===== */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.15);
            position: relative;
            min-height: 100px;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            border-radius: 6px 0 0 6px;
        }

        .card.green::before { background-color: #7AC943; }
        .card.red::before { background-color: #E74C3C; }
        .card.cyan::before { background-color: #1ABC9C; }
        .card.purple::before { background-color: #8E44AD; }
        .card.yellow::before { background-color: #F5C04C; }

        .card h3 {
            font-size: 15px;
            margin: 0 0 8px;
            color: #2F362C;
        }

        .card p {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            color: #222;
            word-wrap: break-word;
        }

        /* ===== Chart Container ===== */
        .chart-container {
            background-color: #fff;
            border-radius: 6px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            overflow: hidden;
            width: 100%;
        }

        .chart-header {
            background-color: #B6D96C;
            padding: 10px 15px;
            font-weight: 600;
            color: #2F362C;
        }

        .chart-body {
            padding: 15px;
        }

        canvas {
            width: 100% !important;
            height: auto !important;
            max-height: 400px;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .filter-box {
                width: 100%;
                justify-content: space-between;
            }

            .chart-body {
                padding: 10px;
            }

            .card p {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .card {
                padding: 15px;
            }

            .card h3 {
                font-size: 14px;
            }

            .card p {
                font-size: 15px;
            }

            canvas {
                max-height: 300px;
            }
        }
    </style>

    <div class="py-8 bg-[#f7f7f7]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Judul Halaman --}}
            <h2 class="text-2xl font-semibold text-[#2F362C] mb-6">Dashboard</h2>

            {{-- Filter --}}
            <div class="filter-box">
                <label for="filter">Filter</label>
                <select id="filter">
                    <option>Pilih Jangka Waktu</option>
                    <option>Harian</option>
                    <option>Bulanan</option>
                </select>
            </div>

            {{-- Cards --}}
            <div class="cards">
                <div class="card yellow">
                    <h3>Profit Kotor</h3>
                    <p>Rp 10.000.000</p>
                </div>
                <div class="card red">
                    <h3>Sewa Tempat</h3>
                    <p>Rp 4.500.000</p>
                </div>
                <div class="card cyan">
                    <h3>Operasional</h3>
                    <p>Rp 4.500.000</p>
                </div>
                <div class="card purple">
                    <h3>Karyawan</h3>
                    <p>Rp 5.500.000</p>
                </div>
                <div class="card green">
                    <h3>Profit Bersih</h3>
                    <p>Rp 15.000.000</p>
                </div>
            </div>

            {{-- Chart --}}
            <div class="chart-container">
                <div class="chart-header">Grafik Profit & Pengeluaran</div>
                <div class="chart-body">
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script>
        const ctx = document.getElementById('incomeChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
                datasets: [
                    {
                        label: 'Profit Kotor',
                        data: [10000000, 12000000, 11000000, 14000000, 13000000, 12500000, 15000000],
                        backgroundColor: '#F5C04C'
                    },
                    {
                        label: 'Sewa Tempat',
                        data: [4500000, 4600000, 4400000, 4700000, 4500000, 4600000, 4800000],
                        backgroundColor: '#E74C3C'
                    },
                    {
                        label: 'Operasional',
                        data: [4500000, 4200000, 4700000, 4300000, 4400000, 4500000, 4600000],
                        backgroundColor: '#1ABC9C'
                    },
                    {
                        label: 'Karyawan',
                        data: [5500000, 5300000, 5600000, 5800000, 5400000, 5700000, 5900000],
                        backgroundColor: '#8E44AD'
                    },
                    {
                        label: 'Profit Bersih',
                        data: [15000000, 16000000, 15500000, 17000000, 16500000, 17200000, 18000000],
                        backgroundColor: '#7AC943'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
