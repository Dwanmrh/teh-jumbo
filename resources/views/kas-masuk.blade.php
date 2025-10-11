<x-app-layout>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

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
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 15px;
            background-color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            margin-bottom: 25px;
        }

        .filter-box label {
            font-weight: 500;
            color: #2F362C;
        }

        .filter-box select,
        .filter-box input {
            background-color: #F5C04C;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-family: inherit;
            font-weight: 500;
            color: #2F362C;
            cursor: pointer;
        }

        .filter-box select:focus,
        .filter-box input:focus {
            outline: none;
        }

        .filter-box button {
            background-color: #7AC943;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .filter-box button:hover {
            background-color: #6AB13B;
        }

        /* ===== Table Box ===== */
        .table-box {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            font-size: 14px;
        }

        th {
            background-color: #FFF2CF;
            font-weight: 600;
            color: #2F362C;
        }

        td {
            background-color: #fff;
        }

        .aksi-btn {
            display: flex;
            justify-content: center;
            gap: 6px;
        }

        .btn-edit {
            background-color: #F5C04C;
            color: #2F362C;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-hapus {
            background-color: #FF5252;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-edit:hover {
            background-color: #E0AC3B;
        }

        .btn-hapus:hover {
            background-color: #E04444;
        }
    </style>

    <div class="py-8 bg-[#f7f7f7]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Judul --}}
            <h2 class="text-2xl font-semibold text-[#2F362C] mb-6">Kas Masuk</h2>

            {{-- Filter --}}
            <div class="filter-box">
                <label for="filter-type">Filter</label>
                <select id="filter-type" onchange="toggleFilterInput()">
                    <option value="">Pilih Jangka Waktu</option>
                    <option value="harian">Harian</option>
                    <option value="bulanan">Bulanan</option>
                </select>

                <input type="date" id="filter-harian" style="display:none;">
                <input type="month" id="filter-bulanan" style="display:none;">
            </div>

            {{-- Tabel --}}
            <div class="table-box">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Transaksi</th>
                            <th>Nama Menu</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 1; $i <= 5; $i++)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>2025-10-11</td>
                                <td>Es Teh</td>
                                <td>3</td>
                                <td>Rp. 5.000</td>
                                <td>Rp. 15.000</td>
                                <td>Tunai</td>
                                <td>
                                    <div class="aksi-btn">
                                        <button class="btn-edit">Edit</button>
                                        <button class="btn-hapus">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        function toggleFilterInput() {
            const type = document.getElementById('filter-type').value;
            const harian = document.getElementById('filter-harian');
            const bulanan = document.getElementById('filter-bulanan');

            if (type === 'harian') {
                harian.style.display = 'inline-block';
                bulanan.style.display = 'none';
            } else if (type === 'bulanan') {
                harian.style.display = 'none';
                bulanan.style.display = 'inline-block';
            } else {
                harian.style.display = 'none';
                bulanan.style.display = 'none';
            }
        }
    </script>

</x-app-layout>
