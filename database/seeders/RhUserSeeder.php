<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class RhUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'rh@biofarm.com'],
            [
                'name' => 'Ressources Humaines',
                'password' => 'rh123456',
                'role' => 'rh',
                'email_verified_at' => Carbon::now(),
            ]
        );
    }
}
