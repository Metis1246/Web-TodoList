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
                            <span id="postTypeText">โพสต์ทั้งหมด</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="postTypeDropdownMenu" class="hidden absolute z-10 mt-2 w-48 bg-white rounded-md shadow-lg">
                            <div class="py-1">
                                <a href="#" data-type="all"
                                    class="block px-4 py-2 text-sm text-blue-400 hover:bg-gray-100 post-type-option">โพสต์ทั้งหมด</a>
                                <a href="#" data-type="mine"
                                    class="block px-4 py-2 text-sm text-blue-400 hover:bg-gray-100 post-type-option">โพสต์ของฉัน</a>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown สถานะ -->
                    <div class="relative">
                        <button id="statusDropdownButton"
                            class="flex items-center text-xl font-bold text-blue-400 text-left font-kanit">
                            <span id="statusText">ทั้งหมด</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="statusDropdownMenu" class="hidden absolute z-10 mt-2 w-48 bg-white rounded-md shadow-lg">
                            <div class="py-1">
                                <a href="#" data-status="all"
                                    class="block px-4 py-2 text-sm text-blue-400 hover:bg-gray-100 status-option">ทั้งหมด</a>
                                <a href="#" data-status="กำลังดำเนินการ"
                                    class="block px-4 py-2 text-sm text-blue-400 hover:bg-gray-100 status-option">กำลังดำเนินการ</a>
                                <a href="#" data-status="ดำเนินการเสร็จแล้ว"
                                    class="block px-4 py-2 text-sm text-blue-400 hover:bg-gray-100 status-option">ดำเนินการเสร็จแล้ว</a>
                            </div>
                        </div>
                    </div>
                </div>

                @auth <!-- เพิ่มเงื่อนไขตรวจสอบการล็อกอิน -->
                    <button onclick="openModal()"
                        class="bg-blue-400 hover:bg-blue-600 text-white rounded-full p-2 w-10 h-10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                @endauth
            </div>
        </div>

        <!-- ส่วนแสดงรายการโพสต์ -->
        <div id="postsContainer" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($items as $item)
                <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300">
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

    @auth <!-- เพิ่มเงื่อนไขตรวจสอบการล็อกอิน -->
        <!-- Modal สำหรับเพิ่มโพสต์ใหม่ -->
        @include('components.post')
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ตัวแปรเก็บสถานะปัจจุบัน
        let currentPostType = 'all';
        let currentStatus = 'all';

        // ฟังก์ชันโหลดโพสต์ใหม่ตาม filter
        function loadFilteredPosts() {
            const params = new URLSearchParams();

            if (currentPostType !== 'all') {
                params.append('type', currentPostType);
            }

            if (currentStatus !== 'all') {
                params.append('status', currentStatus);
            }

            fetch(`/items/filter?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    updatePostsDisplay(data.items);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // ฟังก์ชันอัปเดตการแสดงผลโพสต์
        function updatePostsDisplay(items) {
            const postsContainer = document.getElementById('postsContainer');
            postsContainer.innerHTML = '';

            if (items.length === 0) {
                postsContainer.innerHTML =
                    '<div class="col-span-2 text-center py-8 text-gray-500">ไม่พบโพสต์ที่ตรงกับเงื่อนไข</div>';
                return;
            }

            items.forEach(item => {
                const postHtml = `
                    <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center mr-3 overflow-hidden">
                                ${item.user.profile_image ? 
                                    `<img src="${item.user.profile_image}" alt="${item.username}" class="w-full h-full object-cover">` : 
                                    `<span class="text-gray-500">${item.username.substring(0, 1)}</span>`}
                            </div>
                            <div>
                                <h3 class="font-bold">${item.username}</h3>
                                <div class="flex items-center text-sm text-gray-500">
                                    <p>${new Date(item.created_at).toLocaleDateString('th-TH')} ${new Date(item.created_at).toLocaleTimeString('th-TH')}</p>
                                    <span class="ml-2 px-3 py-1 rounded-full text-sm 
                                        ${item.status === 'กำลังดำเนินการ' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'}">
                                        ${item.status}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <h4 class="text-xl font-bold mb-2">${item.name}</h4>
                        <p class="text-gray-700 mb-3">${item.description}</p>
                        ${item.image_url ? 
                            `<div class="mb-3 h-48 flex items-center justify-center bg-gray-100 rounded-lg overflow-hidden">
                                                    <img src="${item.image_url}" alt="${item.name}" class="max-w-full max-h-full object-contain">
                                                </div>` : ''}
                    </div>
                `;
                postsContainer.innerHTML += postHtml;
            });
        }

        // Event listeners สำหรับ dropdown
        document.querySelectorAll('.post-type-option').forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                currentPostType = this.dataset.type;
                document.getElementById('postTypeText').textContent = this.textContent;
                document.getElementById('postTypeDropdownMenu').classList.add('hidden');
                loadFilteredPosts();
            });
        });

        document.querySelectorAll('.status-option').forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                currentStatus = this.dataset.status;
                document.getElementById('statusText').textContent = this.textContent;
                document.getElementById('statusDropdownMenu').classList.add('hidden');
                loadFilteredPosts();
            });
        });

        // ฟังก์ชันจัดการ Modal
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            document.getElementById('uploadForm').reset();
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
