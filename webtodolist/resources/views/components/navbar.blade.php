<!DOCTYPE html>
<html lang="th" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - P.Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        html,
        body {
            height: 100%;
        }

        #app {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrap {
            flex: 1 0 auto;
            padding-bottom: 4rem;
        }

        .footer {
            flex-shrink: 0;
        }


        @media (max-width: 767px) {
            #buttons {
                position: absolute;
                top: 100%;
                right: 0;
                background: white;
                padding: 1.5rem;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                z-index: 50;
                min-width: 200px;
                display: none;
                flex-direction: column;
            }

            #buttons.mobile-menu {
                display: flex;
            }

            #buttons a,
            #buttons>div {
                margin-bottom: 1.5rem;
                width: 100%;
                text-align: center;
            }

            #buttons a:last-child,
            #buttons>div:last-child {
                margin-bottom: 0;
            }

            #buttons .bg-blue-400 {
                margin-bottom: 1.5rem;
            }

            #buttons .bg-gray-300 {
                margin-bottom: 0;
            }

            #buttons .flex.items-center.gap-3 {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            #buttons .flex.items-center.gap-3 span {
                text-align: center;
                padding: 0.5rem 0;
                border-bottom: 1px solid #e5e7eb;
                margin-bottom: 1rem;
            }

            #buttons .flex.items-center.gap-3 form {
                width: 100%;
            }

            #buttons .flex.items-center.gap-3 button {
                width: 100%;
            }
        }
    </style>
</head>

<body class="font-sans flex flex-col min-h-screen">
    <div id="app">
        <header class="w-full max-w-7xl mx-auto px-5 py-2 bg-white relative">
            <div class="w-full flex flex-wrap items-center justify-between">

                <div class="flex items-center gap-4">
                    <a href="/" class="inline-flex items-center">
                        <h1 class="text-4xl font-bold text-blue-400">PlanOnPoint</h1>
                    </a>
                </div>


                <button id="menu-toggle" class="md:hidden p-1 focus:outline-none absolute right-5 top-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>


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
                            <span class="text-blue-400 text-1xl font-bold">Username
                                {{ Auth::user()->username }}</span>
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
        @if (session('success'))
            <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50">
                {{ session('success') }}
            </div>
        @endif
        <main class="content-wrap flex-grow">
            @yield('content')
        </main>

        <footer class="footer w-full bg-blue-400 p-4 text-white">
            <div class="max-w-7xl mx-auto text-center">
                <p class="font-bold">Made With Metis</p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const buttons = document.getElementById('buttons');

            menuToggle.addEventListener('click', function() {
                buttons.classList.toggle('mobile-menu');
                buttons.classList.toggle('hidden');
            });
        });
    </script>
</body>

</html>
