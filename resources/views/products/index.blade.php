<x-app-layout>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

<div class="max-w-8xl mx-auto px-4 sm:px-9 mt-5">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <div>
            <h1 class="text-xl font-semibold mt-4">Manajemen Produk</h1>
            <p class="text-gray-500 text-sm">Kelola data produk untuk POS</p>
        </div>

        <button 
            onclick="openModal()"
            class="bg-black text-white px-4 py-2 rounded-lg flex items-center gap-2 w-full sm:w-auto justify-center">
            + Tambah Produk
        </button>
    </div>

    <!-- STATISTIC CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <div class="p-5 border rounded-xl bg-white shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Produk</p>
                    <h3 class="text-xl font-medium mt-4">{{ $totalProduk }}</h3>
                </div>
                <span class="material-symbols-outlined text-blue-500 text-3xl mt-10">package_2</span>
            </div>
        </div>

        <div class="p-5 border rounded-xl bg-white shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Stok</p>
                    <h3 class="text-xl font-medium mt-4">{{ $totalStok }}</h3>
                </div>
                <span class="material-symbols-outlined text-green-500 text-3xl mt-10">inventory</span>
            </div>
        </div>

        <div class="p-5 border rounded-xl bg-white shadow-sm">
            <p class="text-gray-500 text-sm">Nilai Stok</p>
            <h3 class="text-xl font-medium mt-4">Rp {{ number_format($nilaiStok, 0, ',', '.') }}</h3>
        </div>

        <div class="p-5 border rounded-xl bg-white shadow-sm border-orange-400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Stok Rendah</p>
                    <h3 class="text-xl font-medium mt-4 text-orange-400">{{ $stokRendah }}</h3>
                </div>
                <span class="material-symbols-outlined text-orange-500 text-3xl mt-10">box</span>
            </div>
        </div>
    </div>

    <!-- PRODUCT GRID -->
    <div class="mt-8">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            @foreach($products as $produk)
            <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">

                <img 
                    src="{{ $produk->foto ? asset('storage/'.$produk->foto) :asset('assets/images/default-product.jpg') }}"
                    class="w-full h-48 object-cover"
                />



                <div class="p-4">

                    <!-- TOP BADGES -->
                    <div class="flex justify-between items-center mb-3">
                        <span class="px-3 py-1 text-sm font-semibold bg-blue-600 text-white rounded-lg">
                            {{ ucfirst($produk->kategori) }}
                        </span>

                        <span class="px-3 py-1 text-sm font-semibold rounded-lg 
                            {{ $produk->stok <= 5 ? 'bg-orange-500 text-white' : 'bg-green-500 text-white' }}">
                            Stok: {{ $produk->stok }}
                        </span>
                    </div>

                    <!-- NAME -->
                    <h3 class="text-lg font-semibold mb-3">{{ $produk->nama }}</h3>

                    <!-- PRICE INFO -->
                    <div class="grid grid-cols-2 gap-1 text-sm mb-4">
                        <span class="text-gray-500">Harga:</span>
                        <span class="text-blue-600 font-semibold text-right">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </span>

                        <span class="text-gray-500">Nilai Total:</span>
                        <span class="font-semibold text-right">
                            Rp {{ number_format($produk->harga * $produk->stok, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex items-center justify-between gap-2 pt-3 border-t">

                        <!-- EDIT -->
                        <button 
                            class="w-full py-2 border rounded-xl text-gray-700 hover:bg-gray-50 btnEdit"
                            data-id="{{ $produk->id }}"
                            data-nama="{{ $produk->nama }}"
                            data-kategori="{{ $produk->kategori }}"
                            data-harga="{{ $produk->harga }}"
                            data-stok="{{ $produk->stok }}">
                            <span class="material-symbols-outlined text-lg">edit</span>
                        </button>

                        <!-- DELETE -->
                        <button 
                            class="w-full py-2 border rounded-xl text-red-500 hover:bg-red-50 delete-product-btn"
                            data-id="{{ $produk->id }}"
                            data-nama="{{ $produk->nama }}">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>

                    </div>

                </div>

            </div>
            @endforeach

        </div>
    </div>

    <!-- MODAL ADD -->
    <div id="modalAddProduct" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-xl shadow-xl p-6 relative mx-3">
            <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-black text-xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">Tambah Produk</h2>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="text-sm font-semibold">Nama Produk</label>
                    <input type="text" name="nama" class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="text-sm font-semibold">Kategori</label>
                    <input type="text" name="kategori" class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="text-sm font-semibold">Harga</label>
                    <input type="number" name="harga" class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="text-sm font-semibold">Stok</label>
                    <input type="number" name="stok" class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="text-sm font-semibold">Foto Produk</label>
                    <input type="file" name="foto" class="w-full border rounded-lg p-2">
                </div>

                <button class="bg-black w-full text-white py-2 rounded-lg">Simpan</button>
            </form>
        </div>
    </div>


    <!-- MODAL EDIT -->
    <div id="modalEditProduct" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-xl shadow-xl p-6 relative mx-3">
            <button onclick="closeModalEdit()" class="absolute top-3 right-3 text-gray-500 hover:text-black text-xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">Edit Produk</h2>

            <form id="formEditProduct" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-sm font-semibold">Nama Produk</label>
                    <input type="text" id="editNama" name="nama" class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="text-sm font-semibold">Kategori</label>
                    <input type="text" id="editKategori" name="kategori" class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="text-sm font-semibold">Harga</label>
                    <input type="number" id="editHarga" name="harga" class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="text-sm font-semibold">Stok</label>
                    <input type="number" id="editStok" name="stok" class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label class="text-sm font-semibold">Foto Produk (opsional)</label>
                    <input type="file" name="foto" class="w-full border rounded-lg p-2">
                </div>

                <button class="bg-black w-full text-white py-2 rounded-lg">Simpan Perubahan</button>
            </form>
        </div>
    </div>

</div>


<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function openModal(){ const m=document.getElementById('modalAddProduct'); m.classList.remove('hidden'); m.classList.add('flex') }
function closeModal(){ const m=document.getElementById('modalAddProduct'); m.classList.add('hidden'); m.classList.remove('flex') }
document.getElementById('modalAddProduct').addEventListener('click',e=>{ if(e.target===e.currentTarget) closeModal() })

function openModalEdit(){ const m=document.getElementById('modalEditProduct'); m.classList.remove('hidden'); m.classList.add('flex') }
function closeModalEdit(){ const m=document.getElementById('modalEditProduct'); m.classList.add('hidden'); m.classList.remove('flex') }

document.querySelectorAll('.btnEdit').forEach(btn=>{
    btn.addEventListener('click',function(){
        document.getElementById('editNama').value = this.dataset.nama;
        document.getElementById('editKategori').value = this.dataset.kategori;
        document.getElementById('editHarga').value = this.dataset.harga;
        document.getElementById('editStok').value = this.dataset.stok;
        document.getElementById('formEditProduct').action = "/products/"+this.dataset.id;
        openModalEdit();
    })
});

document.getElementById('modalEditProduct').addEventListener('click',e=>{ if(e.target===e.currentTarget) closeModalEdit() })
</script>

<script>
// DELETE PRODUCT
document.querySelectorAll('.delete-product-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        let id = this.dataset.id;
        let nama = this.dataset.nama;

        Swal.fire({
            title: "Hapus Produk?",
            text: "Produk \"" + nama + "\" akan dihapus permanen.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Buat form delete dinamis
                let form = document.createElement("form");
                form.action = "/products/" + id;
                form.method = "POST";

                let csrf = document.createElement("input");
                csrf.type = "hidden";
                csrf.name = "_token";
                csrf.value = "{{ csrf_token() }}";

                let method = document.createElement("input");
                method.type = "hidden";
                method.name = "_method";
                method.value = "DELETE";

                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});
</script>


</x-app-layout>
