import './bootstrap';
import Alpine from 'alpinejs';

// Format money with commas and kyat symbol
window.formatMoney = (amount) => {
    return new Intl.NumberFormat('my-MM', {
        style: 'decimal',
        maximumFractionDigits: 0
    }).format(amount) + ' ကျပ်';
};

window.Alpine = Alpine;
Alpine.start();
