@extends('components.navbar')

@section('title', 'หน้าหลัก')

@section('content')
    <div class="w-full max-w-7xl mx-auto p-4">
        <div class="rounded-lg p-4 bg-white">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center space-x-4">
                    <!-- First dropdown (โพสต์ทั้งหมด/โพสต์ของเรา) -->
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

                    <!-- Second dropdown (สถานะ) -->
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
    </div>

    @include('components.post')
    @include('components.footer')

    <script>
        // Dropdown functionality for post type
        const postTypeDropdownButton = document.getElementById('postTypeDropdownButton');
        const postTypeDropdownMenu = document.getElementById('postTypeDropdownMenu');

        postTypeDropdownButton.addEventListener('click', function(e) {
            e.stopPropagation();
            postTypeDropdownMenu.classList.toggle('hidden');
            statusDropdownMenu.classList.add('hidden');
        });

        // Dropdown functionality for status
        const statusDropdownButton = document.getElementById('statusDropdownButton');
        const statusDropdownMenu = document.getElementById('statusDropdownMenu');

        statusDropdownButton.addEventListener('click', function(e) {
            e.stopPropagation();
            statusDropdownMenu.classList.toggle('hidden');
            postTypeDropdownMenu.classList.add('hidden');
        });

        // Close dropdowns when clicking outside
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
</body>

</html>
