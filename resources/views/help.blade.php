@extends('layouts.app')

@section('title', 'အကူအညီ')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">အကူအညီ</h1>

    <div class="space-y-8">
        <!-- Getting Started -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">စတင်အသုံးပြုခြင်း</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="font-bold mb-2">၁။ အကောင့်ဖွင့်ခြင်း</h3>
                    <p class="text-gray-600">
                        - "အကောင့်ဖွင့်ရန်" ခလုတ်ကို နှိပ်ပါ<br>
                        - လိုအပ်သော အချက်အလက်များဖြည့်သွင်းပါ<br>
                        - "အကောင့်ဖွင့်မည်" ကို နှိပ်ပါ
                    </p>
                </div>
                <div>
                    <h3 class="font-bold mb-2">၂။ ငွေသွင်းခြင်း</h3>
                    <p class="text-gray-600">
                        - Dashboard မှ "ငွေသွင်းရန်" ခလုတ်ကို နှိပ်ပါ<br>
                        - သင့်လျော်သော ငွေပေးချေမှုနည်းလမ်းကို ရွေးချယ်ပါ<br>
                        - ညွှန်ကြားချက်အတိုင်း ငွေလွှဲပါ
                    </p>
                </div>
            </div>
        </div>

        <!-- How to Play -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">လော့ထရီထိုးနည်း</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="font-bold mb-2">မြန်မာ 2D</h3>
                    <p class="text-gray-600">
                        - နှစ်လုံးထီ ထိုးရန် နံပါတ် ၀၀ မှ ၉၉ အထိ ရွေးချယ်နိုင်ပါသည်<br>
                        - နေ့စဉ် မွန်းတည့် ၁၂:၀၁ နာရီ နှင့် ညနေ ၄:၃၀ နာရီတွင် ၂ကြိမ် ပေါက်ဂဏန်းထုတ်ပါသည်<br>
                        - အနိုင်ရရှိပါက ထိုးငွေ၏ ၈၅ ဆ ပြန်လည်ရရှိမည်
                    </p>
                </div>
                <div>
                    <h3 class="font-bold mb-2">မြန်မာ 3D</h3>
                    <p class="text-gray-600">
                        - သုံးလုံးထီ ထိုးရန် နံပါတ် ၀၀၀ မှ ၉၉၉ အထိ ရွေးချယ်နိုင်ပါသည်<br>
                        - လစဉ် ၁ရက်နေ့ နှင့် ၁၆ရက်နေ့တွင် ပေါက်ဂဏန်းထုတ်ပါသည်<br>
                        - အနိုင်ရရှိပါက ထိုးငွေ၏ ၅၀၀ ဆ ပြန်လည်ရရှိမည်
                    </p>
                </div>
                <div>
                    <h3 class="font-bold mb-2">ထိုင်း လော့ထရီ</h3>
                    <p class="text-gray-600">
                        - ခြောက်လုံးထီ ထိုးရန် နံပါတ် ၀၀၀၀၀၀ မှ ၉၉၉၉၉၉ အထိ ရွေးချယ်နိုင်ပါသည်<br>
                        - လစဉ် ၁ရက်နေ့ နှင့် ၁၆ရက်နေ့တွင် ပေါက်ဂဏန်းထုတ်ပါသည်<br>
                        - First Prize ပေါက်ပါက ထိုးငွေ၏ ၁၀၀၀ ဆ ပြန်လည်ရရှိမည်
                    </p>
                </div>
                <div>
                    <h3 class="font-bold mb-2">လာအို လော့ထရီ</h3>
                    <p class="text-gray-600">
                        - လေးလုံးထီ ထိုးရန် နံပါတ် ၀၀၀၀ မှ ၉၉၉၉ အထိ ရွေးချယ်နိုင်ပါသည်<br>
                        - အပတ်စဉ် တနင်္လာ၊ ဗုဒ္ဓဟူး၊ သောကြာနေ့များတွင် ပေါက်ဂဏန်းထုတ်ပါသည်<br>
                        - First Prize ပေါက်ပါက ထိုးငွေ၏ ၈၀၀ ဆ ပြန်လည်ရရှိမည်
                    </p>
                </div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">အမေးများသော မေးခွန်းများ</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="font-bold mb-2">ငွေသွင်း/ထုတ်ရန် မည်သည့်နည်းလမ်းများ အသုံးပြုနိုင်ပါသလဲ?</h3>
                    <p class="text-gray-600">
                        KBZ Pay, Wave Pay, CB Pay တို့ဖြင့် ငွေသွင်း/ထုတ် ပြုလုပ်နိုင်ပါသည်။
                    </p>
                </div>
                <div>
                    <h3 class="font-bold mb-2">အနိုင်ရပါက မည်သို့ထုတ်ယူနိုင်ပါသလဲ?</h3>
                    <p class="text-gray-600">
                        Dashboard မှတဆင့် "ငွေထုတ်ရန်" ကို နှိပ်၍ သင့်လျော်သော ငွေပေးချေမှုနည်းလမ်းဖြင့် ထုတ်ယူနိုင်ပါသည်။
                    </p>
                </div>
                <div>
                    <h3 class="font-bold mb-2">အနိုင်ရငွေကို မည်သည့်အချိန်တွင် ရရှိမည်လဲ?</h3>
                    <p class="text-gray-600">
                        ပေါက်ဂဏန်းထွက်ပြီး မိနစ် ၃၀ အတွင်း သင့်အကောင့်သို့ အလိုအလျောက် ထည့်သွင်းပေးမည်ဖြစ်ပါသည်။
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">ဆက်သွယ်ရန်</h2>
            <div class="space-y-2">
                <p><strong>ဖုန်း:</strong> 09-XXXXXXXXX</p>
                <p><strong>အီးမေးလ်:</strong> support@example.com</p>
                <p><strong>Facebook:</strong> facebook.com/example</p>
                <p><strong>Viber:</strong> viber.me/example</p>
            </div>
        </div>
    </div>
</div>
@endsection
