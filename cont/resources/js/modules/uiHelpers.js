/**
 * Модуль для інтерактивних елементів кошика та адмін-панелі
 */

/**
 * Керування кількістю товарів у кошику
 */
export class CartQuantityManager {
    /**
     * Збільшити кількість
     */
    static increase(button) {
        const input = button.parentElement.querySelector('input[name="quantity"]');
        if (!input) return;

        const max = parseInt(input.max, 10) || Infinity;
        const current = parseInt(input.value, 10);

        if (current >= max) {
            alert('Досягнуто максимальної доступної кількості на складі.');
            return;
        }

        input.value = current + 1;
        input.form.submit();
    }

    /**
     * Зменшити кількість (не нижче 1)
     */
    static decrease(button) {
        const input = button.parentElement.querySelector('input[name="quantity"]');
        if (!input) return;

        const current = parseInt(input.value, 10);

        if (current > 1) {
            input.value = current - 1;
            input.form.submit();
        }
    }
}

/**
 * Лайтбокс для перегляду фото товару
 */
export class PhotoLightbox {
    /**
     * Відкрити лайтбокс
     */
    static open(imageSrc) {
        const lightbox = document.createElement('div');
        lightbox.className = 'fixed inset-0 bg-ui-bg/90 flex items-center justify-center z-50';
        
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                lightbox.remove();
            }
        });

        lightbox.innerHTML = `
            <div class="relative max-w-4xl w-full h-full flex items-center justify-center p-4">
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="absolute top-4 right-4 text-ui-fg hover:text-ui-accent z-10">
                    <i class="fas fa-times text-2xl"></i>
                </button>
                <img src="${PhotoLightbox.escapeHtml(imageSrc)}" 
                     class="max-w-full max-h-full object-contain" 
                     alt="Лайтбокс">
            </div>
        `;

        document.body.appendChild(lightbox);
    }

    /**
     * Escape HTML
     */
    static escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

/**
 * Ініціалізація глобальних функцій для Blade шаблонів
 */
export function initGlobalHelpers() {
    // Для кошика
    window.increaseQuantity = CartQuantityManager.increase;
    window.decreaseQuantity = CartQuantityManager.decrease;
    
    // Для адмін-панелі
    window.openLightbox = PhotoLightbox.open;
}
