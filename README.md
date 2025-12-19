# Laravel E-Commerce Application

## Вимоги до системи

- Docker
- Docker Compose
- Git

## Встановлення та запуск

1. Клонувати репозиторій:
```bash
git clone <repository-url>
cd dskur2
```

2. Налаштувати середовище:
```bash
cp cont/.env.example cont/.env
```

3. Запустити Docker контейнери:
```bash
docker-compose up -d
```

4. Виконати міграції та seed бази даних:
```bash
docker exec web php artisan migrate --seed
```

5. Створити symbolic link для storage:
```bash
docker exec web php artisan storage:link
```

6. Build
```bash
docker exec web npm run build
```


Додаток буде доступний за адресою: `http://localhost:8000`

PhpMyAdmin: `http://localhost:8080`

## Архітектура проекту

### Технологічний стек

**Backend:**
- Laravel 12.x (PHP 8.2)
- MySQL 8.0
- Apache HTTP Server

**Frontend:**
- Tailwind CSS 3.x
- Alpine.js 3.x
- Vite 7.x
- Laravel Breeze (authentication scaffolding)

**Development Tools:**
- Docker & Docker Compose (containerization)
- PHPUnit (testing)
- Faker (data generation)
- Laravel Pint (code styling)
- Mockery (mocking framework)

### Структура контейнерів

Проект використовує мікросервісну архітектуру на базі Docker:

- `web` - Laravel application (PHP 8.2 + Apache)
- `mysql_db` - MySQL 8.0 database server
- `phpmyadmin` - Web-based database management interface

## Модель бази даних

### Діаграма зв'язків

```
users (1) ───────< (N) cart_items (N) >─────── (1) products
  │                                                  │
  │                                                  │
  │ (1)                                         (N) │
  │                                                  │
  └─< (N) orders                          categories (N)
       │                                            │
       │ (1)                                        │
       │                                            │
       └─< (N) order_items (N) >───────────────────┘
            
products (1) ───────< (N) product_photos

products (1) ───────< (N) reviews (N) >─────── (1) users
```

### Сутності бази даних

#### users
Таблиця користувачів системи.

**Поля:**
- `id` - Primary key
- `name` - Ім'я користувача
- `email` - Email (unique)
- `password` - Хешований пароль
- `avatar_path` - Шлях до аватара (nullable)
- `role` - Роль (enum: admin, user)
- `remember_token` - Токен автентифікації
- `timestamps` - created_at, updated_at

#### categories
Категорії продуктів.

**Поля:**
- `id` - Primary key
- `name` - Назва категорії
- `description` - Опис (nullable)
- `timestamps`

#### products
Продукти каталогу.

**Поля:**
- `id` - Primary key
- `name` - Назва продукту
- `description` - Опис (nullable)
- `price` - Ціна (decimal 10,2)
- `stock` - Кількість на складі
- `is_archived` - Статус архівації (boolean, indexed)
- `timestamps`

**Індекси:**
- `is_archived` - Для фільтрації активних продуктів
- `stock` - Для запитів наявності

#### category_product
Pivot table для зв'язку many-to-many між категоріями та продуктами.

**Поля:**
- `id` - Primary key
- `category_id` - Foreign key → categories
- `product_id` - Foreign key → products
- `timestamps`

**Обмеження:**
- Unique constraint на `(category_id, product_id)`
- Cascade delete для обох foreign keys

#### product_photos
Фотографії продуктів.

**Поля:**
- `id` - Primary key
- `product_id` - Foreign key → products
- `path` - Шлях до файлу
- `is_main` - Головне зображення (boolean)
- `order` - Порядок відображення
- `timestamps`

**Відношення:**
- Cascade delete при видаленні продукту

#### cart_items
Товари в кошику користувача.

**Поля:**
- `id` - Primary key
- `user_id` - Foreign key → users
- `product_id` - Foreign key → products
- `quantity` - Кількість
- `created_at` - Timestamp

**Обмеження:**
- Unique constraint на `(user_id, product_id)`
- Cascade delete для обох foreign keys

#### orders
Замовлення користувачів.

**Поля:**
- `id` - Primary key
- `user_id` - Foreign key → users
- `order_number` - Номер замовлення (unique)
- `status` - Статус (enum: pending, processing, completed, cancelled)
- `total_amount` - Загальна сума (decimal 10,2)
- `shipping_address` - Адреса доставки
- `notes` - Примітки (nullable)
- `timestamps`

#### order_items
Позиції замовлення.

**Поля:**
- `id` - Primary key
- `order_id` - Foreign key → orders (cascade delete)
- `product_id` - Foreign key → products
- `product_price` - Ціна продукту на момент замовлення (decimal 10,2)
- `quantity` - Кількість
- `subtotal` - Проміжний підсумок (decimal 10,2)
- `timestamps`

#### reviews
Відгуки користувачів про продукти.

**Поля:**
- `id` - Primary key
- `product_id` - Foreign key → products
- `user_id` - Foreign key → users
- `stars` - Рейтинг (unsigned tinyint)
- `comment` - Текст відгуку (nullable)
- `timestamps`

**Обмеження:**
- Unique constraint на `(product_id, user_id)` - один відгук на продукт від користувача
- Cascade delete для обох foreign keys

## MVC Pattern

### Models
Eloquent ORM models з визначеними відношеннями:
- `User` - Аутентифікація, кошик, замовлення, відгуки
- `Product` - Категорії, фото, відгуки, кошики, позиції замовлень
- `Category` - Many-to-many відношення з продуктами
- `Order` - Належить користувачу, має позиції
- `OrderItem` - Належить замовленню та продукту
- `CartItem` - Належить користувачу та продукту
- `Review` - Належить користувачу та продукту
- `ProductPhoto` - Належить продукту

### Controllers
HTTP request handling у директорії `app/Http/Controllers/`:
- Resource controllers для CRUD операцій
- Authorization через policies
- Request validation через Form Requests

### Views
Blade templates у директорії `resources/views/`:
- Layout components
- Livewire/Alpine.js reactive components
- Tailwind CSS utility classes

## Routing

Маршрути визначені у `routes/`:
- `web.php` - Web routes
- `auth.php` - Authentication routes (Breeze)
- `console.php` - Artisan commands

## Asset Pipeline

Vite.js compilation:
- Entry point: `resources/js/app.js`
- CSS: `resources/css/app.css`
- PostCSS processing з Tailwind
- Hot Module Replacement (HMR) у development mode

## Testing

PHPUnit test suite:
- `tests/Feature/` - Integration tests
- `tests/Unit/` - Unit tests

Запуск тестів:
```bash
docker exec web php artisan test
```

## Database Seeding

Faker для генерації тестових даних:
- `DatabaseSeeder` - головний seeder
- Factory definitions у `database/factories/`

## Development Commands

```bash
# Composer dependencies
docker exec web composer install

# NPM dependencies
docker exec web npm install

# Build assets
docker exec web npm run build

# Development mode (HMR)
docker exec web npm run dev

# Clear cache
docker exec web php artisan cache:clear
docker exec web php artisan config:clear
docker exec web php artisan view:clear

# Generate application key
docker exec web php artisan key:generate
```

## Code Style

Laravel Pint для форматування коду згідно зі стандартами PSR-12:
```bash
docker exec web ./vendor/bin/pint
```

## Logging

Monolog integration:
- Logs: `storage/logs/laravel.log`
- Configurable channels у `config/logging.php`
