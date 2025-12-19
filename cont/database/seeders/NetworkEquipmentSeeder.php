<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NetworkEquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::firstOrCreate(
            ['name' => 'Мережеве обладнання'],
            ['description' => 'Роутери, точки доступу, комутатори та інше мережеве обладнання.']
        );

        $faker = fake('uk_UA');

        $brands = [
            'TP-Link', 'ASUS', 'MikroTik', 'Ubiquiti', 'D-Link', 'NETGEAR', 'Cisco', 'Tenda', 'Huawei', 'Zyxel', 'Linksys',
        ];

        $types = [
            // type => [label, minPrice, maxPrice]
            'router' => ['Роутер', 1200, 12000],
            'switch' => ['Свіч', 800, 18000],
            'access_point' => ['Точка доступу', 1500, 14000],
            'mesh' => ['Mesh-система', 3000, 22000],
            'firewall' => ['Мережевий шлюз/фаєрвол', 2500, 30000],
            'poe' => ['PoE-інжектор/адаптер', 350, 3500],
            'modem' => ['4G/5G модем/роутер', 1800, 15000],
            'nic' => ['Мережева карта/адаптер', 250, 3500],
        ];

        $modelTokens = [
            'AX', 'AC', 'BE', 'GS', 'SG', 'RB', 'UniFi', 'CRS', 'TL', 'RT', 'EX', 'XR',
        ];

        $uniqueNames = [];
        $created = 0;
        $maxAttempts = 300;

        for ($attempt = 0; $attempt < $maxAttempts && $created < 30; $attempt++) {
            $brand = $faker->randomElement($brands);
            [$typeLabel, $minPrice, $maxPrice] = $faker->randomElement(array_values($types));
            $token = $faker->randomElement($modelTokens);
            $series = $faker->numberBetween(5, 999);
            $suffix = $faker->optional(0.65)->randomElement(['V2', 'Pro', 'Lite', 'Plus', 'Max', 'S', 'G', 'AX', 'Wi‑Fi 6', 'Wi‑Fi 6E']);
            $short = trim($token . '-' . $series . ($suffix ? ' ' . $suffix : ''));

            $name = trim("{$brand} {$short} {$typeLabel}");
            $name = Str::of($name)->replace('  ', ' ')->toString();

            if (isset($uniqueNames[$name])) {
                continue;
            }
            $uniqueNames[$name] = true;

            $features = $faker->randomElements([
                'Gigabit Ethernet',
                'Wi‑Fi 6',
                'Wi‑Fi 6E',
                'OFDMA та MU‑MIMO',
                'PoE',
                'керований VLAN',
                'VPN',
                'WPA3',
                '2.5G порт',
                'підтримка Mesh',
                'QoS',
                'Dual‑band',
            ], $faker->numberBetween(2, 4));

            $description = $faker->sentence(2) . ' ' . 'Ключові особливості: ' . implode(', ', $features) . '.';

            $product = Product::updateOrCreate(
                ['name' => $name],
                [
                    'description' => $description,
                    'price' => $faker->randomFloat(2, $minPrice, $maxPrice),
                    'stock' => $faker->numberBetween(0, 60),
                    'is_archived' => false,
                ]
            );

            $product->categories()->syncWithoutDetaching([$category->id]);
            $created++;
        }
    }
}
