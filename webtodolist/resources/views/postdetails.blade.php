@extends('components.navbar')

@section('title', 'รายละเอียดโพสต์')

@section('content')
    <div class="w-full max-w-7xl mx-auto p-4">
        <div class="rounded-lg p-4 bg-white">
            <div class="flex justify-between items-center mb-4">
                <a href="{{ url('/') }}" class="text-blue-400 hover:text-blue-600 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="ml-2">กลับหน้าหลัก</span>
                </a>

                @if ($currentUserId == $item->user_id)
                    <div class="flex space-x-2">
                        <button onclick="openEditModal({{ $item->id }})"
                            class="bg-yellow-400 hover:bg-yellow-600 text-white rounded-full p-2 w-10 h-10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                        </button>
                        <button onclick="confirmDelete({{ $item->id }})"
                            class="bg-red-400 hover:bg-red-600 text-white rounded-full p-2 w-10 h-10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>

            <!-- ส่วนแสดงรายละเอียดโพสต์ -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center mr-4 overflow-hidden">
                        @if ($item->user->profile_image)
                            <img src="{{ $item->user->profile_image }}" alt="{{ $item->user->username }}"
                                class="w-full h-full object-cover">
                        @else
                            <span class="text-gray-500 text-xl">{{ substr($item->user->username, 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">{{ $item->user->username }}</h3>
                        <div class="flex items-center text-sm text-gray-500">
                            <p>{{ $item->created_at->format('d/m/Y H:i') }}</p>
                            <span
                                class="ml-3 px-3 py-1 rounded-full text-sm 
                                {{ $item->status === 'กำลังดำเนินการ' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ $item->status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- เนื้อหาโพสต์ -->
                <h4 class="text-2xl font-bold mb-4">{{ $item->name }}</h4>
                <p class="text-gray-700 mb-6 whitespace-pre-line">{{ $item->description }}</p>

                <!-- รูปภาพโพสต์ -->
                @if ($item->image_url)
                    <div class="mb-6 h-96 flex items-center justify-center bg-gray-100 rounded-lg overflow-hidden">
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                            class="max-w-full max-h-full object-contain">
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal สำหรับแก้ไขโพสต์ -->
    @include('components.editpost')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ฟังก์ชันเปิด Modal แก้ไข
        function openEditModal(itemId) {
            document.getElementById('editModal').classList.remove('hidden');
        }

        // ฟังก์ชันปิด Modal แก้ไข
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // ฟังก์ชันยืนยันการลบ
        function confirmDelete(itemId) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณจะไม่สามารถกู้คืนโพสต์นี้กลับมาได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบโพสต์!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ส่งคำขอลบไปยังเซิร์ฟเวอร์
                    fetch(`/items/${itemId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                return response.json();
                            }
                            throw new Error('Network response was not ok.');
                        })
                        .then(data => {
                            Swal.fire(
                                'ลบแล้ว!',
                                'โพสต์ของคุณถูกลบแล้ว',
                                'success'
                            ).then(() => {
                                window.location.href = '/';
                            });
                        })
                        .catch(error => {
                            Swal.fire(
                                'เกิดข้อผิดพลาด!',
                                'ไม่สามารถลบโพสต์ได้',
                                'error'
                            );
                        });
                }
            });
        }
    </script>
@endsection
