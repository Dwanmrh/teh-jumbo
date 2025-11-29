<x-app-layout>

<div class="max-w-8xl mx-auto px-4 sm:px-9 mt-5">

    <!-- HEADER -->
    <div class="mb-4">
        <h3 class="text-2xl font-bold">Point of Sale (POS)</h3>
        <p class="text-gray-500 text-lg">Kasir penjualan produk</p>
    </div>

    <!-- MAIN CONTENT (2 kolom) -->
    <div class="flex gap-5">

        {{-- CARD PRODUK --}}
        <div class="w-3/4 pt-2">
            <div class="bg-white shadow p-5 rounded-xl mb-5">
                <h2 class="text-xl font-bold mb-4">Daftar Produk</h2>
                <p class="text-gray-500 text-sm -mt-3">Pilih produk untuk ditambahkan ke keranjang</p>

                <input type="text" id="searchInput"
                    placeholder="Cari produk..."
                    class="mt-4 w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200">

                <div class="grid grid-cols-4 gap-4 mt-5">
                    @foreach($products as $p)
                         <button 
                            data-name="{{ strtolower($p->nama) }}"
                            data-category="{{ strtolower($p->kategori) }}"
                            onclick="addToCart('{{ $p->id }}','{{ $p->nama }}', {{ $p->harga }})"
                            class="product-card bg-white p-4 border rounded-xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all w-full text-left flex flex-col">

                            <!-- Foto Produk -->
                            <div class="h-44 bg-gray-100 flex items-center justify-center rounded-lg overflow-hidden w-full">
                                @if($p->foto)
                                <img src="{{ asset('storage/'.$p->foto) }}"
                                    class="w-full h-full object-cover">
                                @else
                                <img 
                                    src="{{ $p->foto ? asset('storage/'.$p->foto) :asset('assets/images/default-product.jpg') }}"
                                    class="w-full h-48 object-cover"
                                />

                                @endif
                            </div>

                            <!-- Detail Produk -->
                            <div class="mt-3 flex flex-col flex-1">
                                <p class="font-semibold text-base">{{ $p->nama }}</p>
                                <p class="text-gray-500 text-sm">{{ $p->kategori }}</p>

                                <p class="text-green-600 font-semibold mt-auto text-base">
                                    Rp {{ number_format($p->harga) }}
                                </p>

                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Stok: {{ $p->stok }}</p>
                                </div>
                            </div>
                        </button>

                    @endforeach
                </div>
            </div>
        </div>

        {{-- CARD KERANJANG --}}
        <div class="w-1/4 pt-2">
            <div class="bg-white rounded-xl shadow p-5 sticky top-5 flex flex-col">

                <h2 class="text-lg font-bold mb-4">Keranjang</h2>

                <div id="cartContainer" class="flex-1 max-h-[450px] overflow-y-auto">
                    <div class="h-full flex flex-col items-center justify-center text-gray-400">
                        <span class="material-symbols-outlined text-5xl mb-2">
                            shopping_cart
                        </span>
                        <p class="text-sm">Keranjang kosong</p>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>


{{-- MODAL CHECKOUT --}}
<div id="checkoutModal"
     class="fixed inset-0 bg-black/40 hidden justify-center items-center z-50">

    <div class="bg-white rounded-xl p-6 w-[500px]">
        <h2 class="text-xl font-bold mb-2">Checkout</h2>
        <p class="text-sm text-gray-500 mb-4">Selesaikan transaksi penjualan</p>

        <div class="mb-3 p-3 bg-gray-100 rounded-lg border" id="modalItemList"></div>

        <div class="border-t pt-3 mb-4">
            <div class="flex justify-between">
                <span class="font-normal">Total Pembayaran:</span>
                <span id="modalTotal" class="font-bold text-green-600"></span>
            </div>
        </div>

        {{-- FORM --}}
        <form method="POST" action="{{ route('pos.checkout') }}"
              onsubmit="document.getElementById('cartData').value = JSON.stringify(cart)">
            @csrf
            <input type="hidden" id="modalTotalValue" name="total">


            <label class="block mb-1 font-medium">Metode Pembayaran</label>
            <select name="metode" class="w-full border rounded-lg p-2 mb-3">
                <option value="tunai">Tunai</option>
                <option value="transfer">Transfer</option>
                <option value="qris">QRIS</option>
            </select>

            <label class="block mb-1 font-medium">Metode Pembayaran</label>
            <input type="number" id="jumlahBayar" class="w-full mb-2 rounded-lg" placeholder="Masukkan jumlah bayar">


            <div class="flex gap-2 mt-3">
                <button type="button" class="quick-btn flex-1 text-center px-4 py-2 border rounded-lg text-xm" data-value="pas">
                    Pas
                </button>

                <button type="button" class="quick-btn flex-1 text-center px-4 py-2 border rounded-lg text-xm" data-value="50000">
                    Rp 50.000
                </button>

                <button type="button" class="quick-btn flex-1 text-center px-4 py-2 border rounded-lg text-xm" data-value="100000">
                    Rp 100.000
                </button>
            </div>


            <div id="kembalianBox" class="mt-3 p-3 rounded bg-green-100 text-green-600 hidden">
                Kembalian: <span id="kembalianText"></span>
            </div>


            <div class="mt-5 flex justify-end gap-3">
                <button type="button"
                        onclick="closeModal()"
                        class="px-4 py-2 border rounded-lg">
                    Batal
                </button>

                <button id="btnBayar"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg opacity-50 cursor-not-allowed"
                        disabled>
                    Bayar
                </button>
            </div>

        </form>
    </div>
