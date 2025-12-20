/**
 * Модуль галереї товару на сторінці каталогу
 * Відповідає за: перемикання головного фото, слайдер відгуків, форму рейтингу
 */

export class ProductGallery {
    /**
     * Перемикання головного фото в галереї
     */
    static swapMainImage(src) {
        const mainImage = document.getElementById('mainImage');
        if (mainImage) {
            mainImage.src = src;
        }
    }
}

/**
 * Менеджер форми рейтингу відгуків
 */
export class RatingPicker {
    constructor(pickerElement) {
        this.picker = pickerElement;
    }

    /**
     * Синхронізація візуального стану кнопок з вибраною оцінкою
     */
    syncVisualState() {
        if (!this.picker) return;

        const inputs = this.picker.querySelectorAll('input[type="radio"][name="stars"]');
        
        inputs.forEach((input) => {
            const pill = input.closest('label')?.querySelector('[data-rating-pill]');
            if (!pill) return;

            pill.classList.remove('border-ui-accent', 'bg-ui-bg', 'text-ui-fg');
            pill.classList.remove('border-ui-border/40', 'text-ui-fg');

            if (input.checked) {
                pill.classList.add('border-ui-accent', 'bg-ui-bg', 'text-ui-fg');
            } else {
                pill.classList.add('border-ui-border/40', 'text-ui-fg');
            }
        });
    }

    /**
     * Ініціалізація
     */
    init() {
        if (!this.picker) return;

        this.picker.addEventListener('change', (e) => {
            if (e.target && e.target.matches('input[type="radio"][name="stars"]')) {
                this.syncVisualState();
            }
        });

        this.syncVisualState();
    }
}

/**
 * Менеджер горизонтального слайдера відгуків
 */
export class ReviewSlider {
    constructor(sliderElement) {
        this.root = sliderElement;
        this.track = this.root?.querySelector('[data-review-track]');
        this.cards = Array.from(this.root?.querySelectorAll('[data-review-card]') || []);
        this.ticks = Array.from(this.root?.querySelectorAll('[data-review-tick]') || []);
        this.btnPrev = this.root?.querySelector('[data-review-prev]');
        this.btnNext = this.root?.querySelector('[data-review-next]');
        this.activeIndex = 0;
        this.scrollRaf = null;
    }

    /**
     * Встановити активну картку
     */
    setActive(index, options = {}) {
        const { scrollIntoView = false } = options;
        const nextIndex = Math.max(0, Math.min(this.cards.length - 1, index));
        this.activeIndex = nextIndex;

        this.ticks.forEach((tick, i) => {
            if (i === this.activeIndex) {
                tick.classList.remove('text-ui-border');
                tick.classList.add('text-ui-accent');
                tick.setAttribute('aria-current', 'true');
            } else {
                tick.classList.remove('text-ui-accent');
                tick.classList.add('text-ui-border');
                tick.removeAttribute('aria-current');
            }
        });

        if (scrollIntoView && this.cards[this.activeIndex]) {
            this.cards[this.activeIndex].scrollIntoView({ 
                behavior: 'smooth', 
                inline: 'center', 
                block: 'nearest' 
            });
        }
    }

    /**
     * Знайти найближчу картку до центру viewport
     */
    getClosestIndex() {
        if (!this.track) return 0;

        const trackRect = this.track.getBoundingClientRect();
        const centerX = trackRect.left + trackRect.width / 2;

        let bestIndex = 0;
        let bestDist = Infinity;

        this.cards.forEach((card, i) => {
            const rect = card.getBoundingClientRect();
            const cardCenter = rect.left + rect.width / 2;
            const dist = Math.abs(cardCenter - centerX);

            if (dist < bestDist) {
                bestDist = dist;
                bestIndex = i;
            }
        });

        return bestIndex;
    }

    /**
     * Ініціалізація обробників
     */
    init() {
        if (!this.root || !this.track || this.cards.length === 0 || this.ticks.length !== this.cards.length) {
            return;
        }

        // Кнопки навігації
        this.btnPrev?.addEventListener('click', () => {
            this.setActive(this.activeIndex - 1, { scrollIntoView: true });
        });

        this.btnNext?.addEventListener('click', () => {
            this.setActive(this.activeIndex + 1, { scrollIntoView: true });
        });

        // Клік на індикатори
        this.ticks.forEach((tick) => {
            tick.addEventListener('click', () => {
                const index = Number(tick.getAttribute('data-review-tick'));
                if (Number.isFinite(index)) {
                    this.setActive(index, { scrollIntoView: true });
                }
            });
        });

        // Оновлення при скролі
        this.track.addEventListener('scroll', () => {
            if (this.scrollRaf) {
                cancelAnimationFrame(this.scrollRaf);
            }
            this.scrollRaf = requestAnimationFrame(() => {
                this.setActive(this.getClosestIndex());
            });
        }, { passive: true });

        // Встановити початковий стан
        this.setActive(this.getClosestIndex());
    }
}

/**
 * Ініціалізація всіх модулів на сторінці каталогу товару
 */
export function initCatalogShowPage() {
    // Рейтинг відгуків
    const ratingPickerElement = document.querySelector('[data-rating-picker]');
    if (ratingPickerElement) {
        const ratingPicker = new RatingPicker(ratingPickerElement);
        ratingPicker.init();
    }

    // Слайдер відгуків
    const reviewSliderElement = document.querySelector('[data-review-slider]');
    if (reviewSliderElement) {
        const reviewSlider = new ReviewSlider(reviewSliderElement);
        reviewSlider.init();
    }

    // Глобальна функція для Blade шаблонів
    window.swapMainImage = ProductGallery.swapMainImage;
}
