<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-10 bg-white border-b border-gray-200">

                    <div class="mb-6 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <button id="bulkDeleteBtn" onclick="submitBulkDelete()" disabled
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out hidden items-center opacity-50 cursor-not-allowed">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Hapus User (<span id="selectedCount">0</span>)
                            </button>
                        </div>
                        <button onclick="openCreateModal()"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah User
                        </button>
                    </div>

                    <form id="bulkDeleteForm" action="{{ route('users.bulk_destroy') }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                        <div id="bulkDeleteInputs"></div>
                    </form>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Berhasil!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Terjadi Kesalahan:</p>
                            <ul class="mt-2 list-disc ml-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        <input type="checkbox" id="selectAll"
                                            class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                    </th>
                                    <th scope="col" class="px-6 py-3">Nama</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Role</th>
                                    <th scope="col" class="px-6 py-3">Outlet</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" name="selected_users[]" value="{{ $user->id }}"
                                                class="user-checkbox w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                        </td>
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $user->name }}
                                        </th>
                                        <td class="px-6 py-4">{{ $user->email }}</td>
                                        <td class="px-6 py-4 capitalize">
                                            @if($user->role === 'admin')
                                                <span
                                                    class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">{{ $user->role }}</span>
                                            @else
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">{{ $user->role }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $user->outlet ? $user->outlet->name : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button
                                                onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ optional($user->outlet)->id }}')"
                                                class="text-blue-600 hover:text-blue-900 transition duration-150 ease-in-out mr-3">
                                                <span class="material-symbols-rounded text-lg">edit</span>
                                            </button>

                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Yakin hapus user ini?')"
                                                    class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out">
                                                    <span class="material-symbols-rounded text-lg">delete</span>
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
        </div>
    </div>


    <div id="createModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[999] flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg mx-auto my-auto rounded-xl shadow-2xl transition-all duration-300 transform scale-95 opacity-0 max-h-screen overflow-y-auto"
            id="createModalContent">
            <div class="p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-3">Tambah User Baru</h2>

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="create_name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" name="name" id="create_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                        </div>

                        <div>
                            <label for="create_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="create_email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="create_password"
                                    class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input type="password" name="password" id="create_password"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                    required>

                                <!-- BAR KEKUATAN PASSWORD -->
                                <div id="createStrengthBar" class="h-2 w-full bg-gray-300 rounded mt-2">
                                    <div id="createStrengthFill"
                                        class="h-2 bg-red-500 rounded w-0 transition-all duration-300"></div>
                                </div>
                            </div>

                            <div>
                                <label for="create_password_confirmation"
                                    class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="create_password_confirmation"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                    required>
                            </div>
                        </div>



                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="create_role"
                                    class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <select name="role" id="create_role"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                    required>
                                    <option value="admin">Admin</option>
                                    <option value="kasir">Kasir</option>
                                </select>
                            </div>
                            <div>
                                <label for="create_outlet_id"
                                    class="block text-sm font-medium text-gray-700 mb-1">Outlet</label>
                                <select name="outlet_id" id="create_outlet_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Tanpa Outlet</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8 pt-4 border-t">
                        <button type="button" onclick="closeCreateModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-150 ease-in-out">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition duration-150 ease-in-out">
                            Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="editModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[999] flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg mx-auto my-auto rounded-xl shadow-2xl transition-all duration-300 transform scale-95 opacity-0 max-h-screen overflow-y-auto"
            id="editModalContent">
            <div class="p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-3">Edit User</h2>

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" id="editId">

                    <div class="space-y-4">
                        <div>
                            <label for="editName" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" name="name" id="editName"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <div>
                            <label for="editEmail" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="editEmail"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_password" class="block text-sm font-medium text-gray-700 mb-1">Password
                                    Baru</label>
                                <input type="password" name="password" id="edit_password"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">

                                <!-- BAR KEKUATAN PASSWORD -->
                                <div id="editStrengthBar" class="h-2 w-full bg-gray-300 rounded mt-2">
                                    <div id="editStrengthFill"
                                        class="h-2 bg-red-500 rounded w-0 transition-all duration-300"></div>
                                </div>

                                <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin ganti password.
                                </p>
                            </div>

                            <div>
                                <label for="edit_password_confirmation"
                                    class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password
                                    Baru</label>
                                <input type="password" name="password_confirmation" id="edit_password_confirmation"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>



                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="editRole" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <select name="role" id="editRole"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                    <option value="admin">Admin</option>
                                    <option value="kasir">Kasir</option>
                                </select>
                            </div>
                            <div>
                                <label for="editOutlet"
                                    class="block text-sm font-medium text-gray-700 mb-1">Outlet</label>
                                <select name="outlet_id" id="editOutlet"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Tanpa Outlet</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8 pt-4 border-t">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition duration-150 ease-in-out">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition duration-150 ease-in-out">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        // Fungsionalitas Modal Create
        function openCreateModal() {
            const modal = document.getElementById('createModal');
            const content = document.getElementById('createModalContent');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Reset scroll position if previously scrolled
            content.scrollTop = 0;
            // Trigger animation
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        function closeCreateModal() {
            const modal = document.getElementById('createModal');
            const content = document.getElementById('createModalContent');
            // Trigger reverse animation
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300); // Wait for transition to finish
        }

        // Fungsionalitas Modal Edit
        function openEditModal(id, name, email, role, outlet_id) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;
            document.getElementById('editOutlet').value = outlet_id === 'null' ? '' : outlet_id;

            document.getElementById('editForm').action = '/users/' + id;

            const modal = document.getElementById('editModal');
            const content = document.getElementById('editModalContent');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Reset scroll position if previously scrolled
            content.scrollTop = 0;
            // Trigger animation
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        function closeEditModal() {
            const modal = document.getElementById('editModal');
            const content = document.getElementById('editModalContent');
            // Trigger reverse animation
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300); // Wait for transition to finish
        }

        // ===========================
        // VALIDASI FORM CREATE (CLIENT SIDE)
        // ===========================
        document.querySelector('form[action="{{ route('users.store') }}"]').addEventListener('submit', function (e) {
            const name = document.getElementById('create_name').value.trim();
            const email = document.getElementById('create_email').value.trim();
            const password = document.getElementById('create_password').value.trim();
            const passwordConfirm = document.getElementById('create_password_confirmation').value.trim();
            const role = document.getElementById('create_role').value.trim();

            if (!name || !email || !password || !passwordConfirm || !role) {
                e.preventDefault();
                alert("⚠ Semua field wajib diisi!");
                return false;
            }

            if (password !== passwordConfirm) {
                e.preventDefault();
                alert("⚠ Password dan Konfirmasi Password tidak sama!");
                return false;
            }
        });

        // ===========================
        // VALIDASI FORM EDIT (CLIENT SIDE)
        // ===========================
        document.getElementById('editForm').addEventListener('submit', function (e) {
            const name = document.getElementById('editName').value.trim();
            const email = document.getElementById('editEmail').value.trim();
            const role = document.getElementById('editRole').value.trim();

            if (!name || !email || !role) {
                e.preventDefault();
                alert("⚠ Nama, Email, dan Role wajib diisi!");
                return false;
            }

            const pass = document.getElementById('edit_password').value.trim();
            const passConfirm = document.getElementById('edit_password_confirmation').value.trim();

            if (pass !== "" && pass !== passConfirm) {
                e.preventDefault();
                alert("⚠ Password baru dan konfirmasi password tidak sama!");
                return false;
            }
        });

        function checkPasswordStrength(password, fillElement) {

            if (password.length === 0) {
                fillElement.style.width = "0";
                fillElement.style.backgroundColor = "transparent";
                return;
            }
            let strength = 0;

            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            switch (strength) {
                case 0:
                case 1:
                    fillElement.style.width = "20%";
                    fillElement.style.backgroundColor = "red";
                    break;
                case 2:
                    fillElement.style.width = "40%";
                    fillElement.style.backgroundColor = "orange";
                    break;
                case 3:
                    fillElement.style.width = "60%";
                    fillElement.style.backgroundColor = "yellow";
                    break;
                case 4:
                    fillElement.style.width = "80%";
                    fillElement.style.backgroundColor = "lightgreen";
                    break;
                case 5:
                    fillElement.style.width = "100%";
                    fillElement.style.backgroundColor = "green";
                    break;
            }
        }

        // Event listeners untuk password CREATE - multiple events untuk handle semua kasus
        const createPasswordInput = document.getElementById("create_password");
        const createStrengthFill = document.getElementById("createStrengthFill");

        if (createPasswordInput && createStrengthFill) {
            // Trigger saat mengetik
            createPasswordInput.addEventListener("input", function () {
                checkPasswordStrength(this.value, createStrengthFill);
            });

            // Trigger saat field mendapat focus (handle autofill & pindah kolom)
            createPasswordInput.addEventListener("focus", function () {
                checkPasswordStrength(this.value, createStrengthFill);
            });

            // Trigger saat field kehilangan focus
            createPasswordInput.addEventListener("blur", function () {
                checkPasswordStrength(this.value, createStrengthFill);
            });

            // Trigger saat ada perubahan (handle paste & autofill)
            createPasswordInput.addEventListener("change", function () {
                checkPasswordStrength(this.value, createStrengthFill);
            });
        }

        // Event listeners untuk password EDIT - multiple events untuk handle semua kasus
        const editPasswordInput = document.getElementById("edit_password");
        const editStrengthFill = document.getElementById("editStrengthFill");

        if (editPasswordInput && editStrengthFill) {
            // Trigger saat mengetik
            editPasswordInput.addEventListener("input", function () {
                checkPasswordStrength(this.value, editStrengthFill);
            });

            // Trigger saat field mendapat focus (handle autofill & pindah kolom)
            editPasswordInput.addEventListener("focus", function () {
                checkPasswordStrength(this.value, editStrengthFill);
            });

            // Trigger saat field kehilangan focus
            editPasswordInput.addEventListener("blur", function () {
                checkPasswordStrength(this.value, editStrengthFill);
            });

            // Trigger saat ada perubahan (handle paste & autofill)
            editPasswordInput.addEventListener("change", function () {
                checkPasswordStrength(this.value, editStrengthFill);
            });
        }

        // ===========================
        // BULK DELETE LOGIC
        // ===========================
        const selectAllCheckbox = document.getElementById('selectAll');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const selectedCountSpan = document.getElementById('selectedCount');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
        const bulkDeleteInputs = document.getElementById('bulkDeleteInputs');

        function updateBulkDeleteState() {
            const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
            const count = selectedCheckboxes.length;
            selectedCountSpan.textContent = count;

            if (count > 0) {
                bulkDeleteBtn.disabled = false;
                bulkDeleteBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'hidden');
                bulkDeleteBtn.classList.add('flex');
            } else {
                bulkDeleteBtn.disabled = true;
                bulkDeleteBtn.classList.add('opacity-50', 'cursor-not-allowed', 'hidden');
                bulkDeleteBtn.classList.remove('flex');
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkDeleteState();
            });
        }

        userCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const allChecked = Array.from(userCheckboxes).every(c => c.checked);
                if (selectAllCheckbox) selectAllCheckbox.checked = allChecked;
                updateBulkDeleteState();
            });
        });

        function submitBulkDelete() {
            const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
            if (selectedCheckboxes.length === 0) return;

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan menghapus " + selectedCheckboxes.length + " user yang dipilih!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkDeleteInputs.innerHTML = ''; // Clear previous inputs
                    selectedCheckboxes.forEach(checkbox => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = checkbox.value;
                        bulkDeleteInputs.appendChild(input);
                    });
                    bulkDeleteForm.submit();
                }
            });
        }

    </script>

</x-app-layout>