</div>

<script>
// CART FRONTEND TANPA JQUERY
let cart = {};

// Tambah ke cart
function addToCart(id, name, price) {
    if (!cart[id]) {
        cart[id] = { name:name, price:price, qty:1 };
    } else {
        cart[id].qty++;
    }
    renderCart();
}

// Update qty
function updateQty(id, change){
    cart[id].qty += change;
    if(cart[id].qty <= 0) delete cart[id];
    renderCart();
}

// Hapus item
function removeItem(id){
    delete cart[id];
    renderCart();
}

// Render UI keranjang
function renderCart() {
    let container = document.getElementById("cartContainer");
    container.innerHTML = "";

    let total = 0;

    Object.keys(cart).forEach(id => {
        let item = cart[id];
        let subtotal = item.qty * item.price;
        total += subtotal;

        let keys = Object.keys(cart);

    if (keys.length === 0) {
        container.innerHTML = `
            <div class="h-full flex flex-col items-center justify-center text-gray-400">
                <span class="material-symbols-outlined text-5xl mb-2">shopping_cart</span>
                <p class="text-sm">Keranjang kosong</p>
            </div>
        `;
        return;
    }
    
        container.innerHTML += `
            <div class="flex justify-between items-center mb-3 border-b pb-2">
                <div>
                    <p class="font-semibold">${item.name}</p>
                    <p class="text-sm text-gray-500">Rp ${item.price.toLocaleString()}</p>

                    <div class="flex items-center gap-2 mt-1">
                        <button class="bg-gray-200 px-2 rounded" onclick="updateQty('${id}', -1)">-</button>
                        <span class="font-semibold">${item.qty}</span>
                        <button class="bg-gray-200 px-2 rounded" onclick="updateQty('${id}', 1)">+</button>
                    </div>
                </div>

                <div class="text-right">
                    <p class="font-bold text-green-600">${subtotal.toLocaleString()}</p>
                    <button class="text-red-500 text-sm" onclick="removeItem('${id}')">Hapus</button>
                </div>
            </div>
            
        `;

    });

    container.innerHTML += `
        <div class="mt-4 pt-3 border-t flex justify-between font-bold text-lg">
            <span>Total:</span>
            <span>${total.toLocaleString()}</span>
        </div>

        <button onclick="openModal()" class="mt-4 w-full bg-green-600 text-white py-3 rounded-xl">
            Checkout
        </button>
    `;
}



// Modal checkout
function openModal(){
    let list = "";
    let total = 0;

    Object.keys(cart).forEach(id => {
        let item = cart[id];
        let subtotal = item.qty * item.price;
        total += subtotal;

        list += `<p>${item.qty}x ${item.name} — Rp ${subtotal.toLocaleString()}</p>`;
    });

    document.getElementById("modalItemList").innerHTML = list;
    document.getElementById("modalTotal").textContent = "Rp " + total.toLocaleString();

    // simpan total ke input hidden
    document.getElementById("modalTotalValue").value = total;

    document.getElementById("checkoutModal").classList.remove("hidden");
    document.getElementById("checkoutModal").classList.add("flex");
}


function closeModal(){
    document.getElementById("checkoutModal").classList.add("hidden");
    document.getElementById("checkoutModal").classList.remove("flex");
}

document.addEventListener('DOMContentLoaded', () => {
    const bayarInput = document.getElementById('jumlahBayar');
    const kembalianBox = document.getElementById('kembalianBox');
    const kembalianText = document.getElementById('kembalianText');
    const totalInput = document.getElementById('modalTotalValue');
    const btnBayar = document.getElementById('btnBayar');


    function updateKembalian() {
        let total = parseInt(totalInput.value);
        let bayar = parseInt(bayarInput.value || 0);

        if (bayar >= total) {
            let kembali = bayar - total;
            kembalianText.textContent = "Rp " + kembali.toLocaleString('id-ID');
            kembalianBox.classList.remove('hidden');

            // AKTIFKAN tombol bayar
            btnBayar.disabled = false;
            btnBayar.classList.remove("opacity-50", "cursor-not-allowed");

        } else {
            kembalianBox.classList.add('hidden');

            // NONAKTIFKAN tombol bayar
            btnBayar.disabled = true;
            btnBayar.classList.add("opacity-50", "cursor-not-allowed");
        }
    }



    bayarInput.addEventListener('input', updateKembalian);

    document.querySelectorAll('.quick-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            let val = btn.dataset.value;

            if (val === "pas") {
                bayarInput.value = totalInput.value;
            } else {
                bayarInput.value = parseInt(val);
            }

            updateKembalian();
        });
    });
});

</script>

<script>
document.getElementById('searchInput').addEventListener('input', function () {
    let q = this.value.toLowerCase();
    let cards = document.querySelectorAll('.product-card');

    cards.forEach(card => {
        let name = card.dataset.name;
        let cat = card.dataset.category;

        // cocok ke nama ATAU kategori
        if (name.includes(q) || cat.includes(q)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>




</x-app-layout>
