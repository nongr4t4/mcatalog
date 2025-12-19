import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Імпорт модулів
import { ProductPhotosManager, ExistingPhotosManager } from './modules/productPhotos.js';
import { initCatalogShowPage } from './modules/catalogShow.js';
import { initGlobalHelpers } from './modules/uiHelpers.js';
import { initDashboardChart } from './modules/salesChart.js';

// Експорт для використання в Blade шаблонах
window.ProductPhotosManager = ProductPhotosManager;
window.ExistingPhotosManager = ExistingPhotosManager;
window.initCatalogShowPage = initCatalogShowPage;
window.initDashboardChart = initDashboardChart;

// Ініціалізація глобальних helper-функцій ОДРАЗУ (не чекаючи DOMContentLoaded)
initGlobalHelpers();

// ===== AJAX: кошик/сповіщення (глобально) =====
window.Shop = {
    // CSRF токен
    csrfToken: document.querySelector('meta[name="csrf-token"]')?.content,

    // Показати сповіщення
    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg animate-fade-in ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('animate-fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    },


    // Оновити лічильник кошика
    updateCartCount(count) {
        const cartBadges = document.querySelectorAll('[data-cart-count]');
        cartBadges.forEach(badge => {
            if (count > 0) {
                badge.textContent = count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });
    },

    // Додати до кошика через AJAX
    async addToCart(productId, quantity = 1) {
        try {
            const response = await fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ quantity }),
                redirect: 'follow',
            });

            // Якщо редирект або 401 — ведемо на логін
            const contentType = response.headers.get('content-type') || '';
            if (response.status === 401 || response.redirected || !contentType.includes('application/json')) {
                window.location.href = '/login';
                return;
            }

            if (response.ok) {
                const data = await response.json();
                const message = data.message || 'Товар додано до кошика!';
                this.showNotification(message, 'success');
                if (data.cartCount !== undefined) {
                    this.updateCartCount(data.cartCount);
                }
                return data;
            }

            throw new Error('Помилка додавання');
        } catch (error) {
            this.showNotification('Помилка додавання товару', 'error');
            throw error;
        }
    },
};

// ===== Ініціалізація при завантаженні =====
document.addEventListener('DOMContentLoaded', () => {
    // Автоматичне приховування алертів
    document.querySelectorAll('[data-auto-dismiss]').forEach(alert => {
        const delay = parseInt(alert.dataset.autoDismiss) || 5000;
        setTimeout(() => {
            alert.classList.add('animate-fade-out');
            setTimeout(() => alert.remove(), 300);
        }, delay);
    });

    // ===== Глобально: делегований клік "Додати в кошик" =====
    document.addEventListener('click', (event) => {
        const btn = event.target.closest?.('[data-add-to-cart]');
        if (!btn) return;

        event.preventDefault();
        event.stopPropagation();

        if (!window.Shop || typeof window.Shop.addToCart !== 'function') {
            console.error('Shop.addToCart is not available');
            return;
        }

        if (btn.disabled) return;

        // Кількість: або 1, або з інпуту
        let quantity = 1;
        const qtySelector = btn.getAttribute('data-add-to-cart-qty-input');
        if (qtySelector) {
            const input = document.querySelector(qtySelector);
            const value = input ? parseInt(input.value, 10) : NaN;
            quantity = Number.isFinite(value) && value > 0 ? value : 1;
        }

        const productId = btn.getAttribute('data-add-to-cart');
        const originalHtml = btn.innerHTML;
        const hasText = (btn.textContent || '').trim().length > 0;
        const spinnerOnly = '<i class="fas fa-spinner fa-spin"></i>';
        const spinnerWithText = '<i class="fas fa-spinner fa-spin mr-2"></i>Додаємо...';

        btn.disabled = true;
        btn.classList.add('opacity-75', 'pointer-events-none');
        btn.innerHTML = hasText ? spinnerWithText : spinnerOnly;

        window.Shop.addToCart(productId, quantity)
            .catch(() => {})
            .finally(() => {
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'pointer-events-none');
                btn.innerHTML = originalHtml;
            });
    });

    // Плавна прокрутка для якірних посилань
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});
