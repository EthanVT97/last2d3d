@extends('layouts.app')

@section('title', 'စည်းကမ်းချက်များ')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">စည်းကမ်းချက်များ</h1>

    <div class="space-y-8">
        <!-- General Terms -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">အထွေထွေ စည်းကမ်းချက်များ</h2>
            <div class="space-y-4">
                <p class="text-gray-600">
                    ၁။ ဝန်ဆောင်မှုကို အသုံးပြုရန် အသက် ၁၈ နှစ်ပြည့်ပြီး ဖြစ်ရမည်။
                </p>
                <p class="text-gray-600">
                    ၂။ အကောင့်တစ်ခုလျှင် တစ်ဦးသာ အသုံးပြုခွင့်ရှိသည်။
                </p>
                <p class="text-gray-600">
                    ၃။ မမှန်ကန်သော အချက်အလက်များ ဖြည့်သွင်းခြင်းကို တားမြစ်သည်။
                </p>
                <p class="text-gray-600">
                    ၄။ အကောင့်လုံခြုံရေးအတွက် သုံးစွဲသူကိုယ်တိုင် တာဝန်ယူရမည်။
                </p>
            </div>
        </div>

        <!-- Lottery Rules -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">လော့ထရီ စည်းကမ်းချက်များ</h2>
            <div class="space-y-4">
                <p class="text-gray-600">
                    ၁။ ထိုးငွေအနည်းဆုံး ၁၀၀ ကျပ်မှ အများဆုံး ၅၀,၀၀၀ ကျပ်အထိ ထိုးနိုင်သည်။
                </p>
                <p class="text-gray-600">
                    ၂။ ပေါက်ဂဏန်းမထွက်မီ သတ်မှတ်အချိန်အတွင်း ထိုးရမည်။
                </p>
                <p class="text-gray-600">
                    ၃။ ပေါက်ဂဏန်းထွက်ပြီးပါက ထိုးထားသောထီကို ပြန်လည်ပြင်ဆင်ခွင့်၊ ပယ်ဖျက်ခွင့် မရှိပါ။
                </p>
                <p class="text-gray-600">
                    ၄။ အနိုင်ရငွေကို ပေါက်ဂဏန်းထွက်ပြီး မိနစ် ၃၀ အတွင်း ထည့်သွင်းပေးမည်။
                </p>
            </div>
        </div>

        <!-- Payment Terms -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">ငွေပေးချေမှုဆိုင်ရာ စည်းကမ်းချက်များ</h2>
            <div class="space-y-4">
                <p class="text-gray-600">
                    ၁။ ငွေသွင်း/ထုတ်ရန် KBZ Pay, Wave Pay, CB Pay တို့ကိုသာ အသုံးပြုနိုင်သည်။
                </p>
                <p class="text-gray-600">
                    ၂။ ငွေသွင်းရာတွင် အနည်းဆုံး ၁,၀၀၀ ကျပ်မှ အများဆုံး ၁,၀၀၀,၀၀၀ ကျပ်အထိ သွင်းနိုင်သည်။
                </p>
                <p class="text-gray-600">
                    ၃။ ငွေထုတ်ယူရန် အနည်းဆုံး ၅,၀၀၀ ကျပ် လက်ကျန်ရှိရမည်။
                </p>
                <p class="text-gray-600">
                    ၄။ ငွေထုတ်ယူမှုကို ၂၄ နာရီအတွင်း ဆောင်ရွက်ပေးမည်။
                </p>
            </div>
        </div>

        <!-- Account Suspension -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">အကောင့်ပိတ်သိမ်းခြင်းဆိုင်ရာ စည်းကမ်းချက်များ</h2>
            <div class="space-y-4">
                <p class="text-gray-600">
                    အောက်ပါအချက်များ တွေ့ရှိပါက အကောင့်ကို ချက်ချင်းပိတ်သိမ်းမည်ဖြစ်သည်:
                </p>
                <ul class="list-disc list-inside space-y-2 text-gray-600">
                    <li>မမှန်ကန်သော အချက်အလက်များ ဖြည့်သွင်းခြင်း</li>
                    <li>တစ်ဦးထက်ပို၍ အကောင့်ဖွင့်ခြင်း</li>
                    <li>မသမာသော နည်းလမ်းဖြင့် ငွေကြေးဆိုင်ရာ လုပ်ဆောင်ချက်များ ပြုလုပ်ခြင်း</li>
                    <li>အခြားသူများ၏ အကောင့်လုံခြုံရေးကို ထိခိုက်စေသော လုပ်ဆောင်ချက်များ ပြုလုပ်ခြင်း</li>
                </ul>
            </div>
        </div>

        <!-- Changes to Terms -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">စည်းကမ်းချက်များ ပြောင်းလဲခြင်း</h2>
            <div class="space-y-4">
                <p class="text-gray-600">
                    ကျွန်ုပ်တို့သည် ဤစည်းကမ်းချက်များကို အချိန်မရွေး ပြောင်းလဲနိုင်ခွင့်ရှိသည်။ အရေးကြီးသော ပြောင်းလဲမှုများကို သုံးစွဲသူများထံ အီးမေးလ်ဖြင့် အကြောင်းကြားမည်ဖြစ်သည်။
                </p>
            </div>
        </div>

        <!-- Contact -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">ဆက်သွယ်ရန်</h2>
            <p class="text-gray-600">
                စည်းကမ်းချက်များနှင့် ပတ်သက်၍ မေးမြန်းလိုပါက အောက်ပါလိပ်စာများသို့ ဆက်သွယ်နိုင်ပါသည်:
            </p>
            <div class="mt-4 space-y-2">
                <p><strong>ဖုန်း:</strong> 09-XXXXXXXXX</p>
                <p><strong>အီးမေးလ်:</strong> support@example.com</p>
                <p><strong>Facebook:</strong> facebook.com/example</p>
                <p><strong>Viber:</strong> viber.me/example</p>
            </div>
        </div>
    </div>
</div>
@endsection
