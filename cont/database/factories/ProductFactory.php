<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Ім'я моделі, яку використовує ця фабрика.
     */
    protected $model = Product::class;

    /**
     * Визначення дефолтного стану моделі.
     */
    public function definition(): array
    {
        // Генеруємо назву з 3-х слів (наприклад, "Smart Wireless Headphones")
        $name = $this->faker->unique()->words(3, true);
        $name = ucfirst($name);

        return [
            'name' => $name,
            'description' => $this->faker->realText(500), // Реалістичний текст опису
            'price' => $this->faker->randomFloat(2, 100, 10000), // Ціна від 100 до 10000
            'stock' => $this->faker->numberBetween(0, 100), // Кількість на складі
            'is_archived' => $this->faker->boolean(10), // 10% шанс, що товар в архіві
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}
