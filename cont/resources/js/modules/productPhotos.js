/**
 * Модуль керування фото товарів (створення/редагування)
 * Відповідає за: превʼю нових фото, порядок, позначення основного фото, видалення
 */

export class ProductPhotosManager {
    constructor() {
        this.selectedFiles = [];
        this.mainIndex = 0;
        this.input = document.getElementById('photos');
        this.preview = document.getElementById('photoPreview');
        this.mainIndexInput = document.getElementById('main_new_index');
    }

    /**
     * Синхронізація файлів у input (основне фото - перше)
     */
    syncFilesToInput() {
        if (!this.input) return;
        
        const dataTransfer = new DataTransfer();
        const ordered = [...this.selectedFiles];
        
        // Основне фото ставимо першим
        if (ordered.length && this.mainIndex > 0 && this.mainIndex < ordered.length) {
            const [mainFile] = ordered.splice(this.mainIndex, 1);
            ordered.unshift(mainFile);
        }
        
        ordered.forEach(file => dataTransfer.items.add(file));
        this.input.files = dataTransfer.files;
        
        if (this.mainIndexInput) {
            this.mainIndexInput.value = ordered.length ? '0' : '';
        }
    }

    /**
     * Рендеринг списку вибраних фото
     */
    renderPhotoPreview() {
        if (!this.preview) return;
        
        this.preview.innerHTML = '';

        if (this.selectedFiles.length === 0) {
            this.preview.innerHTML = '<p class="text-ui-muted text-sm">Файлів не обрано</p>';
            return;
        }

        this.selectedFiles.forEach((file, index) => {
            const isMain = this.mainIndex === index;
            const div = document.createElement('div');
            div.className = 'flex items-center p-3 bg-ui-bg rounded-lg border border-ui-border/40 hover:bg-ui-bg/40 transition';
            
            const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
            const mainButtonClass = isMain 
                ? 'px-2 py-1 text-xs rounded border border-ui-border/40 text-ui-accent' 
                : 'px-2 py-1 text-xs rounded border border-ui-border/40 text-ui-fg';
            const mainButtonText = isMain ? 'Основне' : 'Зробити основним';
            
            div.innerHTML = `
                <i class="fas fa-image text-ui-accent mr-3 text-lg"></i>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-ui-fg truncate">${this.escapeHtml(file.name)}</p>
                    <p class="text-xs text-ui-muted">${fileSizeMB} МБ</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="${mainButtonClass}" data-action="set-main" data-index="${index}">
                        ${mainButtonText}
                    </button>
                    <button type="button" class="px-2 py-1 text-xs text-ui-accent hover:brightness-95" data-action="move" data-index="${index}" data-direction="up">
                        ↑
                    </button>
                    <button type="button" class="px-2 py-1 text-xs text-ui-accent hover:brightness-95" data-action="move" data-index="${index}" data-direction="down">
                        ↓
                    </button>
                    <button type="button" class="px-2 py-1 text-xs text-ui-accent2 hover:brightness-110" data-action="remove" data-index="${index}">
                        Видалити
                    </button>
                </div>
            `;
            
            this.preview.appendChild(div);
        });
    }

    /**
     * Додавання нових файлів
     */
    handlePhotosChange(event) {
        const newFiles = Array.from(event.target.files);
        this.selectedFiles = [...this.selectedFiles, ...newFiles];
        
        if (this.selectedFiles.length === 1) {
            this.mainIndex = 0;
        }
        
        this.syncFilesToInput();
        this.renderPhotoPreview();
    }

    /**
     * Видалення фото
     */
    removePhoto(index) {
        this.selectedFiles.splice(index, 1);
        
        if (this.mainIndex >= this.selectedFiles.length) {
            this.mainIndex = 0;
        }
        
        this.syncFilesToInput();
        this.renderPhotoPreview();
    }

    /**
     * Позначити фото як основне
     */
    setMain(index) {
        this.mainIndex = index;
        this.syncFilesToInput();
        this.renderPhotoPreview();
    }

    /**
     * Переміщення фото
     */
    moveFile(index, direction) {
        const delta = direction === 'up' ? -1 : 1;
        const newIndex = index + delta;
        
        if (newIndex < 0 || newIndex >= this.selectedFiles.length) return;
        
        [this.selectedFiles[index], this.selectedFiles[newIndex]] = 
        [this.selectedFiles[newIndex], this.selectedFiles[index]];
        
        if (this.mainIndex === index) {
            this.mainIndex = newIndex;
        } else if (this.mainIndex === newIndex) {
            this.mainIndex = index;
        }
        
        this.syncFilesToInput();
        this.renderPhotoPreview();
    }

