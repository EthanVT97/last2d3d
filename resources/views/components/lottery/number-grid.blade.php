@props([
    'type' => '2d',
    'maxNumber' => 99,
    'columns' => 10,
    'selectedNumbers' => []
])

<div
    x-data="{
        selectedNumbers: @js($selectedNumbers),
        maxSelections: {{ $type === '2d' ? 1 : 6 }},
        betAmount: 100,
        totalAmount: 0,
        
        toggleNumber(number) {
            const index = this.selectedNumbers.indexOf(number);
            if (index === -1 && this.selectedNumbers.length < this.maxSelections) {
                this.selectedNumbers.push(number);
            } else if (index !== -1) {
                this.selectedNumbers.splice(index, 1);
            }
            this.calculateTotal();
        },
        
        isSelected(number) {
            return this.selectedNumbers.includes(number);
        },
        
        calculateTotal() {
            this.totalAmount = this.selectedNumbers.length * this.betAmount;
        },
        
        formatNumber(number) {
            return number.toString().padStart(2, '0');
        }
    }"
    class="space-y-6"
>
    <!-- Number Grid -->
    <div class="grid grid-cols-{{ $columns }} gap-2">
        @for ($i = 0; $i <= $maxNumber; $i++)
            <button
                type="button"
                x-on:click="toggleNumber({{ $i }})"
                x-bind:class="{
                    'bg-primary-600 text-white': isSelected({{ $i }}),
                    'hover:bg-gray-100': !isSelected({{ $i }})
                }"
                class="aspect-square rounded-lg border border-gray-300 flex items-center justify-center text-lg font-semibold transition-colors duration-200"
            >
                <span x-text="formatNumber({{ $i }})"></span>
            </button>
        @endfor
    </div>

    <!-- Bet Controls -->
    <div class="bg-gray-50 rounded-lg p-4 space-y-4">
        <div class="flex items-center justify-between">
            <label class="text-sm font-medium text-gray-700">ထိုးကြေး</label>
            <div class="flex space-x-2">
                @foreach ([100, 500, 1000, 5000, 10000] as $amount)
                    <button
                        type="button"
                        x-on:click="betAmount = {{ $amount }}; calculateTotal()"
                        x-bind:class="{
                            'bg-primary-600 text-white': betAmount === {{ $amount }},
                            'bg-white hover:bg-gray-50': betAmount !== {{ $amount }}
                        }"
                        class="px-3 py-1 rounded border border-gray-300 text-sm font-medium transition-colors duration-200"
                    >
                        {{ number_format($amount) }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700">ရွေးချယ်ထားသော နံပါတ်များ:</span>
            <div class="flex flex-wrap gap-2">
                <template x-for="number in selectedNumbers" :key="number">
                    <span 
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800"
                        x-text="formatNumber(number)"
                    ></span>
                </template>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700">စုစုပေါင်း:</span>
            <span class="text-lg font-bold text-primary-600" x-text="formatMoney(totalAmount)"></span>
        </div>

        <button
            type="submit"
            x-bind:disabled="selectedNumbers.length === 0"
            x-bind:class="{
                'opacity-50 cursor-not-allowed': selectedNumbers.length === 0
            }"
            class="w-full bg-primary-600 text-white py-2 px-4 rounded-md font-semibold hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200"
        >
            ထိုးမည်
        </button>
    </div>
</div>
