@extends('components.navbar')

@section('title', 'หน้าหลัก')

@section('content')
    <div class="w-full max-w-7xl mx-auto p-4">
        <div class="rounded-lg p-4 bg-white">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center space-x-4">
                    <!-- Dropdown ประเภทโพสต์ -->
                    <div class="relative">
                        <button id="postTypeDropdownButton"
                            class="flex items-center text-xl font-bold text-blue-400 text-left font-kanit">
                            โพสต์ทั้งหมด
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <div id="postTypeDropdownMenu" class="hidden absolute z-10 mt-2 w-48 bg-white rounded-md shadow-lg">
                            <div class="py-1">
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-blue-400 hover:bg-gray-100">โพสต์ทั้งหมด</a>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-blue-400 hover:bg-gray-100">โพสต์ของเรา</a>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown สถานะ -->
                    <div class="relative">
                        <button id="statusDropdownButton"
                            class="flex items-center text-xl font-bold text-blue-400 text-left font-kanit">
                            ทั้งหมด
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <div id="statusDropdownMenu" class="hidden absolute z-10 mt-2 w-48 bg-white rounded-md shadow-lg">
                            <div class="py-1">
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-blue-400 hover:bg-gray-100">ทั้งหมด</a>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-blue-400 hover:bg-gray-100">กำลังดำเนินการ</a>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-blue-400 hover:bg-gray-100">ดำเนินการเสร็จแล้ว</a>
                            </div>
                        </div>
                    </div>
                </div>

                <button onclick="openModal()"
                    class="bg-blue-400 hover:bg-blue-600 text-white rounded-full p-2 w-10 h-10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- ส่วนแสดงรายการโพสต์ -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($items as $item)
                <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300 relative">
                    <!-- ปุ่มจัดการโพสต์ (แสดงเฉพาะเจ้าของ) -->
                    @if (auth()->check() && auth()->user()->user_id == $item->user_id)
                        <div class="absolute top-2 right-2 flex space-x-2">
                            <button onclick="openEditModal('{{ $item->id }}')"
                                class="p-1 bg-blue-400 hover:bg-blue-500 text-white rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button onclick="confirmDelete('{{ $item->id }}')"
                                class="p-1 bg-red-400 hover:bg-red-500 text-white rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    <!-- ส่วนหัวโพสต์ -->
                    <div class="flex items-center mb-3">
                        <div
                            class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center mr-3 overflow-hidden">
                            @if ($item->user->profile_image)
                                <img src="{{ $item->user->profile_image }}" alt="{{ $item->username }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="text-gray-500">{{ substr($item->username, 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold">{{ $item->username }}</h3>
                            <div class="flex items-center text-sm text-gray-500">
                                <p>{{ $item->created_at->format('d/m/Y H:i') }}</p>
                                <span
                                    class="ml-2 px-3 py-1 rounded-full text-sm 
                                    {{ $item->status === 'กำลังดำเนินการ' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $item->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- เนื้อหาโพสต์ -->
                    <h4 class="text-xl font-bold mb-2">{{ $item->name }}</h4>
                    <p class="text-gray-700 mb-3">{{ $item->description }}</p>

                    <!-- รูปภาพโพสต์ -->
                    @if ($item->image_url)
                        <div class="mb-3 h-48 flex items-center justify-center bg-gray-100 rounded-lg overflow-hidden">
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                class="max-w-full max-h-full object-contain">
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal สำหรับเพิ่มโพสต์ใหม่ -->
    @include('components.post')

    <!-- Modal สำหรับแก้ไขโพสต์ -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-blue-400">แก้ไขโพสต์</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_item_id" name="id">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">ชื่อรายการ</label>
                    <input type="text" name="name" id="edit_name"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">รายละเอียด</label>
                    <textarea name="description" id="edit_description"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400" rows="3"
                        required></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-400 hover:bg-gray-500 rounded-lg mr-2 text-white transition-colors duration-300">ยกเลิก</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-400 hover:bg-blue-600 text-white rounded-lg transition-colors duration-300">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ฟังก์ชันจัดการ Modal
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            document.getElementById('uploadForm').reset();
        }

        function openEditModal(itemId) {
            // ดึงข้อมูลโพสต์
            fetch(`/items/${itemId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_item_id').value = data.id;
                    document.getElementById('edit_name').value = data.name;
                    document.getElementById('edit_description').value = data.description;
                    document.getElementById('editModal').classList.remove('hidden');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // ส่งฟอร์มแก้ไข
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const itemId = document.getElementById('edit_item_id').value;
            const formData = new FormData(this);

            fetch(`/items/${itemId}`, {
                    method: 'POST',
                    body: new URLSearchParams(formData),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: 'แก้ไขโพสต์เรียบร้อยแล้ว',
                            confirmButtonText: 'ตกลง'
                        }).then(() => {
                            closeEditModal();
                            window.location.reload();
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถแก้ไขโพสต์ได้'
                    });
                });
        });

        // ยืนยันการลบ
        function confirmDelete(itemId) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "คุณจะไม่สามารถกู้คืนโพสต์นี้ได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(itemId);
                }
            });
        }

        function deleteItem(itemId) {
            fetch(`/items/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('ลบแล้ว!', 'โพสต์ของคุณถูกลบแล้ว', 'success');
                        window.location.reload();
                    }
                })
                .catch(error => {
                    Swal.fire('ผิดพลาด!', 'เกิดข้อผิดพลาดในการลบโพสต์', 'error');
                });
        }

        // Dropdown menus
        const postTypeDropdownButton = document.getElementById('postTypeDropdownButton');
        const postTypeDropdownMenu = document.getElementById('postTypeDropdownMenu');
        const statusDropdownButton = document.getElementById('statusDropdownButton');
        const statusDropdownMenu = document.getElementById('statusDropdownMenu');

        postTypeDropdownButton.addEventListener('click', function(e) {
            e.stopPropagation();
            postTypeDropdownMenu.classList.toggle('hidden');
            statusDropdownMenu.classList.add('hidden');
        });

        statusDropdownButton.addEventListener('click', function(e) {
            e.stopPropagation();
            statusDropdownMenu.classList.toggle('hidden');
            postTypeDropdownMenu.classList.add('hidden');
        });

        document.addEventListener('click', function(event) {
            if (!postTypeDropdownButton.contains(event.target) && !postTypeDropdownMenu.contains(event.target)) {
                postTypeDropdownMenu.classList.add('hidden');
            }
            if (!statusDropdownButton.contains(event.target) && !statusDropdownMenu.contains(event.target)) {
                statusDropdownMenu.classList.add('hidden');
            }
        });
    </script>
@endsection
