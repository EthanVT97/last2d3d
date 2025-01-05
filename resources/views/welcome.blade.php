<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>မြန်မာ 2D/3D လော့ထရီ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="text-2xl font-bold text-blue-600">
                    <i class="fas fa-ticket-alt mr-2"></i>MM Lottery
                </div>
                <div class="space-x-4">
                    <a href="/login" class="text-gray-600 hover:text-blue-600">
                        <i class="fas fa-sign-in-alt mr-1"></i>လော့အင်ဝင်ရန်
                    </a>
                    <a href="/register" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-user-plus mr-1"></i>အကောင့်ဖွင့်ရန်
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
        <div class="container mx-auto px-4 py-20">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-5xl font-bold mb-6">မြန်မာ 2D/3D၊ ထိုင်း၊ လာအို လော့ထရီများ</h1>
                <p class="text-xl mb-8 text-blue-100">အွန်လိုင်းမှတစ်ဆင့် လွယ်ကူစွာ ထိုးနိုင်ပါပြီ</p>
                <div class="space-x-4">
                    <a href="/register" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        စတင်ရန် <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="/help" class="bg-transparent border-2 border-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">
                        အသုံးပြုနည်း <i class="fas fa-question-circle ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Available Lotteries Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">ရရှိနိုင်သော လော့ထရီများ</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- 2D Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
                    <div class="text-blue-600 text-4xl mb-4"><i class="fas fa-dice-two"></i></div>
                    <h3 class="text-xl font-bold mb-2">မြန်မာ 2D</h3>
                    <p class="text-gray-600 mb-4">နေ့စဉ် မွန်းတည့် ၁၂:၀၁ နာရီ နှင့် ညနေ ၄:၃၀ နာရီ</p>
                    <a href="/play/2d" class="block text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-play mr-2"></i>ထိုးရန်
                    </a>
                </div>

                <!-- 3D Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
                    <div class="text-blue-600 text-4xl mb-4"><i class="fas fa-dice-three"></i></div>
                    <h3 class="text-xl font-bold mb-2">မြန်မာ 3D</h3>
                    <p class="text-gray-600 mb-4">လစဉ် ၁ရက်၊ ၁၆ရက်</p>
                    <a href="/play/3d" class="block text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-play mr-2"></i>ထိုးရန်
                    </a>
                </div>

                <!-- Thai Lottery Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
                    <div class="text-blue-600 text-4xl mb-4"><i class="fas fa-ticket-alt"></i></div>
                    <h3 class="text-xl font-bold mb-2">ထိုင်း လော့ထရီ</h3>
                    <p class="text-gray-600 mb-4">လစဉ် ၁ရက်၊ ၁၆ရက်</p>
                    <a href="/play/thai" class="block text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-play mr-2"></i>ထိုးရန်
                    </a>
                </div>

                <!-- Laos Lottery Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
                    <div class="text-blue-600 text-4xl mb-4"><i class="fas fa-ticket-alt"></i></div>
                    <h3 class="text-xl font-bold mb-2">လာအို လော့ထရီ</h3>
                    <p class="text-gray-600 mb-4">လစဉ် ၁ရက်၊ ၁၆ရက်</p>
                    <a href="/play/laos" class="block text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-play mr-2"></i>ထိုးရန်
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">အားသာချက်များ</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="text-blue-600 text-3xl mb-4"><i class="fas fa-shield-alt"></i></div>
                    <h3 class="text-xl font-bold mb-2">လုံခြုံစိတ်ချရ</h3>
                    <p class="text-gray-600">သင့်ငွေကို စိတ်ချစွာ ထိုးနိုင်ပါသည်</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="text-blue-600 text-3xl mb-4"><i class="fas fa-bolt"></i></div>
                    <h3 class="text-xl font-bold mb-2">မြန်ဆန်</h3>
                    <p class="text-gray-600">ချက်ချင်း ထိုးနိုင်၊ ချက်ချင်း ထုတ်ယူနိုင်</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="text-blue-600 text-3xl mb-4"><i class="fas fa-headset"></i></div>
                    <h3 class="text-xl font-bold mb-2">၂၄နာရီ ဝန်ဆောင်မှု</h3>
                    <p class="text-gray-600">မည်သည့်အချိန်တွင်မဆို ဆက်သွယ်နိုင်ပါသည်</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-lg font-bold mb-4">ဆက်သွယ်ရန်</h4>
                    <ul class="space-y-2">
                        <li><i class="fas fa-phone mr-2"></i>09-123456789</li>
                        <li><i class="fas fa-envelope mr-2"></i>info@mmlottery.com</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>ရန်ကုန်မြို့</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">အကူအညီ</h4>
                    <ul class="space-y-2">
                        <li><a href="/help" class="hover:text-blue-400">အသုံးပြုနည်း</a></li>
                        <li><a href="/terms" class="hover:text-blue-400">စည်းမျဉ်းစည်းကမ်းများ</a></li>
                        <li><a href="/faq" class="hover:text-blue-400">မေးလေ့ရှိသောမေးခွန်းများ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Social Media</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-blue-400"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="hover:text-blue-400"><i class="fab fa-viber fa-2x"></i></a>
                        <a href="#" class="hover:text-blue-400"><i class="fab fa-telegram fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center">
                <p>&copy; 2025 MM Lottery. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
