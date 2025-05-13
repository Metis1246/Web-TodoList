@extends('components.navbar')

@section('title', 'สมัครสมาชิก')

@section('content')
    <div class="flex items-center justify-center min-h-screen py-12 bg-gray-50">
        <div class="bg-white rounded-lg shadow-md p-8 w-full max-w-md shadow-t-lg">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-medium text-blue-400">สมัครสมาชิก</h1>
                <p class="text-gray-500">Register</p>
            </div>

            <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">ชื่อผู้ใช้</label>
                    <input type="text" id="username" name="username" placeholder="Username" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
                    <input type="email" id="email" name="email" placeholder="Email" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน</label>
                    <input type="password" id="password" name="password" placeholder="Password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-gray-700 mb-1">ยืนยันรหัสผ่าน</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="Confirm Password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <button type="submit"
                    class="w-full bg-blue-400 text-white py-2 px-4 rounded-md hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    สมัครสมาชิก
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-sm text-gray-600">
                    หากมีบัญชีอยู่แล้ว <a href="{{ route('login') }}" class="text-blue-400 hover:underline">เข้าสู่ระบบ</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสผ่านไม่ตรงกัน',
                    text: 'กรุณาตรวจสอบรหัสผ่านอีกครั้ง',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สมัครสมาชิกสำเร็จ!',
                            text: 'กำลังนำท่านไปยังหน้าเข้าสู่ระบบ',
                            confirmButtonColor: '#3085d6'
                        }).then(() => {
                            window.location.href = "{{ route('login') }}";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: data.message || 'กรุณาตรวจสอบข้อมูลอีกครั้ง',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาดในการเชื่อมต่อ',
                        text: 'กรุณาลองใหม่อีกครั้ง',
                        confirmButtonColor: '#3085d6'
                    });
                });
        });

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#3085d6'
            });
        @endif

        @if (session('status'))
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: '{{ session('status') }}',
                confirmButtonColor: '#3085d6'
            });
        @endif
    </script>
@endsection
