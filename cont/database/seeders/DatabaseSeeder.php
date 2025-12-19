<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@local.loc'],
            [
                'name' => 'Адміністратор',
                'password' => Hash::make('12341234'),
                'role' => 'admin',
                'avatar_path' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@local.loc'],
            [
                'name' => 'Користувач',
                'password' => Hash::make('12341234'),
                'role' => 'user',
                'avatar_path' => null,
            ]
        );

        $this->call(NetworkEquipmentSeeder::class);
    }
}
