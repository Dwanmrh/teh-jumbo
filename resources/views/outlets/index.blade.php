<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Outlet') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="outletForm()" x-cloak>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- BUTTON TAMBAH -->
                    <div class="mb-4">
                        <button @click="openCreate()"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Outlet
                        </button>
                    </div>

                    <!-- ALERT -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- TABLE -->
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($outlets as $outlet)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $outlet->name }}</td>
                                    <td class="px-6 py-4">{{ $outlet->address }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $outlet->phone }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                        <!-- EDIT (MODAL) -->
                                        <button @click="openEdit({ id: {{ $outlet->id }}, name: '{{ $outlet->name }}', address: '{{ $outlet->address }}', phone: '{{ $outlet->phone }}' })"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </button>

                                        <!-- DELETE -->
                                        <form action="{{ route('outlets.destroy', $outlet->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Apakah Anda yakin?')" class="text-red-600 hover:text-red-900">
                                                Hapus
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <!-- MODAL -->
        <div x-show="show"
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6">

                <h2 class="text-xl font-bold mb-4" x-text="isEdit ? 'Edit Outlet' : 'Tambah Outlet'"></h2>

                <form @submit.prevent="submitForm">
                    <div class="mb-3">
                        <label class="font-semibold">Nama</label>
                        <input type="text" class="w-full border rounded p-2" x-model="form.name">
                    </div>

                    <div class="mb-3">
                        <label class="font-semibold">Alamat</label>
                        <textarea class="w-full border rounded p-2" x-model="form.address"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="font-semibold">Telepon</label>
                        <input type="text" class="w-full border rounded p-2" x-model="form.phone">
                    </div>

                    <div class="flex justify-end space-x-3 mt-4">
                        <button type="button" @click="show = false"
                            class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                            Batal
                        </button>

                        <button type="submit"
                            class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

    <!-- SCRIPT ALPINE -->
    <script>
        function outletForm() {
            return {
                show: false,
                isEdit: false,
                form: { id: null, name: '', address: '', phone: '' },

                openCreate() {
                    this.isEdit = false;
                    this.form = { id: null, name: '', address: '', phone: '' };
                    this.show = true;
                },

                openEdit(outlet) {
                    this.isEdit = true;
                    this.form = outlet;
                    this.show = true;
                },

                async submitForm() {
                    let url = this.isEdit ? `/outlets/${this.form.id}` : `/outlets`;
                    let method = this.isEdit ? "PUT" : "POST";

                    let res = await fetch(url, {
                        method: method,
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.form)
                    });

                    if (res.ok) {
                        window.location.reload();
                    } else {
                        alert("Gagal menyimpan data");
                    }
                }
            }
        }
    </script>

</x-app-layout>
