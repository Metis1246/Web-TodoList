@extends('components.navbar')

@section('title', 'รายละเอียดโพสต์')

@section('content')
    <div class="w-full max-w-4xl mx-auto p-4">
        <div class="mt-6 pb-5">
            <a href="/" class="inline-flex items-center text-blue-400 hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                กลับไปยังหน้าก่อนหน้า
            </a>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
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
                            <p>{{ $item->updated_at->timezone('Asia/Bangkok')->format('d/m/Y H:i') }}</p>
                            <span
                                class="ml-3 px-3 py-1 rounded-full text-sm 
                                {{ $item->status === 'กำลังดำเนินการ' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ $item->status }}
                            </span>
                        </div>
                    </div>
                </div>

                @auth
                    @if ($isOwner)
                        <div class="relative">
                            <button id="post-menu-button" class="p-2 rounded-full hover:bg-gray-100 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                </svg>
                            </button>
                            <div id="post-menu"
                                class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border border-gray-200">
                                <div class="py-1">
                                    <a href="#" onclick="openEditModal()"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">แก้ไขโพสต์</a>
                                    <form id="delete-post-form-{{ $item->id }}"
                                        action="{{ route('posts.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $item->id }})"
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 hover:text-red-800">
                                            ลบโพสต์
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>


            <h2 class="text-2xl font-bold mb-4">{{ $item->name }}</h2>
            <p class="text-gray-700 mb-6 whitespace-pre-line">{{ $item->description }}</p>


            @if ($item->image_url)
                <div class="mb-6 flex items-center justify-center bg-gray-100 rounded-lg overflow-hidden">
                    <img src="{{ str_replace('//', '/', $item->image_url) }}" alt="{{ $item->name }}"
                        class="w-full max-h-[70vh] object-contain"
                        onerror="this.onerror=null;this.src='https://via.placeholder.com/300';">
                </div>
            @endif


            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">ความคิดเห็น ({{ $item->comments->count() }})</h3>


                @auth
                    <div class="mb-6 bg-gray-50 rounded-lg p-4">
                        <form action="{{ route('items.comments.store', $item) }}" method="POST" enctype="multipart/form-data"
                            class="space-y-3">
                            @csrf
                            <textarea name="content" rows="3"
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="เขียนความคิดเห็น..."></textarea>

                            <div class="flex items-center justify-between">
                                <div>
                                    <input type="file" name="image" id="comment-image" class="hidden" accept="image/*">
                                    <label for="comment-image"
                                        class="cursor-pointer text-gray-500 hover:text-blue-500 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        เพิ่มรูปภาพ
                                    </label>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                    โพสต์ความคิดเห็น
                                </button>
                            </div>
                        </form>
                    </div>
                @endauth


                <div class="space-y-4">
                    @foreach ($item->comments as $comment)
                        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                        @if ($item->user->profile_image)
                                            <img src="{{ $item->user->profile_image }}" alt="{{ $item->user->username }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <span
                                                class="text-gray-500 text-xl">{{ substr($item->user->username, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-medium">{{ $comment->user->username }}</h4>
                                        <p class="text-xs text-gray-500">
                                            {{ $comment->created_at->timezone('Asia/Bangkok')->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>

                                @if (auth()->id() === $comment->user_id)
                                    <div class="relative">
                                        <button class="comment-menu-button p-1 rounded-full hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                        <div
                                            class="comment-menu hidden absolute right-0 mt-1 w-32 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                            <button
                                                onclick="openEditCommentModal({{ $comment->id }}, '{{ $comment->content }}')"
                                                class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                แก้ไข
                                            </button>
                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                                                class="w-full">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="block w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-gray-100"
                                                    onclick="return confirm('แน่ใจว่าต้องการลบความคิดเห็นนี้?')">
                                                    ลบ
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-3 pl-13">
                                <p class="text-gray-700 whitespace-pre-line">{{ $comment->content }}</p>

                                @if ($comment->image_url)
                                    <div class="mt-3">
                                        <img src="{{ $comment->image_url }}" alt="Comment image"
                                            class="max-h-60 rounded-lg">
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @auth
        @if ($isOwner)
            <div id="edit-modal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
                <div class="bg-white rounded-lg p-6 w-full max-w-md">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">แก้ไขโพสต์</h3>
                        <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('posts.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">ชื่อโพสต์</label>
                            <input type="text" name="name" id="name" value="{{ $item->name }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">รายละเอียด</label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ $item->description }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">สถานะ</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="กำลังดำเนินการ" {{ $item->status === 'กำลังดำเนินการ' ? 'selected' : '' }}>
                                    กำลังดำเนินการ</option>
                                <option value="ดำเนินการเสร็จแล้ว"
                                    {{ $item->status === 'ดำเนินการเสร็จแล้ว' ? 'selected' : '' }}>ดำเนินการเสร็จแล้ว</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">รูปภาพ
                                (ถ้าไม่ต้องการเปลี่ยนรูป ให้เว้นว่างไว้)</label>
                            <input type="file" name="image" id="image"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>

                        @if ($item->image_url)
                            <div class="mb-4">
                                <p class="text-sm text-gray-500">รูปภาพปัจจุบัน:</p>
                                <img src="{{ $item->image_url }}" alt="Current Image" class="mt-2 h-32 object-contain">
                            </div>
                        @endif

                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeEditModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                ยกเลิก
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                บันทึกการเปลี่ยนแปลง
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif


        <div id="edit-comment-modal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">แก้ไขความคิดเห็น</h3>
                    <button onclick="closeEditCommentModal()" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="edit-comment-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <textarea name="content" rows="3"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 mb-3"></textarea>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">รูปภาพ (ถ้าไม่ต้องการเปลี่ยนรูป
                            ให้เว้นว่างไว้)</label>
                        <input type="file" name="image"
                            class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100">
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditCommentModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            ยกเลิก
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            บันทึกการเปลี่ยนแปลง
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('post-menu-button');
            if (menuButton) {
                menuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const menu = document.getElementById('post-menu');
                    menu.classList.toggle('hidden');
                });
            }

            document.addEventListener('click', function() {
                const menu = document.getElementById('post-menu');
                if (menu && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                }
            });
        });

        function openEditModal() {
            document.getElementById('edit-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function confirmDelete(itemId) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "การลบโพสต์นี้ไม่สามารถย้อนกลับได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ลบโพสต์',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/posts/${itemId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (response.redirected) {
                                window.location.href = response.url;
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถลบโพสต์ได้', 'error');
                        });
                }
            });
        }


        document.querySelectorAll('.comment-menu-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const menu = this.nextElementSibling;
                document.querySelectorAll('.comment-menu').forEach(m => {
                    if (m !== menu) m.classList.add('hidden');
                });
                menu.classList.toggle('hidden');
            });
        });


        document.addEventListener('click', function() {
            document.querySelectorAll('.comment-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        });


        function openEditCommentModal(commentId, content) {
            const form = document.getElementById('edit-comment-form');
            form.action = `/comments/${commentId}`;
            form.querySelector('textarea').value = content;
            document.getElementById('edit-comment-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeEditCommentModal() {
            document.getElementById('edit-comment-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    </script>
@endsection
