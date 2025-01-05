<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'မြန်မာ 2D/3D လော့ထရီ')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
    <style>
        .animated-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(45deg, #f3f4f6, #e5e7eb, #d1d5db);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gray-100">
    <div class="animated-background"></div>
    <div class="relative">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm fixed w-full top-0 z-50">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center h-16">
                    <a href="/" class="text-2xl font-bold text-gray-700 flex items-center">
                        <i class="fas fa-ticket-alt mr-2"></i>MM Lottery
                    </a>
                    
                    @auth
                        <div class="hidden md:flex items-center space-x-8">
                            <a href="{{ route('home') }}" class="flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                                <i class="fas fa-home mr-2"></i>
                                <span>ပင်မစာမျက်နှာ</span>
                            </a>
                            <a href="{{ route('lottery.2d') }}" class="flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                                <i class="fas fa-dice-two mr-2"></i>
                                <span>2D</span>
                            </a>
                            <a href="{{ route('lottery.3d') }}" class="flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                                <i class="fas fa-dice-three mr-2"></i>
                                <span>3D</span>
                            </a>
                            <a href="{{ route('lottery.thai') }}" class="flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                                <i class="fas fa-ticket-alt mr-2"></i>
                                <span>ထိုင်း</span>
                            </a>
                            <a href="{{ route('lottery.laos') }}" class="flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                                <i class="fas fa-ticket-alt mr-2"></i>
                                <span>လာအို</span>
                            </a>
                            @if(Auth::check())
                                <div class="flex items-center text-gray-600 bg-gray-50 px-4 py-2 rounded-lg">
                                    <i class="fas fa-wallet mr-2 text-gray-700"></i>
                                    <span>{{ number_format(Auth::user()->balance) }} ကျပ်</span>
                                </div>
                            @endif
                        </div>
                        <!-- Mobile Menu Button -->
                        <button class="md:hidden text-gray-600 hover:text-gray-800" id="mobile-menu-button">
                            <i class="fas fa-bars text-2xl"></i>
                        </button>
                        <!-- User Menu -->
                        <div class="hidden md:block relative group">
                            <button class="flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                                <i class="fas fa-user-circle mr-2"></i>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down ml-2"></i>
                            </button>
                            <div class="absolute right-0 w-48 py-2 mt-2 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-user mr-2"></i>ပရိုဖိုင်
                                </a>
                                <a href="{{ route('profile.referrals') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-users mr-2"></i>မိတ်ဆက်များ
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i>ထွက်မည်
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
                                အကောင့်ဝင်ရန်
                            </a>
                            <a href="{{ route('register') }}" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors">
                                အကောင့်ဖွင့်ရန်
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Mobile Menu -->
        <div class="fixed inset-0 bg-gray-800 bg-opacity-50 z-40 hidden" id="mobile-menu-overlay">
            <div class="fixed inset-y-0 right-0 max-w-xs w-full bg-white shadow-xl z-50 transform transition-transform duration-300 translate-x-full" id="mobile-menu">
                <div class="p-4">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-xl font-bold text-gray-800">Menu</h2>
                        <button class="text-gray-600 hover:text-gray-800" id="mobile-menu-close">
                            <i class="fas fa-times text-2xl"></i>
                        </button>
                    </div>
                    @auth
                        <div class="flex items-center mb-8 bg-gray-50 p-4 rounded-lg">
                            <i class="fas fa-wallet text-2xl text-gray-700 mr-4"></i>
                            <div>
                                <p class="text-sm text-gray-600">လက်ကျန်ငွေ</p>
                                <p class="text-lg font-bold text-gray-800">{{ number_format(Auth::user()->balance) }} ကျပ်</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-50 rounded-lg transition-colors">
                                <i class="fas fa-home mr-2"></i>ပင်မစာမျက်နှာ
                            </a>
                            <a href="{{ route('lottery.2d') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-50 rounded-lg transition-colors">
                                <i class="fas fa-dice-two mr-2"></i>2D
                            </a>
                            <a href="{{ route('lottery.3d') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-50 rounded-lg transition-colors">
                                <i class="fas fa-dice-three mr-2"></i>3D
                            </a>
                            <a href="{{ route('lottery.thai') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-50 rounded-lg transition-colors">
                                <i class="fas fa-ticket-alt mr-2"></i>ထိုင်း
                            </a>
                            <a href="{{ route('lottery.laos') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-50 rounded-lg transition-colors">
                                <i class="fas fa-ticket-alt mr-2"></i>လာအို
                            </a>
                            <div class="border-t border-gray-200 my-4"></div>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-50 rounded-lg transition-colors">
                                <i class="fas fa-user mr-2"></i>ပရိုဖိုင်
                            </a>
                            <a href="{{ route('profile.referrals') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-50 rounded-lg transition-colors">
                                <i class="fas fa-users mr-2"></i>မိတ်ဆက်များ
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-50 rounded-lg transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i>ထွက်မည်
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="space-y-4">
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-50 rounded-lg transition-colors">
                                <i class="fas fa-sign-in-alt mr-2"></i>အကောင့်ဝင်ရန်
                            </a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 bg-gray-700 text-white hover:bg-gray-800 rounded-lg transition-colors">
                                <i class="fas fa-user-plus mr-2"></i>အကောင့်ဖွင့်ရန်
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="pt-16">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-12">
            <div class="container mx-auto px-4 py-8">
                <div class="text-center">
                    <p>&copy; {{ date('Y') }} MM Lottery. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Mobile Menu Toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

        function openMobileMenu() {
            mobileMenu.classList.remove('translate-x-full');
            mobileMenuOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            mobileMenu.classList.add('translate-x-full');
            mobileMenuOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        mobileMenuButton.addEventListener('click', openMobileMenu);
        mobileMenuClose.addEventListener('click', closeMobileMenu);
        mobileMenuOverlay.addEventListener('click', (e) => {
            if (e.target === mobileMenuOverlay) {
                closeMobileMenu();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