    /**
     * Escape HTML для безпеки
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Ініціалізація обробників подій
     */
    init() {
        this.renderPhotoPreview();
        
        // Делегування кліків на кнопки
        if (this.preview) {
            this.preview.addEventListener('click', (e) => {
                const button = e.target.closest('[data-action]');
                if (!button) return;
                
                const action = button.dataset.action;
                const index = parseInt(button.dataset.index, 10);
                
                if (!Number.isFinite(index)) return;
                
                switch (action) {
                    case 'set-main':
                        this.setMain(index);
                        break;
                    case 'move':
                        this.moveFile(index, button.dataset.direction);
                        break;
                    case 'remove':
                        this.removePhoto(index);
                        break;
                }
            });
        }
        
        // Обробник зміни input файлів
        if (this.input) {
            this.input.addEventListener('change', (e) => this.handlePhotosChange(e));
        }
    }
}

/**
 * Менеджер наявних фото (тільки для редагування)
 */
export class ExistingPhotosManager {
    constructor() {
        this.container = document.querySelector('[data-existing-photos]');
    }

    /**
     * Синхронізація порядку фото
     */
    syncPhotoBadges() {
        const items = Array.from(document.querySelectorAll('[data-photo-item]'));
        
        items.forEach((item, index) => {
            const orderInput = item.querySelector('[data-photo-order-input]');
            if (orderInput) {
                orderInput.value = String(index);
            }
            
            const badge = item.querySelector('[data-photo-order-badge]');
            if (badge) {
                badge.textContent = String(index + 1);
            }
        });
    }

    /**
     * Перевірка чи фото позначене для видалення
     */
    isMarkedForDelete(item) {
        const deleteInput = item.querySelector('[data-photo-delete-input]');
        return deleteInput ? !deleteInput.disabled : false;
    }

    /**
     * Позначити/зняти позначку видалення
     */
    setMarkedForDelete(item, shouldDelete) {
        const deleteInput = item.querySelector('[data-photo-delete-input]');
        const deleteBtn = item.querySelector('[data-photo-delete-btn]');
        const note = item.querySelector('[data-photo-delete-note]');
        const mainRadio = item.querySelector('input[type="radio"][name="main_photo"]');

        if (!deleteInput || !deleteBtn) return;

        deleteInput.disabled = !shouldDelete;

        item.classList.toggle('opacity-50', shouldDelete);
        item.classList.toggle('pointer-events-none', false);

        if (note) {
            note.classList.toggle('hidden', !shouldDelete);
        }
        
        deleteBtn.textContent = shouldDelete ? 'Відновити' : 'Видалити';
        deleteBtn.classList.toggle('text-ui-accent2', !shouldDelete);
        deleteBtn.classList.toggle('hover:brightness-110', !shouldDelete);
        deleteBtn.classList.toggle('text-ui-accent', shouldDelete);
        deleteBtn.classList.toggle('hover:brightness-95', shouldDelete);

        if (mainRadio) {
            if (shouldDelete) {
                const wasChecked = mainRadio.checked;
                mainRadio.disabled = true;
                
                if (wasChecked) {
                    // Знайти інше фото для main
                    const items = Array.from(document.querySelectorAll('[data-photo-item]'));
                    const other = items.find(el => el !== item && !this.isMarkedForDelete(el));
                    const otherRadio = other?.querySelector('input[type="radio"][name="main_photo"]');
                    
                    if (otherRadio) {
                        otherRadio.checked = true;
                    }
                }
            } else {
                mainRadio.disabled = false;
            }
        }
    }

    /**
     * Ініціалізація обробників
     */
    init() {
        if (!this.container) return;
        
        this.syncPhotoBadges();
        
        document.addEventListener('click', (e) => {
            const moveBtn = e.target.closest('[data-photo-move]');
            const deleteBtn = e.target.closest('[data-photo-delete-btn]');

            if (!moveBtn && !deleteBtn) return;

            const item = e.target.closest('[data-photo-item]');
            if (!item) return;

            // Переміщення
            if (moveBtn) {
                const direction = moveBtn.getAttribute('data-photo-move');
                const items = Array.from(document.querySelectorAll('[data-photo-item]'));
                const index = items.indexOf(item);
                const swapWith = direction === 'up' ? items[index - 1] : items[index + 1];
                
                if (!swapWith) return;

                if (direction === 'up') {
                    swapWith.before(item);
                } else {
                    swapWith.after(item);
                }

                this.syncPhotoBadges();
                return;
            }

            // Видалення
            if (deleteBtn) {
                const shouldDelete = !this.isMarkedForDelete(item);
                this.setMarkedForDelete(item, shouldDelete);
            }
        });
    }
}
