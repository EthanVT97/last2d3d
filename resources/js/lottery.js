import './bootstrap';
import Alpine from 'alpinejs';
import Echo from 'laravel-echo';

window.Alpine = Alpine;

// Real-time updates for lottery draws
const echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Listen for draw results
echo.channel('lottery')
    .listen('DrawResultAnnounced', (e) => {
        if (document.body.dataset.lotteryType === e.type) {
            updateDrawResult(e.number);
            checkWinningPlays(e.number);
        }
    });

// Listen for balance updates
echo.private(`user.${userId}`)
    .listen('BalanceUpdated', (e) => {
        updateBalance(e.balance);
    });

// Update UI functions
function updateDrawResult(number) {
    const resultElement = document.querySelector('.latest-result');
    if (resultElement) {
        resultElement.textContent = number.toString().padStart(2, '0');
        resultElement.classList.add('animate-bounce');
        setTimeout(() => {
            resultElement.classList.remove('animate-bounce');
        }, 1000);
    }
}

function updateBalance(newBalance) {
    const balanceElement = document.querySelector('.user-balance');
    if (balanceElement) {
        balanceElement.textContent = formatMoney(newBalance);
        balanceElement.classList.add('animate-pulse');
        setTimeout(() => {
            balanceElement.classList.remove('animate-pulse');
        }, 1000);
    }
}

function checkWinningPlays(winningNumber) {
    const plays = document.querySelectorAll('.play-item');
    plays.forEach(play => {
        const playNumber = play.dataset.number;
        const statusElement = play.querySelector('.play-status');
        
        if (playNumber === winningNumber.toString()) {
            statusElement.innerHTML = `
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    ထီပေါက်
                </span>
            `;
            play.classList.add('bg-green-50');
        } else {
            statusElement.innerHTML = `
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    မပေါက်
                </span>
            `;
        }
    });
}

// Utility functions
window.formatMoney = (amount) => {
    return new Intl.NumberFormat('my-MM', {
        style: 'decimal',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount) + ' ကျပ်';
};

// Initialize Alpine.js
Alpine.start();
