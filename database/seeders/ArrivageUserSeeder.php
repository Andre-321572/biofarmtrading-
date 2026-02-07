<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ArrivageUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'arrivage@biofarm.com'],
            [
                'name' => 'Gestionnaire Arrivage',
                'password' => Hash::make('password123'),
                'role' => 'arrivage',
            ]
        );
    }
}
