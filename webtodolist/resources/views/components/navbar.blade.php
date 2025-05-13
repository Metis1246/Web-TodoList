<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - P.Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');
            const buttons = document.getElementById('buttons');

            menuToggle.addEventListener('click', function() {
                navLinks.classList.toggle('hidden');
                buttons.classList.toggle('hidden');
            });
        });
    </script>
</head>

<body class="font-sans">
    <header class="w-full max-w-7xl mx-auto px-5 py-2 bg-white">
        <div class="w-full flex flex-wrap items-center justify-between relative">
            <!-- Logo -->
            <div class="flex items-center gap-4">
                <a href="/" class="inline-flex items-center">
                    <h1 class="text-4xl font-bold text-blue-400">PlanOnPoint</h1>
                </a>
                <!-- Menu Toggle for Mobile -->
                <button id="menu-toggle" class="md:hidden p-1 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Buttons -->
            <div id="buttons" class="hidden md:flex gap-3 w-full md:w-auto mt-6 md:mt-0">
                @guest
                    <a href="{{ route('login') }}"
                        class="w-full md:w-auto px-3 py-2 bg-blue-400 hover:bg-blue-500 text-white font-bold rounded-xl text-center shadow-lg">
                        เข้าสู่ระบบ
                    </a>
                    <a href="{{ route('register') }}"
                        class="w-full md:w-auto px-3 py-2 bg-gray-300 hover:bg-gray-400 text-white font-bold rounded-xl text-center shadow-lg">
                        สมัครสมาชิก
                    </a>
                @else
                    <div class="flex items-center gap-3">
                        <!-- ขยับ Username ขึ้น -->
                        <span class="text-blue-400 text-1xl font-bold relative -top-2">Username
                            {{ Auth::user()->username }}</span>

                        <!-- ปุ่ม logout คงตำแหน่งเดิม -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full md:w-auto px-3 py-2 bg-blue-400 hover:bg-red-500 text-white font-bold rounded-xl text-center shadow-lg">
                                ออกจากระบบ
                            </button>
                        </form>
                    </div>

                @endguest
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>
