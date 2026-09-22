<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RpUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'rp@biofarmtrading.com'],
            [
                'name' => 'Responsable Production',
                'password' => Hash::make('password'),
                'role' => 'rp',
            ]
        );
    }
}
