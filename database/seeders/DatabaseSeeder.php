<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'username' => 'administrator',
            'email' => config('app.email'),
            'password' => bcrypt('Qwerty123'),
            'avatar' => '/images/avatars/avatar-1.png'
        ]);
        $this->call(LaratrustSeeder::class);
        Artisan::call('app:akses');
    }
}
